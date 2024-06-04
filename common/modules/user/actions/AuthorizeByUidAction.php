<?php

namespace common\modules\user\actions;

use api\behaviors\returnStatusBehavior\JsonSuccess;
use api\behaviors\returnStatusBehavior\RequestFormData;
use common\modules\user\helpers\UserHelper;
use common\modules\user\models\User;
use OpenApi\Attributes as OA;
use Yii;
use yii\web\Response;

#[OA\Post(
    path: '/user/authorize-by-uid',
    operationId: 'login-by-uid',
    description: 'Авторизация по айди',
    summary: 'Авторизация',
    security: [['bearerAuth' => []]],
    tags: ['user']
)]
#[RequestFormData(
    properties: [
        new OA\Property(property: 'uid', description: 'UID (если есть)', type: 'string'),
    ]
)]
#[JsonSuccess(content: [new OA\Property(property: 'profile', ref: '#/components/schemas/Profile')])]
class AuthorizeByUidAction extends BaseAction
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
