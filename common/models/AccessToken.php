<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Токен доступа (bearer).
 *
 * @property integer $id
 * @property string $user_id
 * @property string $value
 *
 * @property-read User $user
 */
class AccessToken extends ActiveRecord
{

    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'access_token';
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}