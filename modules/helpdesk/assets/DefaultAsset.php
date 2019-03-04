<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\helpdesk\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DefaultAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'template/css/bootstrap.css',
        'template/css/style.css',
        'template/css/style-responsive.css',
        'font-awesome/css/font-awesome.css',
        'css/site.css',
        //'js/DataTables-1.10.12/media/css/jquery.dataTables.css',
    ];
    public $js = [
        //'js/DataTables-1.10.12/media/js/jquery.dataTables.js',      
        /*'js/jquery.serialize-object/jquery.serialize-object.js',
        'js/masterdata-utils.js',
        'js/numeral-js/numeral.js',
        'js/numeral-js/languages/th.js',
        'js/bootbox/bootbox.min.js',
        'js/moment/moment.js',*/
       // 'template/js/jquery.js',
       'js/bootbox/bootbox.min.js',
        'template/js/bootstrap.min.js',
        'template/js/jquery.dcjqaccordion.2.7.js',
        'template/js/jquery.scrollTo.min.js',
        'template/js/jquery.nicescroll.js',
        'template/js/common-scripts.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
