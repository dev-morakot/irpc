<?php

namespace app\modules\helpdesk;

use app\modules\helpdesk\models\Request;
use Yii;

/**
 * resource module definition class
 */
class HelpDesk extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\helpdesk\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * Deprecated use Request::states() instead.
     * @return array
     */
    public static function reState(){
        $array = [
            ['id' => Request::WAIT, 'name' => 'รอรับซ่อม'],
            ['id' => Request::REPAIR, 'name' => 'รับซ่อมแล้ว'],
            ['id' => Request::CLOSE, 'name' =>'รอจบงานซ่อม'],
            ['id' => Request::ENDJOB, 'name' =>'จบงานซ่อม'],
            ['id' => Request::CLAME , 'name' => 'ส่งเคลม'],
            ['id' => Request::BUY,'name' =>'จัดซื้ออุปกรณ์'],
            ['id'=> Request::CANCEL ,'name' =>'ยกเลิก']
        ];

        return $array;
    }
}