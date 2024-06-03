<?php

namespace common\modules\user\actions;

use admin\models\LoginUIDForm;
use common\modules\user\actions\BaseAction;
use common\modules\user\helpers\UserHelper;
use common\modules\user\models\User;
use common\modules\user\Module;
use Yii;
use yii\web\Response;

class authorizeByUidAction extends BaseAction
{
    private ?User $_user;
    final public function run(): Response|array|string
    {
        $uid = Yii::$app->request->post('uid');

        $this->_user = User::findByUID($uid);
        if ($this->_user == null) {
            $this->_user = UserHelper::createNewUserByUid($uid);
        }

        Yii::$app->user->login($this->_user);

        return $this->controller->returnSuccess(UserHelper::getProfile($this->_user), 'profile');
    }
}
