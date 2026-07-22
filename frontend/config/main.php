<?php

use common\services\UserLoginSessionService;
use frontend\components\CourseUrlRule;
use yii\log\FileTarget;
use yii\web\UserEvent;

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
      
      'log' => [
         'traceLevel' => YII_DEBUG ? 3 : 0,
         
         // Сбрасывать сообщения чаще в диспетчер логов
         'flushInterval' => 1,
         
         'targets' => [
            /*
             * Обычный лог приложения.
             * Если он у тебя уже есть — второй раз не добавляй.
             */
            [
               'class' => FileTarget::class,
               'levels' => [
                  'error',
                  'warning',
               ],
               'logFile' => '@runtime/logs/app.log',
            ],
            
            /*
             * Отдельный лог Payme.
             */
            [
               'class' => FileTarget::class,
               
               'levels' => [
                  'error',
                  'warning',
                  'info',
               ],
               
               'categories' => [
                  'payme',
                  'payme-log',
               ],
               
               'logFile' => '@runtime/logs/payme.log',
               
               // Не записывать GET, POST, COOKIE, SESSION и SERVER
               'logVars' => [],
               
               // Записывать каждое сообщение сразу
               'exportInterval' => 1,
               
               // Размер одного файла примерно 10 MB
               'maxFileSize' => 10240,
               
               // Хранить до 10 старых файлов
               'maxLogFiles' => 10,
               
               'enableRotation' => true,
            ],
            // Отдельный лог для Zapier
            [
               'class' => \yii\log\FileTarget::class,
               'categories' => ['zapier'],
               'levels' => ['info', 'warning', 'error'],
               'logFile' => '@runtime/logs/zapier.log',
               'logVars' => [],
               'exportInterval' => 1,
               'maxFileSize' => 10240, // 10 MB
               'maxLogFiles' => 10,
            ],
            [
               'class' => 'yii\log\FileTarget',
               'levels' => ['info', 'warning', 'error'],
               'categories' => ['telegram'],
               'logFile' => '@runtime/logs/telegram.log',
               'logVars' => [],
            ],
            
            [
               'class' => \yii\log\FileTarget::class,
               'categories' => ['telegram-staff'],
               'logFile' => '@frontend/runtime/logs/telegram-staff.log',
               'logVars' => [],
            ],
            [
               'class' => \yii\log\FileTarget::class,
               'categories' => ['playmobile'],
               'logFile' => '@frontend/runtime/logs/playmobile.log',
               'logVars' => [],
               'maxFileSize' => 10240, // 10 MB
               'maxLogFiles' => 3,
            ]
         ],
      ],
      'assetManager' => [
         'appendTimestamp' => true,
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
         // this is the name of the session cookie used for login on the frontend
         'name' => 'advanced-frontend',
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
            'team' => 'site/teams',
            'download/<id>-<file>'=>'courses/download',
            'faq/<page>'=>'site/faq',
            
            'POST telegram/staff-webhook' => 'telegram-staff/webhook',
            'GET telegram/staff-health' => 'telegram-staff/health',
            
            'POST telegram/webhook' => 'telegram/webhook',
            'GET telegram/health' => 'telegram/health',
            
            'POST payme/webhook' => 'payme/webhook',
            'payment/click-return/<id:\d+>/<payment_status>/<payment_id>' => 'payment/click-return',
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
