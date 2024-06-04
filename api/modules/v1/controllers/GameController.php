<?php

namespace api\modules\v1\controllers;

use api\behaviors\returnStatusBehavior\JsonSuccess;
use api\behaviors\returnStatusBehavior\RequestFormData;
use common\components\exceptions\ModelSaveException;
use common\models\Game;
use common\modules\user\helpers\UserHelper;
use common\modules\user\models\User;
use OpenApi\Attributes as OA;
use PHPUnit\Exception;
use Random\RandomException;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class GameController extends AppController
{
    /**
     * {@inheritdoc}
     */
    public $modelClass = Game::class;

    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'auth' => ['except' => ['test-rating', 'test-games-for-users']],
        ]);
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
    #[JsonSuccess(content: [
        new OA\Property(
            property: 'game', type: 'array',
            items: new OA\Items('#/components/schemas/Game'),
        )
    ])]
    public function actionStart(): array
    {
        return $this->returnSuccess(Game::startGame(Yii::$app->user->identity), 'game');
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
        requiredProps: ['points'],
        properties: [
            new OA\Property(property: 'points', description: 'Очки за игру', type: 'integer'),
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
        try {
            $game = Game::stopGame(Yii::$app->user->identity, (int)Yii::$app->request->post('points'));
        } catch (Exception $e) {
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess($game, 'game');
    }

    #[OA\Post(
        path: '/game/test',
        operationId: 'game-test',
        summary: 'Много игр у пользователя',
        security: [['bearerAuth' => []]],
        tags: ['game']
    )]
    #[JsonSuccess(content: [
        new OA\Property(
            property: 'game', type: 'array',
            items: new OA\Items('#/components/schemas/Game'),
        )
    ])]
    public function actionTest()
    {
        $user = Yii::$app->user->identity;
        for ($i = 0; $i < 2000; $i++) {
            Game::startGame($user);
            Game::stopGame($user, random_int(50, 100));
        }
    }

    /**
     * @throws \yii\base\Exception
     * @throws ModelSaveException
     * @throws RandomException
     */
    #[OA\Post(
        path: '/game/test-rating',
        operationId: 'game-test-rating',
        summary: 'Много пользователей с играми',
        security: [['bearerAuth' => []]],
        tags: ['game']
    )]
    #[JsonSuccess(content: [
        new OA\Property(
            property: 'game', type: 'array',
            items: new OA\Items('#/components/schemas/Game'),
        )
    ])]
    public function actionTestRating()
    {
        for ($i = 0; $i < 2000; $i++) {
            $user = UserHelper::createNewUserByUid(User::generateUID());
            Game::startGame($user);
            Game::stopGame($user, random_int(50, 100));
        }
    }

    /**
     * @throws \yii\base\Exception
     * @throws ModelSaveException
     * @throws RandomException
     */
    #[OA\Post(
        path: '/game/test-games-for-users',
        operationId: 'game-test-points',
        summary: 'Много игр у пользователей',
        security: [['bearerAuth' => []]],
        tags: ['game']
    )]
    #[JsonSuccess(content: [
        new OA\Property(
            property: 'game', type: 'array',
            items: new OA\Items('#/components/schemas/Game'),
        )
    ])]
    public function actionTestGamesForUsers()
    {
        for ($i = 0; $i < 10; $i++) {
            $user = User::find()->orderBy(new Expression('rand()'))->one();
            for ($i = 0; $i < 4; $i++) {
                Game::startGame($user);
                sleep(1);
                Game::stopGame($user, random_int(50, 100));
            }
        }
    }
}
