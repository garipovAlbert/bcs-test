<?php

namespace api\modules\v1\services;

use api\modules\v1\models\UserResource;
use common\models\User;
use yii\base\Component;

/**
 * Сервис, реализующий CRUD для Пользователя.
 */
class UserCrudService extends Component
{

    /**
     * @param User $user
     * @return UserResource
     */
    public function get(User $user): UserResource
    {
        return (new UserResource)->mapUser($user);
    }

    /**
     * Возвращает всех активных Пользователей.
     *
     * @return UserResource[]
     */
    public function getList(): array
    {
        $users = User::find()
            ->andWhere(['status' => User::STATUS_ACTIVE])
            ->all();

        return array_map(
            fn(User $user) => (new UserResource)->mapUser($user),
            $users
        );
    }

    /**
     * Создает Пользователя.
     *
     * @param UserResource $userResource
     * @return User
     */
    public function create(UserResource $userResource): User
    {
        $user = new User;
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = User::STATUS_ACTIVE;

        $user->username = $userResource->username;
        $user->email = $userResource->email;
        $user->password = $userResource->password;

        $user->save(false);

        return $user;
    }

    /**
     * Обновляет Пользователя.
     *
     * @param User $user
     * @param UserResource $userResource
     */
    public function update(User $user, UserResource $userResource)
    {
        $user->username = $userResource->username;
        $user->email = $userResource->email;
        if (!empty($userResource->password)) {
            $user->password = $userResource->password;
        }

        $user->save(false);
    }

    /**
     * Удаляет Токен Доступа.
     *
     * @param User $user
     * @throws \Throwable
     */
    public function delete(User $user)
    {
        $user->status = User::STATUS_DELETED;
        $user->save(false);
    }

}