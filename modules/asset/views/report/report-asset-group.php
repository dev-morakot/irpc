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

$this->title = Yii::t('app', 'เบิกจ่ายแยกตามฝ่าย/แผนก');
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
<div class="report-index" ng-app="ReportApp" ng-controller="ReportAssetGroupController" ng-cloak>
    <h2><?= Html::encode($this->title) ?></h2>

    <hr />

    <div class="alert alert-info col-sm-12" style="padding: 8px">
        <form name="form1">
            <div class="form-group form-group-sm">
                <div class="col-sm-2">
                    <label> วันที่ </label>
                    <div class="input-group">
                        <input type="text" class="input-sm form-control"
                        uib-datepicker-popup="dd/MM/yyyy"
                        ng-model="model.date_start"
                        datepicker-options="datepickerOptions"
                        is-open="datepicker1.opened"
                        close-text="Close" required />
                        <span class="input-group-btn">
                            <button type="button" class="input-sm btn btn-default" ng-click="openDatepicker1()"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                    </div>
                </div>

            
                <div class="col-sm-2">
                    <label> ถึง </label> 
                    <div class="input-group">
                        <input type="text" class="input-sm form-control"
                        uib-datepicker-popup="dd/MM/yyyy"
                        ng-model="model.date_end"
                        datepicker-options="datepickerOptions"
                        is-open="datepicker2.opened"
                        close-text="Close" required /> 
                        <span class="input-group-btn">
                            <button type="button" class="input-sm btn btn-default" ng-click="openDatepicker2()"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                    </div>
                </div>

                <div class="col-sm-2">
                    <label> ฝ่าย/แผนก</label>
                    
                    <ui-select ng-model="model.group" 
                            reset-search-input="true"
                            style="min-width: 300px;" theme="bootstrap" title="เลือกแผนก" uis-open-close="onGroupOpenClose(isOpen)">
                            <ui-select-match allow-clear='true' placeholder="ค้นหาชื่อแผนก">{{$select.selected.department}}</ui-select-match>
                            <ui-select-choices group-by="'name'" 
                            repeat="person in groups" 
                            refresh-on-active='true'
                            refresh-delay="0"
                            refresh="refreshGroup($select.search)">
                                <div ng-bind-html="person.department | highlight: $select.search"></div>
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
                    <th width="auto" style="text-align: center">วันที่เบิก</th>
                    <th width="auto" style="text-align: center">ชื่อผู้เบิก</th>
                    <th width="auto" style="text-align: center">ฝ่าย</th>
                    <th width="auto" style="text-align: center">แผนก</th>
                    <th width="auto" style="text-align: center">ใช้สำหรับ</th>
                    <th width="auto" style="text-align: center">รายการทีเบิก</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="line in report_asset_group">
                    <td>{{ line.name }}</td>
                    <td>{{ line.date_approve }}</td>
                    <td>{{ line.full_name }} </td>
                    <td>{{ line.group.name }}</td>
                    <td>{{ line.group.department }}</td>
                    <td>{{ line.notes }}</td>
                    <td align="center">
                        <button type="button" class="btn btn-primary btn-sm" ng-click="detail(line)" data-toggle="modal" data-target="#myModal">
                            พัสดุที่เบิก
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 70%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">ใบเบิกพัสดุ - ครุภัณฑ์</h4>
      </div>
      <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="margin-top: 15px">
                    <thead>
                        <tr>
                            <th width="auto" style="text-align: center">#</th>
                            <th width="auto" style="text-align: center">หมายเลขครุภัณฑ์</th>
                            <th width="500px" style="text-align: center">รายการ</th>
                            <th width="auto" style="text-align: center">ประเภทสินทรัพย์</th>
                            <th width="auto" style="text-align: center">จำนวน</th>
                            <th width="auto" style="text-align: center">หน่วย</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in modelLines">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ line.asset.name }}</td>
                            <td>{{ line.asset.description }}</td>
                            <td>{{ line.asset.category.name }}</td>
                            <td align="right">{{ line.qty | number }}</td>
                            <td align="right">{{ line.asset.unit.name }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">รวม</th>
                            <th style="text-align: right">{{ modelLines | sumOfValue:'qty'}}</th>
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