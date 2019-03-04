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

$this->title = Yii::t('app', 'สรุปตามประเภทสินทรัพย์');
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
<div class="report-index" ng-app="ReportApp" ng-controller="ReportController" ng-cloak>
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
                    <label> ประเภทสินทรัพย์</label>
                    
                    <ui-select ng-model="model.category"
                               theme="bootstrap"
                               reset-search-input="true"
                               title="เลือกประเภทสินทรัพย์"
                               uis-open-close='onCategoriesOpenClose(isOpen)'
                               >
                        <ui-select-match placeholder="ค้นหาประเภทสินทรัพย์..."
                                         allow-clear='true'>{{$select.selected.name}}</ui-select-match>
                        <ui-select-choices 
                            repeat="category as category in categ"
                            refresh="refreshCategories($select.search)"
                            refresh-on-active='true'
                            refresh-delay="0">
                            <small>{{category.name}}</small>
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
                  <th style="text-align: center">วันที่</th>
                  <th style="text-align: center">เลขเอกสาร</th>
                  <th style="text-align: center">ผู้ขอเบิก</th>
                  <th style="text-align: center">ฝ่าย-แผนก</th>
                  <th style="text-align: center">หมายเลขครุภัณฑ์</th>
                  <th style="text-align: center">รายการ</th>
                  <th style="text-align: center">ประเภทสินทรัพย์</th>
                  <th style="text-align: center">จำนวน</th>
                  <th style="text-align: center">หน่วย</th>
              </tr>
          </thead>
          <tbody>
              <tr ng-repeat="line in categories">
                  <td>{{ line.date_approve }}</td>
                  <td>{{ line.order.name }}</td>
                  <td>{{ line.order.full_name }}</td>
                  <td>{{ line.order.group.name }} - {{ line.order.group.department }}</td>
                  <td>{{ line.asset.name }}</td>
                  <td>{{ line.asset.description }}</td>
                  <td>{{ line.asset.category.name }}</td>
                  <td align="right">{{ line.qty | number }}</td>
                  <td align="right">{{ line.asset.unit.name }}</td>
              </tr>
          </tbody>
          <tfoot>
              <tr>
                  <td colspan="7">รวม</td>
                  <td align="right"> {{ categories | sumOfValue:'qty'}}</td>
                  <td></td>
              </tr>
          </tfoot>
      </table>
    </div>
</div>