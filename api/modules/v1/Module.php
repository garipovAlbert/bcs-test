<?php

namespace api\modules\v1;

use api\modules\v1\services\AccessTokenCrudService;
use api\modules\v1\services\UserCrudService;
use yii\base\InvalidConfigException;

class Module extends \yii\base\Module
{

    /**
     * {@inheritDoc}
     */
    public $controllerNamespace = 'api\modules\v1\controllers';

    /**
     * {@inheritDoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        // Дефолтная конфигурация сервисов модуля.
        if (!$this->has(AccessTokenCrudService::class)) {
            $this->set(AccessTokenCrudService::class, AccessTokenCrudService::class);
        }
        if (!$this->has(UserCrudService::class)) {
            $this->set(UserCrudService::class, UserCrudService::class);
        }
    }

}