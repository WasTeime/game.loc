<?php

namespace common\models;

use common\models\AppActiveRecord;
use common\modules\user\models\User;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%rating}}".
 *
 * @property int       $id
 * @property int       $user_id
 * @property int       $max_points
 * @property int       $updated_at Дата изменения
 *
 * @property-read User $user
 */
#[Schema(properties: [
    new Property(property: 'user', type: 'string'),
    new Property(property: 'max_points', type: 'integer'),
    new Property(property: 'updated_at', type: 'integer'),
])]
class Rating extends AppActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%rating}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'max_points', 'updated_at'], 'integer'],
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
            'user_id' => Yii::t('app', 'User ID'),
            'max_points' => Yii::t('app', 'Max Points'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function fields()
    {
        return [
            'user' => fn () => $this->user->username,
            'max_points',
            'updated_at'
        ];
    }

    final public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
