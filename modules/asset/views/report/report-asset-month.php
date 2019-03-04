<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\components\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use app\modules\asset\models\AssetOrder;
use app\modules\asset\Asset;
use app\modules\asset\assets\ReportAsset;
use yii\web\View;

$this->title = Yii::t('app', 'เบิกจ่ายแยกตามประจำเดือน');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
    .my-table tr th {
      padding: 8px;
    }
    
    .my-table tr td {
      padding: 1px;
    }
   
  ');
  $this->registerCss('[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
        display: none !important;
    }');
    ReportAsset::register($this, View::POS_READY);
?>
<div class="report-asset-month" ng-app="ReportApp" ng-controller="ReportAssetMonthController" ng-cloak>
    <h2><?= Html::encode($this->title) ?></h2>

    <hr />

    <div class="alert alert-info col-sm-12" style="padding: 8px">
        <form name="form1">
            <div class="form-group form-group-sm">

                <div class="col-sm-2">
                    <label> เลือกเดือน</label>
                    
                    <ui-select ng-model="model.month"
                               theme="bootstrap"
                               reset-search-input="true"
                               title="เลือกเลือก"
                               uis-open-close='onMonthOpenClose(isOpen)'
                               >
                        <ui-select-match placeholder="เลือกเลือก..."
                                         allow-clear='true'>{{$select.selected.name}}</ui-select-match>
                        <ui-select-choices 
                            repeat="month as month in months"
                            refresh="refreshMonth($select.search)"
                            refresh-on-active='true'
                            refresh-delay="0">
                            <small>{{month.name}}</small>
                        </ui-select-choices>
                    </ui-select>
                </div>

                <div class="col-sm-2">
                    <label> เลือกปี</label>
                    
                    <ui-select ng-model="model.year"
                               theme="bootstrap"
                               reset-search-input="true"
                               title="เลือกปี"
                               uis-open-close='onYearOpenClose(isOpen)'
                               >
                        <ui-select-match placeholder="เลือกปี..."
                                         allow-clear='true'>{{$select.selected.name}}</ui-select-match>
                        <ui-select-choices 
                            repeat="year as year in years"
                            refresh="refreshYear($select.search)"
                            refresh-on-active='true'
                            refresh-delay="0">
                            <small>{{year.name}}</small>
                        </ui-select-choices>
                    </ui-select>
                </div>

             
            </div>
        </form>
    </div>
    
    <div style="margin-top: 10px;text-align: left">
        <button type="button" ng-click="RunReport()" class="btn btn-primary">Run Report</button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered" style="margin-top: 15px">
            <thead>
                <tr>
                    <th width="auto" style="text-align: center">เลขที่เอกสาร</th>
                    <th width="auto" style="text-align: center">วันที่</th>
                    <th width="auto" style="text-align: center">ชื่อผู้ขอเบิก</th>
                    <th width="auto" style="text-align: center">ฝ่าย</th>
                    <th width="auto" style="text-align: center">แผนก</th>
                    <th width="auto" style="text-align: center">หมายเลขครุภัณฑ์</th>
                    <th width="auto" style="text-align: center">รายการ</th>
                    <th width="auto" style="text-align: center">ประเภทสินทรัพย์</th>
                    <th width="auto" style="text-align: center">จำนวน</th>
                    <th width="auto" style="text-align: center">หน่วย</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="line in report_asset_month">
                    <td>{{ line.doc_name }}</td>
                    <td>{{ line.date_approve }}</td>
                    <td>{{ line.full_name }}</td>
                    <td>{{ line.group_name }}</td>
                    <td>{{ line.department }}</td>
                    <td>{{ line.name }} </td>
                    <td>{{ line.description }}</td>
                    <td>{{ line.category }}</td>
                    <td align="right"> {{ line.qty | number }}</td>
                    <td align="right"> {{ line.unit_name }} </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="8">รวม</th>
                    <th style="text-align: right">{{ report_asset_month | sumOfValue:'qty'}}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>