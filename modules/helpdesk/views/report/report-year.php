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

$this->title = Yii::t('app', 'รายงานซ่อมประจำปี');
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
<div class="report-year" ng-app="ReportApp" ng-controller="ReportRequestYearController" ng-cloak>
    <h2><?= Html::encode($this->title) ?></h2>

    <hr />

    <div class="alert alert-info col-sm-12" style="padding: 8px">
        <form name="form1">
            <div class="form-group form-group-sm">

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
                    <th width="500px" style="text-align: center">เดือน/ปี</th>
                    <th width="100px" style="text-align: center"> จำนวนที่ซ่อม</th>
                    <th width="100px" style="text-align: center"></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="line in report_year">
                    <td>{{ line.date_close | date: "MMMM" }}</td>
                    <td align="right">{{ line.qty | number }}</td>
                    <td align="center">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" ng-click="dialog(line)" data-target="#myModal">
                            รายละเอียด
                        </button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>รวม</th>
                    <th style="text-align:right">{{ sum | number }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>

        <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 70%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> รายละเอียด</h4>
      </div>
      <div class="modal-body">
            
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
                        <tr ng-repeat="line in modelLines">
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
      <div class="modal-footer">
       
        <div class="pull-right">
            <button type="button" class="btn btn-default" data-dismiss="modal">
               ยกเลิก
            </button>
        </div>
        <div class="clearfix"></div>
        
      </div>
    </div>
  </div>
</div>



</div>