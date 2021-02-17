<?php

namespace api\modules\v1\services;

use api\modules\v1\models\AccessTokenResource;
use common\models\AccessToken;
use common\models\User;
use yii\base\Component;
use yii\db\StaleObjectException;

/**
 * Сервис, реализующий CRUD для Токена Доступа.
 */
class AccessTokenCrudService extends Component
{

    /**
     * @param AccessToken $token
     * @return AccessTokenResource
     */
    public function get(AccessToken $token): AccessTokenResource
    {
        return (new AccessTokenResource)->mapAccessToken($token);
    }

    /**
     * Возвращает все Токены Доступа для указанного пользователя.
     *
     * @param User $user
     * @return AccessTokenResource[]
     */
    public function getList(User $user): array
    {
        $tokens = AccessToken::find()
            ->andWhere(['user_id' => $user->id])
            ->all();

        return array_map(
            fn(AccessToken $token) => (new AccessTokenResource)->mapAccessToken($token),
            $tokens
        );
    }

    /**
     * Создает Токен Доступа.
     *
     * @param AccessTokenResource $tokenResource
     * @return AccessToken
     * @throws \yii\base\Exception
     */
    public function create(AccessTokenResource $tokenResource): AccessToken
    {
        $token = new AccessToken;
        $token->value = \Yii::$app->security->generateRandomString(64);
        $token->user_id = User::findByUsername($tokenResource->username)->id;
        $token->save(false);

        return $token;
    }

    /**
     * Удаляет Токен Доступа.
     *
     * @param AccessToken $token
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function delete(AccessToken $token)
    {
        $token->delete();
    }

}