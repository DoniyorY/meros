<?php

use common\services\UserLoginSessionService;
use yii\web\UserEvent;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Tashkent',
   'modules' => [
      'gridview' => ['class' => 'kartik\grid\Module']
   ],
    'name'=>'MEROS Admin Panel',
   
    'components' => [
       'assetManager' => [
          'appendTimestamp' => true,
       ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/admin',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
           'on afterLogin' => static function (UserEvent $event): void {
              UserLoginSessionService::start(
                 (int) $event->identity->getId()
              );
           },
           
           'on beforeLogout' => static function (): void {
              UserLoginSessionService::finishCurrent();
           },
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'as access' => [
        'class' => yii\filters\AccessControl::class,
        'rules' => [
            [
                'allow' => true,
                'actions' => ['login'], // Доступ разрешен только к странице login
                'roles' => ['?'],       // Только для гостей
            ],
            [
                'allow' => true,
                'roles' => ['admin','tech_support'],       // Только для авторизованных пользователей
            ],
        ],
        'denyCallback' => function ($rule, $action) {

            return Yii::$app->response->redirect(['/site/login']);
        },
    ],
    'params' => $params,
];
