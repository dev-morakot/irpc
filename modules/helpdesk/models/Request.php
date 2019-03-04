<?php 

namespace app\modules\helpdesk\models;

use Yii;
use app\modules\resource\models\ResUsers;
use app\modules\helpdesk\HelpDesk;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use DateTime;
use app\components\DateHelper;
use yii\db\Expression;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "request".
 *
 * @property integer $id
 * @property string $name
 * @property string $state
 * @property string $origin
 * @property string $sn_number
 * @property string $brand
 * @property string $problem
 * @property string $other
 * @property string building
 * @property string officers
 * @property string $description
 * @property integer $requested_by
 * @property string $date_create
 * @property string $date_clame
 * @property string $date_repair
 * @property string $budget
 * @property integer $create_uid
 * @property string $create_date
 * @property integer $write_uids
 * @property string $write_date
 *
 */
class Request extends \yii\db\ActiveRecord {

    const WAIT = "wait";
    const REPAIR = "repair";
    const CLOSE = "close";
    const CLAME = "clame";
    const BUY = "buy";
    const CANCEL = "cancel";
    const ENDJOB = "endjob";

    public static function tableName(){
        return 'request';
    }

    public function rules(){

        return [
            [['name'],'required'],
            [['builder1_id', 'builder2_id', 'builder3_id', 'builder4_id', 'officer_id', 'repair_id', 'cancel_id', 'close_id', 'create_uid','write_uid'],'integer'],
            [['date_create','create_date','date_cancel', 'date_clame', 'date_comment', 'date_end_job', 'date_repair', 'write_date'],'safe'],
            [['name','origin','sn_number','brand', 'answer','detail_work','budget', 'comment_detail', 'note_cancel', 'problem','description','other'],'string','max' => 255],
            [['state','comment_state'],'string','max' => 30],
            [['officer','building','detail_building','detail_officer'],'string','max' => 255]
        ];
    }

    public function attributeLabels(){

        return [
            'id' => 'ID',
            'name' => 'เลขที่ใบแจ้งซ่อม',
            'origin' => 'เอกสารที่มา',
            'state' => 'สถานะ',
            'date_create' => 'วันที่บันทึก',
            'sn_number' => Yii::t('app','หมายเลขคุรภัณฑ์'),
            'brand' =>'รุ่น/ยี่ห้อ',
            'problem' => 'แจ้งปัญหา',
            'other' =>'อื่นๆ',
            'requested_by' => 'ผู้แจ้ง',
            'description' => 'ตรวจสอบเบื้องต้น (ระบุสาเหตุ/ปัญหาที่พบ)',
            'create_uid' => Yii::t('app', 'Create Uid'),
            'create_date' => Yii::t('app', 'Create Date'),
            'write_uid' => Yii::t('app', 'Write Uid'),
            'write_date' => Yii::t('app', 'Write Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser(){
        return $this->hasOne(ResUsers::className(), ['id' =>'requested_by']);
    }

    public function getRepair(){
        return $this->hasOne(ResUsers::className(), ['id' => 'repair_id']);
    }


    public function getClose(){
        return $this->hasOne(ResUsers::className(), ['id' => 'close_id']);
    }

    public function getCancel(){
        return $this->hasOne(ResUsers::className(), ['id' => 'cancel_id']);
    }

    public function getBuilder1(){
        return $this->hasOne(ResUsers::className(), ['id' => 'builder1_id']);
    }

    public function getBuilder2(){
        return $this->hasOne(ResUsers::className(), ['id' => 'builder2_id']);
    }

    public function getBuilder3(){
        return $this->hasOne(ResUsers::className(), ['id' => 'builder3_id']);
    }

    public function getBuilder4(){
        return $this->hasOne(ResUsers::className(), ['id' => 'builder4_id']);
    }

    public function getOfficers(){
        return $this->hasOne(ResUsers::className(), ['id' => 'officer_id']);
    }


    /**
     * Deletes an existing Request model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
     public function doDelete(){
         $model = $this;
         $transaction = Request::getDb()->beginTransaction();
         try {
            Yii::$app->userlog->info(
                'ลบเอกสารแจ้งซ่อม id=' . $model->id. ',name ='.$model->name . ', แจ้งปัญหา ='. $model->problem . ', รุ่น/ยี่ห้อ =' . $model->brand
                , Request::tableName(), 'delete');
            Yii::$app->docmsg->msg($model,'ลบ');

            $model->delete();
            $transaction->commit();
         } catch (\Exception $e) {
             $transaction->rollBack();
             throw $e;
         }
         return true;
     }

    /**
     * @inheritdoc
     * @return SaleOrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RequestQuery(get_called_class());
    }
}

?>