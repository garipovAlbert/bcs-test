<?php

namespace api\modules\v1;

use api\modules\v1\services\AccessTokenCrudService;

class Module extends \yii\base\Module
{

    /**
     * {@inheritDoc}
     */
    public $controllerNamespace = 'api\modules\v1\controllers';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();

        // Дефолтная конфигурация сервисов модуля.
        if (!$this->has(AccessTokenCrudService::class)) {
            $this->set(AccessTokenCrudService::class, AccessTokenCrudService::class);
        }
    }

}