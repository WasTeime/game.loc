<?php

namespace common\models;

use common\enums\Boolean;
use common\modules\user\helpers\UserHelper;
use common\modules\user\models\User;
use Exception;
use Yii;

class SignUpUidForm extends AppModel
{
    public function signup(): ?User
    {
        if ($transaction = Yii::$app->db->beginTransaction()) {
            try {
                $user = UserHelper::createNewUserByUid();
                UserHelper::createUserExt($user, Boolean::Yes);
                $transaction->commit();
                return $user;
            } catch (Exception) {
                $transaction->rollBack();
            }
        }
        return null;
    }
}
