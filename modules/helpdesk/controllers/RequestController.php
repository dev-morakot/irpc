<?php

namespace app\modules\helpdesk\controllers;

use Yii;
use yii\web\Controller;
use app\components\DocSequenceHelper;
use app\components\SimpleStringHelper;
use app\components\DateHelper;
use app\modules\admin\models\AppUserlog;
use app\modules\resource\models\ResDocMessage;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use \yii\web\Response;
use yii\helpers\Html;
use yii\db\Query;
use yii\db\Expression;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\modules\helpdesk\models\Request;
use app\modules\helpdesk\models\RequestSearch;
use app\modules\helpdesk\models\RequestQuery;
use app\modules\helpdesk\HelpDesk;
/**
 * Default controller for the `resource` module
 */
class RequestController extends Controller
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
                    'delete' => ['POST','GET'],
                ],
            ],
        ];
    }


    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
    	$this->layout = "main_default";
        $searchModel = new RequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $states = ArrayHelper::map(HelpDesk::reState(), 'id', 'name');
        $countMyDoc = Request::find()->where(['requested_by' => Yii::$app->user->id])->count();
        $countAllDoc = Request::find()->count();
        $countRepair = Request::find()->where(['state' => Request::REPAIR])->count();
        $countClose = Request::find()->where(['state' => Request::CLOSE])->count();
        $countEnd = Request::find()->where(['state' => Request::ENDJOB])->count();
        $countClame = Request::find()->where(['state' => Request::CLAME])->count();
        $countBuy = Request::find()->where(['state' => Request::BUY])->count();
        $countCancel = Request::find()->where(['state' => Request::CANCEL])->count();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'states' => $states,
            'countMyDoc' => $countMyDoc,
            'countAllDoc' => $countAllDoc,
            'countRepair' => $countRepair,
            'countClose' => $countClose,
            'countEnd' => $countEnd,
            'countClame' => $countClame,
            'countCancel' => $countCancel,
            'countBuy' => $countBuy
        ]);
    }

    public function actionCreate(){
    	$this->layout = "main_default";
    	return $this->render("create");
    }

    public function actionUpdate($id){
        $this->layout = 'main_default';
        $model = $this->findModel($id);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing PurchaseOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->doDelete();
        return $this->redirect(['index']);
    }


    /**
     * Displays a single Request model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id){
        $this->layout = "main_default";
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if($request->isAjax) {
            return $this->renderAjax('view', [
                'model' =>$model,
            ]);
        } else {
            return $this->render('view', [
                'model' =>$model,
            ]);
        }
    }

    public function actionPrint($id) {
        $this->layout = "main_default";
        $model = $this->findModel($id);
        return $this->render('print', [
            'model' => $model
        ]);
    }

    /**
     * for Anuglar
     */
    public function actionLoadFormAjax(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $states = HelpDesk::reState();
        return ['states' => $states];
    }

    public function actionLoadModelFormJson($id) {
        $model = Request::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['model' =>$model];
    }

    public function actionSaveRequest(){
        $postdata = Yii::$app->request->rawBody;
        $data = Json::decode($postdata);
        $input_id = $data['model']['id'];
        $model = $data['model'];
        $model['state'] = Request::WAIT;
        $model['sn_number'] = $model['sn_number'];
        $model['description'] = $model['description'];
        $model['problem'] = isset($model['problem'])?$model['problem']:null;
        $model['brand'] = $model['brand'];
        $model['other'] = isset($model['other'])?$model['other']:null;
        $model['date_create'] = new Expression("NOW()");
        $model['officer'] = isset($model['officer'])?$model['officer']:null;
        $model['building'] = isset($model['building'])?$model['building']:null;
        $transaction = Request::getDb()->beginTransaction();
        try {
        
        Yii::info("array=>".\yii\helpers\VarDumper::dumpAsString($data));
            if($input_id == -1) {
                // insert
                // load json data into model
                $requests = new Request();
                $requests->attributes = $model;
                $requests->name = DocSequenceHelper::genDocNo(DocSequenceHelper::IT_DOC_NO);
                $requests->requested_by = Yii::$app->user->identity->id;
                $requests->save(false);
                
                // Logger
                Yii::$app->userlog->info(
                    'สร้างฟอร์ม เพื่อแจ้งซ่อม id ='. $requests->id. ', name=' . $requests->name. ', อาการ,สาเหตุ='. $requests->description
                    , Request::tableName(), 'create');

                    Yii::$app->docmsg->msg($requests, 'สร้าง');
            } else {
                // update
                $requests = Request::findOne(['id' =>$input_id]);
                $requests->attributes = $model;
                $requests->save();

                // Logger
                Yii::$app->userlog->info(
                    'แก้ไข IT id=' . $requests->id. ', name=' . $requests->name. ', อาการ,สาเหตุ ='. $requests->description
                , Request::tableName(), 'update');

                Yii::$app->docmsg->msg($requests, 'ปรับปรุง, หมายเลขคุรภัณฑ์ ='. $requests->sn_number . ', อาการ/สาเหตุ ='. $requests->description. ', รุ่น/ยี่ห้อ ='. $requests->brand. ', แจ้งปัญหา ='. $requests->problem);
            }

            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $request = Request::find()
            ->where(['id' => $requests->id])
            ->one();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['request' => $request];
    }
    
    public function actionUsedRepairListJson(){
        $model = \app\modules\resource\models\ResUsers::find()
            ->where(['rule_help' => 'it'])
            ->asArray()->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $model;
    }

    public function actionUsedBuilderListJson(){
        $model = \app\modules\resource\models\ResUsers::find()
            ->where(['rule_help' => 'builder'])
            ->asArray()->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $model;
    }

    public function actionUsedOfficerListJson(){
        $model = \app\modules\resource\models\ResUsers::find()
            ->where(['rule_help' => 'officer'])
            ->asArray()->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $model;
    }

    public function actionSetWait($id) {
        $transaction = Request::getDb()->beginTransaction();
        try {
            $model = $this->findModel($id);
            $model->state = Request::WAIT;
            $model->date_cancel = null;
            $model->note_cancel = null;
            $model->repair_id = null;
            $model->date_repair =null;
            $model->cancel_id = null;
            $model->save(false);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $this->actionView($id);
    }

    public function actionSaveStateRepair(){
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $input_id = $data['model']['id'];
        $model = $data['model'];
        $model['builder1_id'] = isset($model['builder1_id'])?$model['builder1_id']:null;
        $model['builder2_id'] = isset($model['builder2_id'])?$model['builder2_id']:null;
        $model['builder3_id'] = isset($model['builder3_id'])?$model['builder3_id']:null;
        $model['builder4_id'] = isset($model['builder4_id'])?$model['builder4_id']:null;
        $model['officer_id'] = isset($model['officer_id'])?$model['officer_id']:null;
        $model['repair_id'] = isset($model['repair_id'])?$model['repair_id']:null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $request = Request::findOne(['id' => $input_id]);
            $prev_state = $request->state;
            $request->state = Request::REPAIR;
            $request->attributes = $model;
            $request->date_repair = \app\components\DateHelper::nowStringDB();
            $request->save(false);

            Yii::$app->userlog->info(
                    'IT id=' . $request->id . ', name=' . $request->name . ', state [' . $prev_state . ' -> ' . $request->state . ']'
                    , Request::tableName(), 'update');
            
            Yii::$app->docmsg->msg($request,'Change status [' . $prev_state . ' -> ' . $request->state . ']');

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $request->id;

    }

    public function actionSaveAs(){
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $input_id = $data['model']['id'];
        $model = $data['model'];
        $transaction = Request::getDb()->beginTransaction();
        try {
            
            if($model['state'] == Request::CANCEL) {
                $request = Request::findOne(['id' => $input_id]);
                $prev_state = $request->state;
                $request->date_cancel = \app\components\DateHelper::nowStringDB();
                $request->note_cancel = $model['note_cancel'];
                $request->state = Request::CANCEL;
                $request->cancel_id = Yii::$app->user->identity->id;
                $request->save(false);

                Yii::$app->userlog->info(
                    'IT id=' . $request->id . ', name=' . $request->name . ', state [' . $prev_state . ' -> ' . $request->state . ']'
                    , Request::tableName(), 'update');
            
                Yii::$app->docmsg->msg($request,'Change status [' . $prev_state . ' -> ' . $request->state . ']');
            }
            if($model['state'] == Request::CLOSE) {
                $request = Request::findOne(['id' => $input_id]);
                $prev_state = $request->state;
                $request->date_close = \app\components\DateHelper::nowStringDB();
                $request->answer = $model['answer'];
                $request->detail_work = $model['detail_work'];
                $request->detail_officer = $model['detail_officer'];
                $request->detail_building = $model['detail_building'];
                $request->state = Request::CLOSE;
                $request->close_id = Yii::$app->user->identity->id;
                $request->save(false);

                Yii::$app->userlog->info(
                    'IT id=' . $request->id. ' ,name=' . $request->name . ', state [' . $prev_state .  ' ->' . $request->state .']'
                    ,Request::tableName(), 'update');
                Yii::$app->docmsg->msg($request, 'Change status [' . $prev_state . '->' . $request->state . ']');
            }

            if($model['state'] == Request::CLAME) {
                $request = Request::findOne(['id' => $input_id]);
                $prev_state = $request->state;
                $request->date_clame = \app\components\DateHelper::nowStringDB();
                $request->budget = $model['budget'];
                $request->state = Request::CLAME;
                $request->save(false);

                Yii::$app->userlog->info(
                    'IT id=' . $request->id. ' ,name=' . $request->name . ', state [' . $prev_state .  ' ->' . $request->state .']'
                    ,Request::tableName(), 'update');
                Yii::$app->docmsg->msg($request, 'Change status [' . $prev_state . '->' . $request->state . ']');
            }

            if($model['state'] == Request::BUY) {
                $request = Request::findOne(['id' => $input_id]);
                $prev_state = $request->state;
                $request->date_clame = \app\components\DateHelper::nowStringDB();
                $request->budget = $model['budget'];
                $request->state = Request::BUY;
                $request->save(false);

                Yii::$app->userlog->info(
                    'IT id=' . $request->id. ' ,name=' . $request->name . ', state [' . $prev_state .  ' ->' . $request->state .']'
                    ,Request::tableName(), 'update');
                Yii::$app->docmsg->msg($request, 'Change status [' . $prev_state . '->' . $request->state . ']');
            }



            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $request->id;

    }

    public function actionSaveClose(){
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $input_id = $data['model']['id'];
        $model = $data['model'];
        $transaction = Request::getDb()->beginTransaction();
        try {
                $request = Request::findOne(['id' => $input_id]);
                $prev_state = $request->state;
                $request->date_close = \app\components\DateHelper::nowStringDB();
                $request->answer = $model['answer'];
                $request->detail_work = $model['detail_work'];
                $request->state = Request::CLOSE;
                $request->close_id = Yii::$app->user->identity->id;
                $request->save(false);

                Yii::$app->userlog->info(
                    'IT id=' . $request->id. ' ,name=' . $request->name . ', state [' . $prev_state .  ' ->' . $request->state .']'
                    ,Request::tableName(), 'update');
                Yii::$app->docmsg->msg($request, 'Change status [' . $prev_state . '->' . $request->state . ']');

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return $request->id;
    }

    public function actionSaveCancel(){
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $input_id = $data['model']['id'];
        $model = $data['model'];
        $transaction = Request::getDb()->beginTransaction();
        try {
                $request = Request::findOne(['id' => $input_id]);
                $prev_state = $request->state;
                $request->date_cancel = \app\components\DateHelper::nowStringDB();
                $request->note_cancel = $model['note_cancel'];
                $request->state = Request::CANCEL;
                $request->cancel_id = Yii::$app->user->identity->id;
                $request->save(false);

                Yii::$app->userlog->info(
                    'IT id=' . $request->id . ', name=' . $request->name . ', state [' . $prev_state . ' -> ' . $request->state . ']'
                    , Request::tableName(), 'update');
            
                Yii::$app->docmsg->msg($request,'Change status [' . $prev_state . ' -> ' . $request->state . ']');

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return $request->id;
    }

    public function actionSaveComment(){
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $input_id = $data['model']['id'];
        $model = $data['model'];
        $transaction = Request::getDb()->beginTransaction();
        try {   

            $request = $this->findModel($input_id);
            $prev_state = $request->state;
            $request->comment_state = $model['comment_state'];
            $request->comment_detail = $model['comment_detail'];
            $request->date_comment = \app\components\DateHelper::nowStringDB();
            $request->state = Request::ENDJOB;
            $request->save(false);

            Yii::$app->userlog->info(
                    'IT id=' . $request->id. ' ,name=' . $request->name . ', state [' . $prev_state .  ' ->' . $request->state .']'
                    ,Request::tableName(), 'update');
                Yii::$app->docmsg->msg($request, 'Change status [' . $prev_state . '->' . $request->state . ']');

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return $request->id;
    }



    /**
     * Finds the PurchaseOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PurchaseOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Request::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}