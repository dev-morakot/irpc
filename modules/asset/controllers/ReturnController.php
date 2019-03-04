<?php

namespace app\modules\asset\controllers;

use Yii;
use app\modules\asset\models\Asset;
use app\modules\asset\models\AssetQuery;
use app\modules\asset\models\AssetSearch;
use app\modules\asset\models\AssetUnit;
use app\modules\asset\models\AssetCategories;
use app\modules\asset\models\AssetLocation;
use app\modules\asset\models\ReturnOrder;
use app\modules\asset\models\ReturnOrderLine;
use app\modules\asset\models\ReturnOrderSearch;
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

class ReturnController extends \yii\web\Controller
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

    /**
     * for Angular Js
     */
    public function actionReturn(){
        $this->layout = "main_default";
        return $this->render('return');
    }

    public function actionIndex(){
        $this->layout = "main_default";
        $searchModel = new ReturnOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $countAllDoc = ReturnOrder::find()->count();
        $countCancel = ReturnOrder::find()->where(['state' => ReturnOrder::CANCEL])->count();
        $countWait = ReturnOrder::find()->where(['state' => ReturnOrder::WAIT])->count();
        $countApprover =  ReturnOrder::find()->where(['state' => ReturnOrder::APPROVED])->count();
        $countMyDoc = ReturnOrder::find()->where(['create_id' => Yii::$app->user->id])->count();
        return $this->render('index', [
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
     * Deletes an existing Asset model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionApproved($id) {
    	$model = $this->findModel($id);
    	$model->approve();
    	return $this->actionView($id);
    }

    public function actionCancel($id) {
    	$model = $this->findModel($id);
    	$model->cancel();
    	return $this->actionView($id);
    }
 

    /**
     * Displays a single Return model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $this->layout = "main_default";
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $detailDataProvider = new \yii\data\ActiveDataProvider([
            'query' => $model->getReturnOrderLines()
        ]);
        if($request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model,
                'detailDataProvider' => $detailDataProvider
            ]);
        } else {
            return $this->render('view', [
                'model' => $model,
                'detailDataProvider' => $detailDataProvider
            ]);
        }
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
        $cart = $data['return_cart'];
        $transaction = ReturnOrder::getDb()->beginTransaction();
        try {
            $order = new ReturnOrder();
            $order->name = DocSequenceHelper::genDocNo(DocSequenceHelper::RE_DOC_NO);
            $order->attributes = $model;
            $order->state = ReturnOrder::WAIT;
            $order->create_id = Yii::$app->user->id;
            if($order->save(false)) {
                foreach($cart as $line) {
                    $modline = new ReturnOrderLine();
                    $modline->order_id = $order->id;
                    $modline->asset_id = $line['id'];
                    $modline->qty = $line['new_qty'];
                    $modline->save(false);
                }
            }

            // Logger
                Yii::$app->userlog->info(
                    'สร้างฟอร์ม คืนพัสดุ-ครุภัณฑ์ id ='. $order->id. ', เลขเอกสาร='. $order->name  .', ชื่อผู้ขอคืน=' . $order->full_name. ', ฝ่าย/แผนก='. $order->group->name
                    , ReturnOrder::tableName(), 'create');

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
        if (($model = ReturnOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
