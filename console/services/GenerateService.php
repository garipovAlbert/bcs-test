<?php

namespace console\services;

use common\models\User;
use yii\base\Component;

/**
 * Генерирует записи.
 */
class GenerateService extends Component
{

    public function users()
    {
        $dataList = [
            [
                'id' => 1,
                'username' => 'patrick',
                'password' => '123456',
                'email' => 'patrick@mail.com',
            ],
            [
                'id' => 2,
                'username' => 'squidward',
                'password' => '123456',
                'email' => 'squidward@mail.com',
            ],
            [
                'id' => 3,
                'username' => 'krabs',
                'password' => '123456',
                'email' => 'krabs@mail.com',
            ],
        ];

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            foreach ($dataList as $data) {
                $user = new User($data);
                $user->generateAuthKey();
                $user->generateEmailVerificationToken();
                $user->save(false);
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

}