<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\components\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use app\modules\helpdesk\models\Request;
use app\modules\helpdesk\HelpDesk;
use app\modules\helpdesk\assets\ReportAsset;
use yii\web\View;

$this->title = Yii::t('app', 'รายงานซ่อมประจำเดือน');
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
<div class="report-month" ng-app="ReportApp" ng-controller="ReportRequestMonthController" ng-cloak>
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
                <table class="table table-bordered table-striped" style="margin-top: 15px">
                    <thead>
                        <tr>
                            <th colspan="9" style="text-align: center">รายละเอียดการแจ้งซ่อม : {{ modline.date_close | date: "MMMM" }}</th>
                        </tr>
                        <tr>
                            <th width="auto" style="text-align: center">เลขที่ใบแจ้งซ่อม</th>
                            <th width="auto" style="text-align: center">วันที่ซ่อม</th>
                            <th width="auto" style="text-align: center">ผู้แจ้งซ่อม</th>
                            <th width="auto" style="text-align: center">แผนก</th>
                            <th width="auto" style="text-align: center">หมายเลขคุรภัณฑ์</th>
                            <th width="auto" style="text-align: center">รุ่น/ยี่ห้อ</th>
                            <th width="auto" style="text-align: center">ปัญหาที่แจ้ง</th>
                            <th width="auto" style="text-align: center">สถานะ</th>
                            <th width="auto" style="text-align: center">ใบแจ้งซ่อม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in report_month">
                            <td>
                                <a href="">
                                {{ line.name }}
                                </a>
                            </td>
                            <td>{{ line.date_close }}</td>
                            <td>{{ line.firstname }} &nbsp; {{ line.lastname }}</td>
                            <td>{{ line.department }}</td>
                            <td>{{ line.sn_number }}</td>
                            <td>{{ line.brand }}</td>
                            <td>
                                <span ng-if="line.problem == 'computer'">ปัญหาคอมพิวเตอร์</span>
                                <span ng-if="line.problem == 'network'">ปัญหาเครือข่าย</span>
                                <span ng-if="line.problem == 'printer'">ปัญหาปริ้นเตอร์</span>
                                <span ng-if="line.problem == 'other'">อื่นๆ</span>
                            </td>
                            <td>
                                <span ng-if="line.state === 'close'" class="label label-success"> รอจบงานซ่อม</span>
                                <span ng-if="line.state === 'endjob'" class="label label-primary"> จบงานซ่อม</span>
                            </td>
                            <td align="center">
                                <a href="index.php?r=helpdesk/request/print&id={{ line.id }}" target="_blank" class="glyphicon glyphicon-file"></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
</div>