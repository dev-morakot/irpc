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
use app\modules\asset\models\ReturnOrder;
use app\modules\asset\models\ReturnOrderSearch;
use app\modules\asset\models\ReturnOrderLine;
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



class ReportController extends \yii\web\Controller
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
     * สรุปครุภัณฑ์ทั้งหมด
     */
    public function actionReportAll() {
         $this->layout = "main_default";
        $searchModel = new AssetOrderSearch();
        $dataProvider = $searchModel->searchLine(Yii::$app->request->queryParams);
        
        $searchModelReturn = new AssetOrderSearch();
        $gridData = $searchModelReturn->searchFilter(Yii::$app->request->queryParams);
        return $this->render('report-all', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModelReturn' => $searchModelReturn,
            'gridData' => $gridData
        ]);
    }
    /**
     * รายงานครุภัณฑ์คงเหลือ
     */
    public function actionReportBalance(){
        $this->layout = "main_default";
        $searchModel = new AssetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('report-balance',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * สรุปตามประเภทอุปกรณ์
     */
    public function actionReportCategory(){
        $this->layout = "main_default";
        return $this->render('report-category');
    }

    /**
     * สรปตามสถานที่
     */
    public function actionReportLocation(){
        $this->layout = "main_default";
        return $this->render("report-location");
    }

    /**
     * เบิกจ่ายแยกประจำปี
     */
    public function actionReportAssetYear(){
        $this->layout = "main_default";
        return $this->render("report-asset-year");
    }

    /**
     * เบิกจ่ายแยกตามประจำเดือน
     */
    public function actionReportAssetMonth(){
        $this->layout = "main_default";
        return $this->render('report-asset-month');
    }

    /**
     * เบิกจ่ายแยกแผนก/ฝ่าย
     */
    public function actionReportAssetGroup(){
        $this->layout = "main_default";
        return $this->render('report-asset-group');
    }

    /**
     * คืนครุภัณฑ์สรุปรวมทั้งหมด
     */
    public function actionReportReturnAll() {
         $this->layout = "main_default";
        $searchModel = new ReturnOrderSearch();
        $dataProvider = $searchModel->searchLine(Yii::$app->request->queryParams);
        
        $searchModelReturn = new ReturnOrderSearch();
        $gridData = $searchModelReturn->searchFilter(Yii::$app->request->queryParams);
        return $this->render('report-return-all', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModelReturn' => $searchModelReturn,
            'gridData' => $gridData
        ]);
    }

    /**
     * จ่ายคืนแยกประจำปี
     */
    public function actionReportReturnYear(){
        $this->layout = "main_default";
        return $this->render("report-return-year");
    }

    /**
     * คืนจ่ายแยกตามประจำเดือน
     */
    public function actionReportReturnMonth(){
        $this->layout = "main_default";
        return $this->render('report-return-month');
    }

    /**
     * คืนจ่ายแยกแผนก/ฝ่าย
     */
    public function actionReportReturnGroup(){
        $this->layout = "main_default";
        return $this->render('report-return-group');
    }


    /**
     * For Angular
     */
    public function actionCategoriesListJson($q=null,$limit=20) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = \app\modules\asset\models\AssetCategories::find()
            ->where(['like','name', $q])
            ->limit($limit)
            ->all();
        return $query;
    }

    public function actionGroupListJson($q = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = \app\modules\resource\models\ResGroup::find()
            ->where(['like','department', $q])
            ->all();
        return $query;
    }

    public function actionLocationListJson($q=null,$limit=20) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = \app\modules\asset\models\AssetLocation::find()
            ->where(['like','name', $q])
            ->limit($limit)
            ->all();
        return $query;
    }

    public function actionReportCategoriesList(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $start = $data['date_start'];
        $end = $data['date_end'];
        $categ = $data['categ']['id'];

        $model = AssetOrderLine::find()->alias("a")
            ->leftJoin('asset_order b','b.id = a.order_id')
            ->leftJoin('asset_asset c','c.id = a.asset_id')
            ->leftJoin('asset_categories d','d.id = c.categories_id')
            ->with(['order.group','order.location','asset.unit','asset.category'])
            ->where(['between','b.date_approve', $start, $end])
            ->andWhere(['c.categories_id' => $categ])
            ->andWhere(['b.state' => AssetOrder::APPROVED])
            ->asArray()
            ->all();
        return $model;
    }

    public function actionReportLocationList(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $start = $data['date_start'];
        $end = $data['date_end'];
        $location = $data['location']['id'];

        $model = AssetOrderLine::find()->alias("a")
            ->leftJoin('asset_order b','b.id = a.order_id')
            ->leftJoin('asset_asset c','c.id = a.asset_id')
            ->leftJoin('asset_categories d','d.id = c.categories_id')
            ->leftJoin('asset_location e','e.id = b.location_id')
            ->with(['order.group','order.location','asset.unit','asset.category'])
            ->where(['between','b.date_approve', $start, $end])
            ->andWhere(['b.location_id' => $location])
            ->andWhere(['b.state' => AssetOrder::APPROVED])
            ->asArray()
            ->all();
        return $model;
    }

    public function actionReportAssetYearJson(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $year = $data['model']['year']['id'];

        $query = AssetOrderLine::find()->alias("a")
            ->select('
                b.date_approve,
                SUM(a.qty) as qty
            ')
            ->leftJoin("asset_order b",'b.id = a.order_id')
            ->where(['year(b.date_approve)' => $year])
            ->andWhere(['b.state' => "approved"])
            ->groupBy("month(b.date_approve)")
            ->asArray()->all();
        return $query;
    }

    public function actionReportAssetYearDetail(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $date = $data['model']['date_approve'];
        $subDate = substr($date,5,2);

        $query = AssetOrderLine::find()->alias("a")
            ->select('
                a.qty,e.name as unit_name,
                d.name as category,c.description,
                b.name as doc_name,c.name
            ')
            ->leftJoin('asset_order b','b.id = a.order_id')
            ->leftJoin('asset_asset c','c.id = a.asset_id')
            ->leftJoin('asset_categories d','d.id = c.categories_id')
            ->leftJoin('asset_unit e','e.id = c.unit_id')
            ->where(['month(b.date_approve)' => $subDate])
            ->andWhere(['b.state' => 'approved'])
            ->asArray()->all();
        return $query;
    }

    public function actionReportAssetMonthJson(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $month = $data['model']['month']['id'];
        $year = $data['model']['year']['id'];
        $query = AssetOrderLine::find()->alias('a')
            ->select('
                a.qty,e.name as unit_name,
                d.name as category,c.description,
                b.name as doc_name,c.name,b.date_approve,b.full_name,
                f.name as group_name,f.department
            ')
            ->leftJoin('asset_order b','b.id = a.order_id')
            ->leftJoin('asset_asset c','c.id = a.asset_id')
            ->leftJoin('asset_categories d','d.id = c.categories_id')
            ->leftJoin('asset_unit e','e.id = c.unit_id')
            ->leftJoin('res_group f','f.id = b.group_id')
            ->where(['month(b.date_approve)' => $month])
            ->andWhere(['year(b.date_approve)' => $year])
            ->andWhere(['b.state' => "approved"])
            ->asArray()->all();
        return $query;
    }

    public function actionReportAssetGroupJson(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $start = $data['date_start'];
        $end = $data['date_end'];
        $group = $data['group']['id'];

        $query = AssetOrder::find()
            ->with(['group'])
            ->where(['between','date_approve', $start, $end])
            ->andWhere(['group_id' => $group])
            ->andWhere(['state' => 'approved'])
            ->asArray()->all();
        return $query;
    }

    public function actionReportAssetGroupDetail(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $model = $data['model'];
        $input_id = $model['id'];

        $query = AssetOrderLine::find()
            ->with(['asset','asset.unit','asset.category'])
            ->where(['order_id' => $input_id])
            ->asArray()
            ->all();
        return $query;
    }



    public function actionReportReturnYearJson(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $year = $data['model']['year']['id'];

        $query = ReturnOrderLine::find()->alias("a")
            ->select('
                b.date_approve,
                SUM(a.qty) as qty
            ')
            ->leftJoin("return_order b",'b.id = a.order_id')
            ->where(['year(b.date_approve)' => $year])
            ->andWhere(['b.state' => "approved"])
            ->groupBy("month(b.date_approve)")
            ->asArray()->all();
        return $query;
    }

    public function actionReportReturnYearDetail(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $date = $data['model']['date_approve'];
        $subDate = substr($date,5,2);

        $query = ReturnOrderLine::find()->alias("a")
            ->select('
                a.qty,e.name as unit_name,
                d.name as category,c.description,
                b.name as doc_name,c.name
            ')
            ->leftJoin('return_order b','b.id = a.order_id')
            ->leftJoin('asset_asset c','c.id = a.asset_id')
            ->leftJoin('asset_categories d','d.id = c.categories_id')
            ->leftJoin('asset_unit e','e.id = c.unit_id')
            ->where(['month(b.date_approve)' => $subDate])
            ->andWhere(['b.state' => 'approved'])
            ->asArray()->all();
        return $query;
    }


    public function actionReportReturnMonthJson(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $month = $data['model']['month']['id'];
        $year = $data['model']['year']['id'];
        $query = ReturnOrderLine::find()->alias('a')
            ->select('
                a.qty,e.name as unit_name,
                d.name as category,c.description,
                b.name as doc_name,c.name,b.date_approve,b.full_name,
                f.name as group_name,f.department
            ')
            ->leftJoin('return_order b','b.id = a.order_id')
            ->leftJoin('asset_asset c','c.id = a.asset_id')
            ->leftJoin('asset_categories d','d.id = c.categories_id')
            ->leftJoin('asset_unit e','e.id = c.unit_id')
            ->leftJoin('res_group f','f.id = b.group_id')
            ->where(['month(b.date_approve)' => $month])
            ->andWhere(['year(b.date_approve)' => $year])
            ->andWhere(['b.state' => "approved"])
            ->asArray()->all();
        return $query;
    }



    public function actionReportReturnGroupJson(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $start = $data['date_start'];
        $end = $data['date_end'];
        $group = $data['group']['id'];

        $query = ReturnOrder::find()
            ->with(['group'])
            ->where(['between','date_approve', $start, $end])
            ->andWhere(['group_id' => $group])
            ->andWhere(['state' => 'approved'])
            ->asArray()->all();
        return $query;
    }

    public function actionReportReturnGroupDetail(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $model = $data['model'];
        $input_id = $model['id'];

        $query = ReturnOrderLine::find()
            ->with(['asset','asset.unit','asset.category'])
            ->where(['order_id' => $input_id])
            ->asArray()
            ->all();
        return $query;
    }
    
}