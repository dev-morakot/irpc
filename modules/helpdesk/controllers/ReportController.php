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


class ReportController extends Controller {
    

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


    public function actionReportService(){
        $this->layout = "main_default";
        return $this->render('report-service');
    }

    public function actionReportYear(){
        $this->layout= "main_default";
        return $this->render("report-year");
    }

     public function actionReportMonth(){
        $this->layout= "main_default";
        return $this->render("report-month");
    }

    public function actionServiceListJson($q = null, $limit = 20) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $usages = ['it','officer','builder'];

        $query = \app\modules\resource\models\ResUsers::find()
            ->where(['like', 'firstname', $q])
            ->andWhere(['in' ,'rule_help', $usages]);
        $service = $query->limit($limit)->all();

        return $service;
    }

    public function actionGroupListJson($q = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = \app\modules\resource\models\ResGroup::find()
            ->where(['like','department', $q])
            ->all();
        return $query;
    }

    public function actionRunreportService(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $start = $data['date_start'];
        $end = $data['date_end'];
        $used = isset($data['service'])?$data['service']['id']:null;
        $department = isset($data['department'])?$data['department']['id']:null;

        $model = Request::find()->alias('r')
            ->select('
                r.id,
                r.name,
                r.sn_number,
                r.description,
                r.state,
                r.date_close,r.date_create,
                r.brand,r.building,r.officer,r.detail_building,r.detail_officer,
                u.firstname, u.lastname,
                g.department, useRepair.firstname as repair_name, useRepair.lastname as repair_lname,
                r.problem,r.other,r.answer,r.detail_work,close.firstname as close_firstname,close.lastname as close_lastname
            ')
            //->with(['user','repair','builder1','builder2','builder3','builder4','officers'])
            ->leftJoin('res_users as u','u.id = r.requested_by')
            ->leftJoin("res_users_group_rel as a", 'a.user_id = u.id')
            ->leftJoin("res_group as g",'g.id = a.group_id')
            ->leftJoin('res_users as useRepair', 'useRepair.id = r.repair_id')
            ->leftJoin('res_users as close','close.id = r.close_id')
            ->where(['between','r.date_close', $start, $end])
            ->andWhere(['in','r.state', ['close','endjob']])
           //w, ->andWhere(['or',['g.id' => $department]])
           //->andWhere(['or',['r.repair_id' => $used]])
            ->andWhere(['or', ['r.officer_id' => $used],
                ['r.builder1_id' => $used],
                ['r.builder2_id' => $used],
                ['r.builder3_id' => $used],
                ['r.builder4_id' => $used],
                ['r.repair_id' => $used],
                ['g.id' => $department]
                
            ])
            ->orderBy('r.id desc')
            ->asArray()->all();
        return $model;
    }

    public function actionReportYearJson(){
         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $year = $data['model']['year']['id'];

         $query = Request::find()->alias("a")
            ->select('
                a.date_close,
                COUNT(a.state) as qty
            ')
            ->where(['year(a.date_close)' => $year])
            ->andWhere(['in','a.state',['endjob','close']])
            ->groupBy("month(a.date_close)")
            ->asArray()->all();
        return $query;
    }

    public function actionReportYearDetail(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $date = $data['model']['date_close'];
        $subDate = substr($date,5,2);


        $model = Request::find()->alias('r')
            ->select('
                r.id,
                r.name,
                r.sn_number,
                r.description,
                r.state,
                r.date_close,r.date_create,
                r.brand,r.building,r.officer,r.detail_building,r.detail_officer,
                u.firstname, u.lastname,
                g.department, useRepair.firstname as repair_name, useRepair.lastname as repair_lname,
                r.problem,r.other,r.answer,r.detail_work,close.firstname as close_firstname,close.lastname as close_lastname
            ')
            //->with(['user','repair','builder1','builder2','builder3','builder4','officers'])
            ->leftJoin('res_users as u','u.id = r.requested_by')
            ->leftJoin("res_users_group_rel as a", 'a.user_id = u.id')
            ->leftJoin("res_group as g",'g.id = a.group_id')
            ->leftJoin('res_users as useRepair', 'useRepair.id = r.repair_id')
            ->leftJoin('res_users as close','close.id = r.close_id')
            ->where(['month(r.date_close)' => $subDate])
            ->andWhere(['in','r.state', ['close','endjob']])
            ->orderBy('r.id desc')
            ->asArray()->all();
        return $model;
    }

    public function actionReportMonthJson(){
         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $month = $data['model']['month']['id'];
        $year = $data['model']['year']['id'];

        $model = Request::find()->alias('r')
            ->select('
                r.id,
                r.name,
                r.sn_number,
                r.description,
                r.state,
                r.date_close,r.date_create,
                r.brand,r.building,r.officer,r.detail_building,r.detail_officer,
                u.firstname, u.lastname,
                g.department, useRepair.firstname as repair_name, useRepair.lastname as repair_lname,
                r.problem,r.other,r.answer,r.detail_work,close.firstname as close_firstname,close.lastname as close_lastname
            ')
            //->with(['user','repair','builder1','builder2','builder3','builder4','officers'])
            ->leftJoin('res_users as u','u.id = r.requested_by')
            ->leftJoin("res_users_group_rel as a", 'a.user_id = u.id')
            ->leftJoin("res_group as g",'g.id = a.group_id')
            ->leftJoin('res_users as useRepair', 'useRepair.id = r.repair_id')
            ->leftJoin('res_users as close','close.id = r.close_id')
            ->where(['month(r.date_close)' => $month])
            ->andWhere(['year(r.date_close)' => $year])
            ->andWhere(['in','r.state', ['close','endjob']])
            ->asArray()->all();
        return $model;
    }
}

?>