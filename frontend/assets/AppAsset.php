<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        'https://fonts.googleapis.com/css?family=Montserrat:400,700',
        'css/font-awesome.css',
        'css/selectize.css',
        'css/owl.carousel.css',
        'css/owl.theme.default.css',
        'css/vanillabox/vanillabox.css',
        'css/layerslider.css',
        'css/flexslider.css',
        'css/style.css',
        'css/bootstrap5-compat.css',
        //'css/mobile-header-fix.css',
    ];
    public $js = [
      //  'js/jquery-2.1.0.min.js',
        'js/jquery-migrate-1.2.1.min.js',
        'js/selectize.min.js',
        'js/owl.carousel.min.js',
        'js/jquery.validate.min.js',
        'js/jquery.placeholder.js',
        'js/jQuery.equalHeights.js',
        'js/icheck.min.js',
        'js/jquery.vanillabox-0.1.5.min.js',
        'js/jquery.tablesorter.min.js',
        //'js/greensock.js',
        //'js/layerslider.transitions.js',
        //'js/layerslider.kreaturamedia.jquery.js',
        'js/jquery.flexslider-min.js',
        'js/retina-1.1.0.min.js',
        'js/custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
    ];
}
