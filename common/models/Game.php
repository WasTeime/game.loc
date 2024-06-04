<?php

namespace common\models;

use common\models\AppActiveRecord;
use common\modules\user\models\User;
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
        ];
    }

    public function fields()
    {
        return [
            'start',
            'end',
            'points',
            'user',
            'status',
        ];
    }

    final public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
