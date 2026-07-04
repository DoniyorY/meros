<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // uncomment if you want to cache RBAC items hierarchy
            // 'cache' => 'cache',
        ],
        'formatter'=>[
            'thousandSeparator' => ' ',
        ],
       'telegramBot' => [
          'class' => \common\components\TelegramBot::class,
          //'token' => '',
          'timeout' => 10,
       ],
       'telegramStaffBot' => [
          'class' => \common\components\TelegramBot::class,
          //'token' => $params['telegramStaffBotToken'],
          'timeout' => 10,
       ],
        'playmobile' => [
            'class' => \rakhmatov\playmobile\components\Connection::class,
            'username' => 'here playmobile login',
            'password' => 'here playmobile password',
        ],
    ],
];
