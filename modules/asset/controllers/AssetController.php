<?php

namespace app\modules\asset\controllers;

use Yii;
use app\modules\asset\models\Asset;
use app\modules\asset\models\AssetQuery;
use app\modules\asset\models\AssetSearch;
use app\modules\asset\models\AssetUnit;
use app\modules\asset\models\AssetCategories;
use app\modules\asset\models\AssetLocation;
use app\modules\asset\models\AssetOrder;
use app\modules\asset\models\AssetOrderSearch;
use app\modules\asset\models\AssetOrderLine;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\DateHelper;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Json;
use yii\db\Expression;
use app\components\DocSequenceHelper;
use app\components\SimpleStringHelper;
use app\modules\admin\models\AppUserlog;
use app\modules\resource\models\ResDocMessage;
use yii\helpers\ArrayHelper;



class AssetController extends \yii\web\Controller
{
     /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST','GET'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = "main_default";
        $searchModel = new AssetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGridAsset(){
        $this->layout = "main_default";
        $searchModel = new AssetOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $countAllDoc = AssetOrder::find()->count();
        $countCancel = AssetOrder::find()->where(['state' => AssetOrder::CANCEL])->count();
        $countWait = AssetOrder::find()->where(['state' => AssetOrder::WAIT])->count();
        $countApprover =  AssetOrder::find()->where(['state' => AssetOrder::APPROVED])->count();
        $countMyDoc = AssetOrder::find()->where(['create_id' => Yii::$app->user->id])->count();
        return $this->render('grid-asset', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'countAllDoc' => $countAllDoc,
            'countCancel' => $countCancel,
            'countWait' => $countWait,
            'countApprover' => $countApprover,
            'countMyDoc' => $countMyDoc,
        ]);
    }

    /**
     * Displays a single Asset model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
         $this->layout = "main_default";
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

     /**
     * Creates a new Asset model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
         $this->layout = "main_default";
        $model = new Asset();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
                        
            ]);
        }
    }

     /**
     * Updates an existing Asset model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->layout = "main_default";
        $model = $this->findModel($id);
       
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        
            ]);
        }
    }

    public function actionPrint($id = null){

       
        $print = AssetOrder::findOne($id);
        $printList = AssetOrderLine::find()
            ->where(['order_id' => $id])
            ->all();
        return $this->render('print', [
            'print' => $print,
            'printList' => $printList,
            'n' => 1
        ]);

       
    }



    /**
     * Deletes an existing Asset model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteAsset($id) {
        AssetOrder::findOne(['id' => $id])->delete();
        return $this->redirect(['grid-asset']);
    }

    public function actionViewAsset($id) {
        $this->layout = "main_default";
        $request = Yii::$app->request;
        $model = AssetOrder::findOne(['id' => $id]);
        $detailDataProvider = new \yii\data\ActiveDataProvider([
            'query' => $model->getAssetOrderLines()
        ]);
        if ($request->isAjax) {
            return $this->renderAjax('view-asset', [
                        'model' => $model,
                        'detailDataProvider' => $detailDataProvider,
            ]);
        } else {
            return $this->render('view-asset', [
                        'model' => $model,
                        'detailDataProvider' => $detailDataProvider
            ]);
        }
    }

    public function actionApproved($id) {
        $ref = Yii::$app->request->referrer;
        $model = AssetOrder::findOne(['id' => $id]);
        $model->approve();
        return $this->actionViewAsset($id);

    }

    public function actionCancel($id) {
        $model = AssetOrder::findOne(['id' => $id]);
        $model->cancel();
        return $this->actionViewAsset($id);
    }

    /**
     * for Angular Js
     */
    public function actionAdd(){
        $this->layout = "main_default";
        return $this->render('add');
    }

    public function actionLoadFormJson(){
        \Yii::$app->response->format = \Yii\web\Response::FORMAT_JSON;
        $asset = Asset::find()
            ->with(['unit','category'])
            ->asArray()
            ->all();
        $category = AssetCategories::find()->all();
        $unit = AssetUnit::find()->all();
        return [
            'asset' => $asset,
            'category' => $category,
            'unit' => $unit
        ];
    }

    public function actionLocationListJson($q = null){
         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = \app\modules\asset\models\AssetLocation::find()
            ->where(['like','name', $q])
            ->all();
        return $query;
    }

    public function actionSaveCartListJson(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $model = $data['model'];
        $model['full_name'] = $model['full_name'];
        $model['group_id'] = isset($model['group_id'])?$model['group_id']['id']:null;
        $model['location_id'] = isset($model['location_id'])?$model['location_id']['id']:null;
        $model['notes'] = isset($model['notes'])?$model['notes']:null;
        $model['date_order'] = new Expression("NOW()");
        $cart = $data['cart'];
        $transaction = AssetOrder::getDb()->beginTransaction();
        try {
            $order = new AssetOrder();
            $order->name = DocSequenceHelper::genDocNo(DocSequenceHelper::AS_DOC_NO);
            $order->attributes = $model;
            $order->state = AssetOrder::WAIT;
            $order->create_id = Yii::$app->user->id;
            if($order->save(false)) {
                foreach($cart as $line) {
                    $modline = new AssetOrderLine();
                    $modline->order_id = $order->id;
                    $modline->asset_id = $line['id'];
                    $modline->qty = $line['new_qty'];
                    $modline->save(false);
                }
            }

            // Logger
                Yii::$app->userlog->info(
                    'สร้างฟอร์ม เบิกพัสดุ-ครุภัณฑ์ id ='. $order->id. ', เลขเอกสาร='. $order->name  .', ชื่อผู้ขอเบิก=' . $order->full_name. ', ฝ่าย/แผนก='. $order->group->name
                    , AssetOrder::tableName(), 'create');

                    Yii::$app->docmsg->msg($order, 'สร้าง');

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Finds the Asset model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Asset the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Asset::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
