<?php

namespace app\modules\asset;

use app\modules\asset\models\AssetOrder;
use yii;
/**
 * resource module definition class
 */
class Asset extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\asset\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * Deprecated use AssetOrder::states() instead.
     * @return array
     */
    public static function asState(){
        $array = [
            ['id'=>  AssetOrder::WAIT,'name'=>'รออนุมัติ'],
            ['id'=>  AssetOrder::APPROVED,'name'=>'อนุมัติเอกสาร'],
            ['id'=>  AssetOrder::CANCEL,'name'=>'ยกเลิกเอกสาร'],
           
        ];
        return $array;
    }
}