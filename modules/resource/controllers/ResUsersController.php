<?php

namespace app\modules\resource\controllers;

use Yii;
use app\modules\resource\models\ResUsers;
use app\modules\resource\models\ResUsersSearch;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * ResUsersController implements the CRUD actions for ResUsers model.
 */
class ResUsersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ResUsers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = "main_default";
        $searchModel = new ResUsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $count = ResUsers::find()->count();
        $uncount = ResUsers::find()
            ->where(['active' => null])
            ->count();
        $model = new ResUsers();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'count' => $count,
            'model' => $model,
            'uncount' => $uncount
        ]);
    }

    /**
     * Displays a single ResUsers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout = "main_default";
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ResUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = "main_default";
        $model = new ResUsers();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->img = $model->upload($model,'img');
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ResUsers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = "main_default";
        /*$model = $this->findModel($id);

        $isLoaded = $model->load(Yii::$app->request->post());
        if ($isLoaded) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile) {
                $result = $model->upload();
                if ($result) {
                    Yii::$app->session->setFlash('success', 'upload success ' . $model->imageFile->name);
                    $model->img = $model->imageFile->name;
                    $model->imageFile = null;
                } else {
                    Yii::$app->session->setFlash('warning', 'upload error');
                }
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }*/
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->img = $model->upload($model,'img');
            $model->save();
            return $this->redirect(['index']);
        }  else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ResUsers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteResUsers($id) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $model = ResUsers::findOne(['id' => $id])->delete();

        
    }
    
    public function actionUserListJson(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $tempUser = [];
        $users = ResUsers::find()->alias('a')
            ->select('
                c.name as group_name,c.department,
                a.code,a.firstname,a.lastname,a.email,a.username,a.id,a.active,a.img,
                a.select_admin,a.select_help,a.select_asset,a.tel,
                a.rule_admin,a.rule_help,a.rule_asset
            ')
            ->leftJoin('res_users_group_rel as b','b.user_id = a.id')
            ->leftJoin('res_group as c','c.id = b.group_id')
            ->asArray()
            ->all();
        $tempUser = $users;

        $availableUser = [];
        foreach ($tempUser as $key => $value) {
            $temp['group_name'] = $value['group_name'];
            $temp['department'] = $value['department'];
            $temp['code'] = $value['code'];
            $temp['firstname'] = $value['firstname'];
            $temp['lastname'] = $value['lastname'];
            $temp['email'] = $value['email'];
            $temp['id'] = $value['id'];
            $temp['active'] = $value['active'];
            $temp['username'] = $value['username'];
            $temp['img'] = $value['img'];
            $temp['tel'] = $value['tel'];
            $temp['rule_asset']= $value['rule_asset'];
            $temp['rule_admin'] = $value['rule_admin'];
            $temp['rule_help'] = $value['rule_help'];
            $temp['select_admin'] = $value['select_admin'];
            $temp['select_help'] = $value['select_help'];
            $temp['select_asset'] = $value['select_asset'];
            array_push($availableUser, $temp);
        }

        return $availableUser;
    }
    
    /**
     * จัดการผู้ใช้ระบบ
     * @return type
     */
    public function actionManage(){
        $this->layout = "main_default";
        return $this->render('manage');
    }
    
    public function actionResUserListJson($q=null){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = ResUsers::find();
        if($q){
            $query->where(['like','username',$q]);
        }
                $users = $query->all();
        return $users;
    }
    
    public function actionCenterUserListJson($q=null){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $res_ids = ResUsers::find()->select('id')->all();
        $query = \app\models\User::find();
        if($q){
            $query->where(['like','username',$q]);
            
        }
        $query->andWhere(['not in','id',$res_ids]);
                $users = $query->all();
        return $users;
    }
    
    public function actionAddToResUser($id){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = \app\models\User::findOne(['id'=>$id]);
        $resUser = new ResUsers();
        $resUser->attributes = $model->attributes;
        $resUser->id = $model->id;
        
        $result = $resUser->save();
        if($result){
            return ['status'=>'success','message'=>'เรียบร้อย'];
        } else {
            return ['status'=>'fail','message'=>$resUser->getErrors()];
        }
    }

    public function actionLoadFormJson(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = ResUsers::find()->all();
        return $model;
    }

    public function actionViewTabJson($id){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = new \yii\db\Query();
            $query->select('
                b.name,
                b.department
            ')
            ->from('res_users_group_rel as a')
            ->leftJoin('res_group as b','b.id = a.group_id')
            ->where(['a.user_id' => $id]);

        $row = $query->all();
        return $row;
    }

    public function actionEditResUsers() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$post = Yii::$app->request->rawBody;
        //$data = Json::decode($post);
        //$model = $data['model'];
        $input_id = @$_POST['id'];
        $transection = ResUsers::getDb()->beginTransaction();
        try {

            $user = ResUsers::findOne(['id' => $input_id]);
            $user->code = ($_POST['code'])?$_POST['code']:null;
            $user->username = @$_POST['username'];
            $user->firstname = @$_POST['firstname'];
            $user->lastname = @$_POST['lastname'];
            $user->tel = @$_POST['tel'];
            $user->email = @$_POST['email'];
            $user->active = @$_POST['active'];
            $user->select_admin = @$_POST['select_admin'];
            $user->rule_admin = @$_POST['rule_admin'];
            $user->select_help = @$_POST['select_help'];
            $user->rule_help = @$_POST['rule_help'];
            $user->select_asset = @$_POST['select_asset'];
            $user->rule_asset = @$_POST['rule_asset'];
            if(@$_FILES['file']['name'] != "") {
                $target_dir = "../web/img_com/";
                $target_file = $target_dir . basename(@$_FILES['file']['name']);
                $file = basename(@$_FILES['file']['name']);
                move_uploaded_file(@$_FILES['file']['tmp_name'], $target_file);
                $user->img = $file;
            }

            $user->save(false);

            $transection->commit();
        } catch (\Exception $e) {
            $transection->rollBack();
            throw $e;
        }

        return $user;

    }
    

    /**
     * Finds the ResUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ResUsers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ResUsers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}