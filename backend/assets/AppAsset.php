<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
       //'css/site.css',
       'css/bootstrap.min.css',
       'css/icons.min.css',
       'css/app.min.css',
       'css/custom.min.css',
    ];
    public $js = [
        'js/main.js',
       'libs/bootstrap/js/bootstrap.bundle.min.js',
       'libs/simplebar/simplebar.min.js',
       'libs/node-waves/waves.min.js',
       'libs/feather-icons/feather.min.js',
       'js/pages/plugins/lord-icon-2.1.0.js',
       //'js/plugins.js',
       'js/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
