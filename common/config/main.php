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
          'token' => '8731982584:AAHtK866lI8wKhq8cXqWu1DXCpc-3i0HYgw',
          'timeout' => 10,
       ],
    ],
];
