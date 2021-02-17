<?php

namespace api\modules\v1\models;

use common\models\AccessToken;
use common\models\User;
use yii\base\Model;

/**
 * Ресурс токена доступа (bearer).
 */
class AccessTokenResource extends Model
{

    /**
     * @var string|null Логин
     */
    public ?string $username = null;

    /**
     * @var string|null Пароль
     */
    public ?string $password = null;

    /**
     * @var string ID
     */
    public string $id;

    /**
     * @var string Токен доступа
     */
    public string $value;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findByUsername($this->username);

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, \Yii::t('app', 'Incorrect login or password.'));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function fields()
    {
        return [
            'id',
            'value',
        ];
    }

    /**
     * @param AccessToken $record
     * @return AccessTokenResource
     */
    public function mapAccessToken(AccessToken $record): self
    {
        $this->id = $record->id;
        $this->value = $record->value;

        return $this;
    }

}