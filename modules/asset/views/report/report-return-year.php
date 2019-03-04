<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\components\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use app\modules\asset\models\ReturnOrder;
use app\modules\asset\Asset;
use app\modules\asset\assets\ReportAsset;
use yii\web\View;

$this->title = Yii::t('app', 'คืนครุภัณฑ์แยกประจำปี');
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
<div class="report-return-year" ng-app="ReportApp" ng-controller="ReportReturnYearController" ng-cloak>
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
                    <th width="100px" style="text-align: center">จำนวนที่จ่ายคืนทั้งหมด</th>
                    <th width="100px" style="text-align: center"></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="line in report_return_year">
                    <td>{{ line.date_approve | date: "MMMM" }}</td>
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
                            <th colspan="8" style="text-align: center">รายละเอียดการคืนพัสดุ - ครุภัณฑ์ : {{ modline.date_approve | date: "MMMM" }}</th>
                        </tr>
                        <tr>
                            <th width="auto" style="text-align: center">#</th>
                            <th width="auto" style="text-align: center">เลขที่เอกสาร</th>
                            <th width="auto" style="text-align: center">หมายเลขครุภัณฑ์</th>
                            <th width="auto" style="text-align: center">รายการ</th>
                            <th width="auto" style="text-align: center">ประเภทสินทรัพย์</th>
                            <th width="auto" style="text-align: center">จำนวน</th>
                            <th width="auto" style="text-align: center">หน่วย</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in modelLines">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ line.doc_name }}</td>
                            <td>{{ line.name }}</td>
                            <td>{{ line.description }}</td>
                            <td>{{ line.category }}</td>
                            <td align="right">{{ line.qty | number }}</td>
                            <td align="right">{{ line.unit_name }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">รวม</th>
                            <th style="text-align: right"> {{ sum | number }} </th>
                            <th></th>
                        </tr>
                    </tfoot>
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