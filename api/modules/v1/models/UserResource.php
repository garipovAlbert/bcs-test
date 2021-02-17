<?php

namespace api\modules\v1\models;

use common\models\User;
use yii\base\Model;
use yii\db\ActiveQuery;

/**
 * Ресурс Пользователь.
 */
class UserResource extends Model
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @var string
     */
    public string $id;

    /**
     * @var string|null
     */
    public ?string $username = null;


    /**
     * @var string|null
     */
    public ?string $email = null;

    /**
     * @var string|null
     */
    public ?string $password = null;

    /**
     * {@inheritDoc}
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['username', 'email', 'password'],
            self::SCENARIO_UPDATE => ['username', 'email', 'password'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 64],
            [
                'username', 'match',
                'pattern' => '/^[a-zA-Z0-9_-]+$/',
                'message' => 'Username can only contain alphanumeric characters, underscores and dashes.',
            ],
            [
                'username', 'unique',
                'targetClass' => User::class, 'targetAttribute' => 'username',
                'message' => 'This username has already been taken.',
                'filter' => function (ActiveQuery $query) {
                    // При редактировании исключить из запроса текущую запись
                    if (!empty($this->id)) {
                        $query->andWhere(['!=', 'id', $this->id]);
                    }
                },
            ],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email', 'unique',
                'targetClass' => User::class, 'targetAttribute' => 'email',
                'message' => 'This email address has already been taken.',
                'filter' => function (ActiveQuery $query) {
                    // При редактировании исключить из запроса текущую запись
                    if (!empty($this->id)) {
                        $query->andWhere(['!=', 'id', $this->id]);
                    }
                },
            ],

            ['password', 'required', 'on' => static::SCENARIO_CREATE],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields()
    {
        return [
            'id',
            'username',
            'email',
        ];
    }

    /**
     * @param User $user
     * @return UserResource
     */
    public function mapUser(User $user): self
    {
        $this->id = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;

        return $this;
    }

}