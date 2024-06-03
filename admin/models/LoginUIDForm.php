<?php

namespace admin\models;

use common\models\AppModel;
use common\modules\user\helpers\UserHelper;
use common\modules\user\models\User;
use Yii;

class LoginUIDForm extends AppModel
{
    public ?string $uid = null;
    private ?User $_user;

    final public function attributeLabels(): array
    {
        return [
            'uid' => 'Идентификатор пользователя',
        ];
    }

    /**
     * Finds user by [[uid]]
     */
    final protected function getUser(): ?User
    {
        if (!isset($this->_user)) {
            $this->_user = User::findByUID($this->uid);
            if ($this->_user == null) {
                $this->_user = UserHelper::createNewUserByUid($this->uid);
            }
        }

        return $this->_user;
    }

    final public function login(): bool
    {
        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }
}
