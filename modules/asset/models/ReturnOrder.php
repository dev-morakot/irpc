<?php

namespace app\modules\asset\models;

use Yii;
use yii\db\Expression;
use yii\helpers\VarDumper;
use app\components\DateHelper;
use DateTime;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use app\modules\resource\models\ResUsers;
use app\modules\resource\models\ResGroup;
use app\modules\asset\Asset;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "return_order".
 *
 * @property integer $id
 * @property string $date_order
 * @property string $date_approve
 * @property string $date_cancel
 * @property string $state
 * @property string $name
 * @property string $full_name
 * @property integer $group_id
 * @property string $notes
 * @property integer $location_id
 * @property integer $approver_id
 * @property integer $cancel_id
 * @property integer $create_id
 * @property integer $create_uid
 * @property string $create_date
 * @property integer $write_uid
 * @property string $write_date
 */
class ReturnOrder extends \yii\db\ActiveRecord
{

    const WAIT = "wait";
    const APPROVED = "approved";
    const DONE = "done";
    const CANCEL = "cancel";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'return_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_order', 'date_approve', 'date_cancel', 'create_date', 'write_date'], 'safe'],
            [['group_id', 'location_id', 'approver_id', 'cancel_id', 'create_id', 'create_uid', 'write_uid'], 'integer'],
            [['notes'], 'string'],
            [['state'], 'string', 'max' => 50],
            [['name','full_name'], 'string', 'max' => 255],
            [['state'],'in','range'=>  ArrayHelper::getColumn(Asset::asState(), 'id')],// allow only key in poState
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date_order' => Yii::t('app', 'วันที่สร้าง'),
            'date_approve' => Yii::t('app', 'วันที่อนุมัติ'),
            'date_cancel' => Yii::t('app', 'วันที่ยกเลิก'),
            'state' => Yii::t('app', 'สถานะ[wait,approve,cancel]'),
            'name' => Yii::t('app', 'เลขเอกสาร Doc'),
            'full_name' => Yii::t('app','ชื่อผู้ขอคืน'),
            'group_id' => Yii::t('app', 'ฝ่าย/แผนก'),
            'notes' => Yii::t('app', 'หมายเหตุ'),
            'location_id' => Yii::t('app', 'สถานที่'),
            'approver_id' => Yii::t('app', 'ผู้อนุมัติ'),
            'cancel_id' => Yii::t('app', 'ผู้ยกเลิก'),
            'create_id' => Yii::t('app', 'ผู้ทำรายการ'),
            'create_uid' => Yii::t('app', 'Create Uid'),
            'create_date' => Yii::t('app', 'Create Date'),
            'write_uid' => Yii::t('app', 'Write Uid'),
            'write_date' => Yii::t('app', 'Write Date'),
        ];
    }

     public function beforeSave($isInsert) {
        
        if($isInsert){
            $this->create_uid = Yii::$app->user->id;
            $this->create_date = new Expression("NOW()");
            $this->write_uid = Yii::$app->user->id;
            $this->write_date = new Expression("NOW()");
        } else {
            $this->write_uid = Yii::$app->user->id;
            $this->write_date = new Expression("NOW()");
        }
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturnOrderLines() {
        return $this->hasMany(ReturnOrderLine::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup() {
        return $this->hasOne(ResGroup::className(), ['id' => 'group_id']);
    }

    public function getLocation(){
        return $this->hasOne(AssetLocation::className(), ['id' => 'location_id']);
    }

    public function getApprove(){
        return $this->hasOne(ResUsers::className(), ['id' => 'approver_id']);
    }

    public function getCancel(){
        return $this->hasOne(ResUsers::className(), ['id' => 'cancel_id']);
    }

    public function getCreate(){
        return $this->hasOne(ResUsers::className(), ['id' => 'create_id']);
    }

    /**
     * อนุมัติคืน
     * @throws \Exception
     */
    public function approve(){
        $model = $this;
        $tx = ReturnOrder::getDb()->beginTransaction();
        try {

            $prev_state = $model->state;
            $model->approver_id = Yii::$app->user->id;
            $model->state = ReturnOrder::APPROVED;
            $model->date_approve = DateHelper::nowStringDB();
            if($model->save(false)) {
                $asLineList = ReturnOrderLine::find()->where(['order_id' => $model->id])->all();
                $amount = 0;
                foreach($asLineList as $line) {
                    $asset_id = $line['asset_id'];
                    $new_qty = $line['qty'];
                    $asset = \app\modules\asset\models\Asset::findOne(['id' => $asset_id]);
                    $amount = ($asset->qty + $new_qty);
                    $asset->qty = $amount;
                    $asset->save(false);
                }

                Yii::$app->session->setFlash('success','อนุมัติ' . $model->name. 'เรียบร้อย');
                Yii::$app->userlog->info(
                    'คืนพัสดุ-ครุภัณฑ์ id=' . $model->id . ', name=' . $model->name. ', state [' . $prev_state . '->' . $model->state . ']'
                ,ReturnOrder::tableName(), 'update');
                Yii::$app->docmsg->msg($model, 'Change status [' . $prev_state . '->'  . $model->state . ']');
            }

            $tx->commit();
        } catch (\Exception $e) {
            $tx->rollBack();
            throw $e;
        }
    }

    /**
     * ยกเลิกเอกสาร
     * @throws \Exception
     */
    public function cancel() {
        $model = $this;
        $tx = ReturnOrder::getDb()->beginTransaction();
        try {
            $prev_state = $model->state;
            $model->cancel_id = Yii::$app->user->id;
            $model->state = ReturnOrder::CANCEL;
            $model->date_cancel = DateHelper::nowStringDB();
            if($model->save(false)) {
                Yii::$app->session->setFlash('success','ไม่อนุมัติ' . $model->name);
                Yii::$app->userlog->info(
                    'คืนพัสดุ-ครุภัณฑ์ id=' . $model->id . ', name=' . $model->name . ' , state [' . $prev_state . '->' . $model->state . ']'
                ,ReturnOrder::tableName(), 'update');
                Yii::$app->docmsg->msg($model, 'Change status [' . $prev_state .  '->' . $model->state . ']');
            }

            $tx->commit();
        } catch (\Exception $e) {
            $tx->rollBack();
            throw $e;
        }
    }

    public static function getTotal($provider , $fieldName) {
    	$total = 0;
    	foreach($provider as $item) {
    		$total += $item[$fieldName];
    	}
    	return "<div style='text-align: right'>" . number_format($total) . "</div>";
    }

    /**
     * @inheritdoc
     * @return AssQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReturnOrderQuery(get_called_class());
    }
}
