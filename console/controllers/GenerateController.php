<?php

namespace console\controllers;

use console\services\GenerateService;
use yii\console\Controller;

/**
 * Контроллер для генерирования записей.
 */
class GenerateController extends Controller
{

    /**
     * Создает пользователей.
     *
     * @param GenerateService $service
     */
    public function actionUsers(GenerateService $service)
    {
        $service->users();
    }

}