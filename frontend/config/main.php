<?php

use frontend\components\CourseUrlRule;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'name' => 'Meros International Institute',
   'language' => 'ru',
   'sourceLanguage' => 'en',
    'components' => [
        'assetManager' => [
            'bundles' => [
                'kartik\form\ActiveFormAsset' => [
                    'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
                ],
                
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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

        // Url Manager
       'urlManager' => [
          'class' => \codemix\localeurls\UrlManager::class,
          
          'languages' => [
             'ru',
             'en',
             'uz',
          ],
          
          // Чтобы даже язык по умолчанию всегда был в URL:
          // /ru/site/about, а не /site/about
          'enableDefaultLanguageUrlCode' => true,
          
          'enablePrettyUrl' => true,
          'showScriptName' => false,
          'enableStrictParsing' => false,
          
          'rules' => [
             /*
              * Системные страницы ставим выше,
              * чтобы не обращаться к базе без необходимости.
              */
             '' => 'site/index',
             'about' => 'site/about',
             'login' => 'site/login',
             'logout' => 'site/logout',
             'contact' => 'site/contact',
             'POST payme/webhook' => 'payme/webhook',
             'payment/click-return/<id:\d+>/' => 'payment/click-return',
             'POST payment/payme/<id:\d+>' => 'payment/payme',
             
             'GET payment/payme-result/<token:[A-Za-z0-9_-]+>'
             => 'payment/payme-result',
             /*
              * Events ставим выше динамических правил курсов,
              * чтобы /events и /events/123 не попадали в CourseUrlRule.
              */
             'events' => 'events/index',
             'events/<id:\d+>' => 'events/view',
             
             /*
              * Динамическая категория + динамический курс.
              *
              * ВАЖНО: lang здесь отсутствует.
              * Язык обработает codemix\localeurls\UrlManager.
              */
             [
                'class' => CourseUrlRule::class,
                'pattern' => '<category:[a-z0-9-]+>/<slug:[a-z0-9-]+>',
                'route' => 'courses/index',
             ],
             
             /*
              * Обычные Yii-маршруты должны идти после курса.
              *
              * Иначе /medical-english/english-for-doctors
              * будет воспринят как controller/action.
              */
             '<controller:[a-zA-Z0-9_-]+>/<action:[a-zA-Z0-9_-]+>'
             => '<controller>/<action>',
             
             '<controller:[a-zA-Z0-9_-]+>'
             => '<controller>/index',
             
             
          ],
       ],

    ],
    'params' => $params,
];
