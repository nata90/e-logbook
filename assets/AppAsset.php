<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/handsontable.min.css',
        'css/jquery.toast.min.css',
        'css/daterangepicker.css',
        'css/ionicons/css/ionicons.min.css',
    ];
    public $js = [
        'js/jquery-sortable.js',
        'js/handsontable.full.min.js',
        'js/jquery.toast.min.js',
        'js/custom.js',
        'js/daterangepicker.js',
        'js/notif.js',
        'js/socket.io.js',
        'js/jquery.form.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
