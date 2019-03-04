<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-widgets
 * @subpackage yii2-widget-select2
 * @version 2.0.9
 */

namespace app\modules\asset\assets;

use yii\web\AssetBundle;


class AssetOrderAsset extends AssetBundle
{   
    public $sourcePath ="@app/modules/asset/assets/";
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\AngularAsset'
    ];
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->css = [            
            
        ];
        $this->js = [        
            
            'js/asset_app.js',
            
        ];
        
        parent::init();
    }
}