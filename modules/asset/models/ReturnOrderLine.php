<?php

namespace app\modules\asset\models;

use Yii;
use yii\db\Expression;
use yii\helpers\VarDumper;
use app\components\DateHelper;
use DateTime;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "return_order_line".
 *
 * @property integer $id
 * @property integer $asset_id
 * @property integer $order_id
 * @property double $qty
 * @property integer $create_uid
 * @property string $create_date
 * @property integer $write_uid
 * @property string $write_date
 */
class ReturnOrderLine extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'return_order_line';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asset_id', 'order_id', 'create_uid', 'write_uid'], 'integer'],
            [['qty'], 'number'],
            [['create_date', 'write_date'], 'safe'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => AssetOrder::className(), 'targetAttribute' => ['order_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'asset_id' => Yii::t('app', 'ตาราง ทะเบียนครุภัฑณ์'),
            'order_id' => Yii::t('app', 'ตาราง asset order'),
            'qty' => Yii::t('app', 'จำนวนที่คืน'),
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
    public function getOrder()
    {
        return $this->hasOne(AssetOrder::className(), ['id' => 'order_id']);
    }

    public function getAsset(){
        return $this->hasOne(Asset::className(),['id' => 'asset_id']);
    }

    /**
     * @inheritdoc
     * @return ReturnOrderLineQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReturnOrderLineQuery(get_called_class());
    }
}
