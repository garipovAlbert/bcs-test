<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // Токен Доступа
                'POST   /v1/access-tokens' => '/v1/access-token/post',
                'GET    /v1/access-tokens' => '/v1/access-token/get-list',
                'GET    /v1/access-tokens/<id:\d+>' => '/v1/access-token/get',
                'DELETE /v1/access-tokens/<id:\d+>' => '/v1/access-token/delete',
            ],
        ],
    ],
    'modules' => [
        'v1' => \api\modules\v1\Module::class,
    ],
    'params' => $params,
];
