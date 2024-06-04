<?php

namespace api\modules\v1\controllers;

use admin\enums\GameStatus;
use api\behaviors\returnStatusBehavior\JsonSuccess;
use api\behaviors\returnStatusBehavior\RequestFormData;
use common\models\Game;
use common\modules\user\models\User;
use OpenApi\Attributes as OA;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class GameController extends AppController
{
    /**
     * {@inheritdoc}
     */
    public $modelClass = Game::class;

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), ['auth' => ['except' => ['start', 'stop']]]);
    }

    /**
     * Returns a list of Text's
     */
    #[OA\Post(
        path: '/game/start',
        operationId: 'game-start',
        description: 'Начинает новую игру у пользователя',
        summary: 'Начало игры',
        security: [['bearerAuth' => []]],
        tags: ['game']
    )]
    #[RequestFormData(
        requiredProps: ['uid'],
        properties: [
            new OA\Property(property: 'uid', description: 'UID пользователя', type: 'string'),
        ]
    )]
    #[JsonSuccess(content: [
        new OA\Property(
            property: 'game', type: 'array',
            items: new OA\Items('#/components/schemas/Game'),
        )
    ])]
    public function actionStart(): array
    {
        $uid = Yii::$app->request->post('uid');
        $user = User::findByUID($uid);
        $user->grabAttempt();

        $lastRecord = Game::find()->where(['user_id' => $user->id])->orderBy(['id' => SORT_DESC])->one();

        if ($lastRecord != null && $lastRecord->status == GameStatus::InProcess->value) {
            $lastRecord->status = GameStatus::Abandon->value;
            $lastRecord->save();
        }
        $game = new Game();
        $game->start = time();
        $game->user_id = $user->id;
        $game->status = GameStatus::InProcess->value;
        $game->save();

        return $this->returnSuccess($game, 'game');
    }

    #[OA\Post(
        path: '/game/stop',
        operationId: 'game-stop',
        description: 'Заканчивает новую игру у пользователя',
        summary: 'Конец игры',
        security: [['bearerAuth' => []]],
        tags: ['game']
    )]
    #[RequestFormData(
        requiredProps: ['uid'],
        properties: [
            new OA\Property(property: 'uid', description: 'UID пользователя', type: 'string'),
        ]
    )]
    #[JsonSuccess(content: [
        new OA\Property(
            property: 'game', type: 'array',
            items: new OA\Items('#/components/schemas/Game'),
        )
    ])]
    public function actionStop(): array
    {
        $uid = Yii::$app->request->post('uid');
        $user = User::findByUID($uid);
        $isAttemptGrabbed = $user->grabAttempt();

        $game = Game::find()->where(['user_id' => $user->id])->orderBy(['id' => SORT_DESC])->one();
        $game->end = time();
        $game->status = GameStatus::Finished->value;
        $game->points = $user->attempts == 0 ? 0 : 100;
        $game->save();

        return $this->returnSuccess($game, 'game');
    }
}
