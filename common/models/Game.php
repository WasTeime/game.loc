<?php

namespace common\models;

use admin\enums\GameStatus;
use common\models\AppActiveRecord;
use common\modules\user\models\User;
use Exception;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%game}}".
 *
 * @property int           $id
 * @property int           $start
 * @property int|null      $end
 * @property int|null      $points
 * @property int           $user_id
 * @property int           $status
 *
 * @property-read User     $user
 * @property-read int      $duration
 */
#[Schema(properties: [
    new Property(property: 'start', type: 'integer'),
    new Property(property: 'end', type: 'integer'),
    new Property(property: 'points', type: 'integer'),
    new Property(property: 'user', type: 'string'),
    new Property(property: 'status', type: 'string'),
])]
class Game extends AppActiveRecord
{
    final static public function startGame(User $user) : Game
    {
        $user->grabAttempt();

        Yii::$app->db->createCommand()
            ->update(
                Game::tableName(),
                ['status' => GameStatus::Abandon->value],
                ['status' => GameStatus::InProcess->value, 'user_id' => $user->id]
            )
            ->execute();

        $game = new Game();
        $game->start = time();
        $game->user_id = $user->id;
        $game->status = GameStatus::InProcess->value;
        $game->save();

        return $game;
    }

    /**
     * @throws Exception
     */
    final static public function stopGame(User $user, int $points)
    {
        $game = Game::find()
            ->where(['user_id' => $user->id])
            ->andWhere(['status' => GameStatus::InProcess->value])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if ($game == null) {
            throw new Exception('Активных игр нету');
        }

        //завершаем последнюю игру
        $game->end = time();
        $game->status = GameStatus::Finished->value;
        $game->points = $user->attempts == 0 ? 0 : $points;
        $game->save();

        Rating::updateUserRating($user->id, $game->points);

        return $game;
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%game}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['start', 'user_id', 'status'], 'required'],
            [['start', 'end', 'points', 'user_id', 'status'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    final public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'start' => Yii::t('app', 'Start'),
            'end' => Yii::t('app', 'End'),
            'points' => Yii::t('app', 'Points'),
            'user_id' => Yii::t('app', 'User ID'),
            'status' => Yii::t('app', 'Status'),
            'duration' => Yii::t('app', 'Duration'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function externalAttributes(): array
    {
        return ['user.username'];
    }

    public function fields()
    {
        return [
            'start',
            'end',
            'points',
            'status',
        ];
    }

    final public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    final public function getDuration() : ?int
    {
        return $this->end == null ? null : $this->end - $this->start;
    }
}
