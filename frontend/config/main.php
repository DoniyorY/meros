<?php
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
    'language' => 'en',
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
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => ['En'=>'en', 'Ru'=>'ru', 'Uz'=>'uz'], // List all supported languages here
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
               ''=>'site/index',
               'about'=>'site/about',
               'contact'=>'site/contact',
               'verify-email/<token>-<rer>'=>'site/verify-email',
               '<category>/<slug>' => 'courses/index',
               'courses/get-plan/<id>'=> 'courses/get-plan',
               'guest-register' => 'courses/guest-register',
               'courses/test'=>'courses/test',
            ],
        ],

    ],
    'params' => $params,
];
