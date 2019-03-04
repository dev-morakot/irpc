<?php

namespace app\modules\resource\models;

use Yii;
use app\modules\resource\models\ResGroup;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * This is the model class for table "res_users".
 *
 * @property integer $id
 * @property string $code รหัสพนักงาน
 * @property string $username
 * @property string $firstname
 * @property string $lastname
 * @property string $firstname_th
 * @property string $lastname_th
 * @property string $email
 * @property string $img
 * @property integer $login_date
 * @property integer $active
 * @property integer $company_id
 * @property integer $default_section_id
 * @property string $img
 * @property string $tel
 * @property string $select_admin
 * @property string $rule_admin
 * @property string $select_help
 * @property string $rule_help
 * @property string $select_asset
 * @property string $rule_asset
 * @property integer $create_uid
 * @property string $create_date
 * @property integer $write_uid
 * @property string $write_date
 */
class ResUsers extends \yii\db\ActiveRecord
{

    public $imageFile;
    public $upload_foler ='img_com';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username','email'],'unique'],
            [['login_date', 'active', 'select_admin', 'select_help', 'select_asset', 'company_id', 'create_uid', 'write_uid'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['username','firstname','lastname','email'], 'string', 'max' => 255],
            [['img'],'string', 'max' => 255],
            [['code','tel', 'rule_admin','rule_help', 'rule_asset',],'string'],
            [['img'],'file','skipOnEmpty'=>true,'extensions'=>'png, jpg,JPG,PNG'],
        ];
    }
    
    public function fields() {
        $fields = [
            'id','firstname','lastname','email'
        ];
        
        return $fields;
        
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code'=>Yii::t('app','รหัสพนักงาน'),
            'name' => Yii::t('app', 'Name'),
            'login_date' => Yii::t('app', 'Login Date'),
            'active' => Yii::t('app', 'Active'),
            'company_id' => Yii::t('app', 'Company ID'),            
            
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
    
    public function getGroups()
    {
        return $this->hasMany(ResGroup::className(), ['id' => 'group_id'])
                ->viaTable('res_users_group_rel', ['user_id'=>'id']);
    }

    public function getTest(){
        return "morakot";
    }
    
    public function getGroupDisplay(){
        $groups = $this->getGroups()->asArray()->all();
        $group_names = array_column($groups, 'name');
        $str = implode(", ", $group_names);
        return $str;
    }

    public function getGroupDepartment(){
        $department = $this->getGroups()->asArray()->all();
        $department_names = array_column($department, 'department');
        $str = implode(", ", $department_names);
        return $str;
    }
    
    public static function currentUser(){
        return ResUsers::findOne(['id'=>Yii::$app->user->id]);
    }
    
    public static function currentUserGroups(){
        $user = ResUsers::find()
                ->where(['id'=>Yii::$app->user->id])
                ->one();
        return $user->groups;
    }
    
    public function getDisplayName(){
        return $this->firstname.' '.$this->lastname.' ('.$this->email.')';
    }
    
    public function getFullName(){
        return $this->firstname.' '.$this->lastname;
    }

    // Action
    public function upload($model,$attribute)
    {
        //$result = $this->imageFile->saveAs(Yii::getAlias('@img_com').'/'.$this->imageFile->baseName.'.'.$this->imageFile->extension);
        //return $result;

        $photo  = UploadedFile::getInstance($model, $attribute);
        $path = $this->getUploadPath();
        if ($this->validate() && $photo !== null) {

            $fileName = md5($photo->baseName.time()) . '.' . $photo->extension;
            //$fileName = $photo->baseName . '.' . $photo->extension;
            if($photo->saveAs($path.$fileName)){
                return $fileName;
            }
        }
        return $model->isNewRecord ? false : $model->getOldAttribute($attribute);
    }

    public function getUploadPath(){
        return Yii::getAlias('@webroot').'/'.$this->upload_foler.'/';
    }

    public function getUploadUrl(){
        return Yii::getAlias('@web').'/'.$this->upload_foler.'/';
    }

    public function getPhotoViewer(){
        return ($this->img) ? $this->getUploadUrl().$this->img : Yii::getAlias('@web').'/img_com/none.png';
    }
    
}
