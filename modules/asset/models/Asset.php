<?php

namespace app\modules\asset\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "asset_asset".
 *
 * @property integer $id
 * @property integer $categories_id
 * @property string $certificate
 * @property string $name
 * @property string $description
 * @property string $notes
 * @property double $qty
 * @property integer $unit_id
 * @property integer $create_uid
 * @property string $create_date
 * @property integer $write_uid
 * @property string $write_date
 */
class Asset extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asset_asset';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categories_id', 'unit_id', 'create_uid', 'write_uid'], 'integer'],
            [['notes'], 'string','max' => 255],
            [['qty'], 'number'],
            [['create_date', 'write_date'], 'safe'],
            [['certificate', 'name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'categories_id' => Yii::t('app', 'ประเภทสินทรัพย์'),
            'certificate' => Yii::t('app', 'เลขที่ใบรับรอง'),
            'name' => Yii::t('app', 'หมายเลขครุภัณฑ์'),
            'description' => Yii::t('app', 'รายการ'),
            'notes' => Yii::t('app', 'หมายเหตุ'),
            'qty' => Yii::t('app', 'จำนวน'),
            'unit_id' => Yii::t('app', 'หน่วย'),
            'create_uid' => Yii::t('app', 'Create Uid'),
            'create_date' => Yii::t('app', 'Create Date'),
            'write_uid' => Yii::t('app', 'Write Uid'),
            'write_date' => Yii::t('app', 'Write Date'),
        ];
    }

    /**
     * @inheritdoc
     */
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

    public function getCategory(){
        return $this->hasOne(AssetCategories::className(),['id' => 'categories_id']);
    }

    public function getUnit(){
        return $this->hasOne(AssetUnit::className(),['id' => 'unit_id']);
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
     * @return AssetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AssetQuery(get_called_class());
    }
}
