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

$this->title = Yii::t('app', 'รายงานผู้ให้บริการ');
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
<div class="request-index" ng-app="ReportApp" ng-controller="ReportServiceController" ng-cloak>
    <h2><?= Html::encode($this->title) ?></h2>

    <hr />

    <div class="alert alert-info col-sm-12" style="padding: 8px">
        <form name="report">
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
                    <label> ผู้ให้บริการ</label>
                    
                    <ui-select ng-model="model.service"
                               theme="bootstrap"
                               reset-search-input="true"
                               title="เลือกผู้ให้บริการ"
                               uis-open-close='onServiceOpenClose(isOpen)'
                               >
                        <ui-select-match placeholder="ค้นหาชื่อผู้ให้บริการ..."
                                         allow-clear='true'>{{$select.selected.firstname}}</ui-select-match>
                        <ui-select-choices 
                            repeat="service as service in services"
                            refresh="refreshUsedService($select.search)"
                            refresh-on-active='true'
                            refresh-delay="0">
                            <small>{{service.firstname}}</small>
                        </ui-select-choices>
                    </ui-select>
                </div>

                <div class="col-sm-2">
                    <label> แผนก </label>

                        <ui-select ng-model="model.department" 
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
      <table class="table table-bordered table-striped" style="margin-top: 15px">
          <thead>
              <tr>
                  <th>เลขที่ใบแจ้งซ่อม</th>
                  <th>ผู้แจ้งซ่อม</th>
                  <th>แผนก</th>
                  <th>หมายเลขคุรภัณฑ์</th>
                  <th>รุ่น/ยี่ห้อ</th>
                  <th>ปัญหาที่แจ้ง</th>
                  <th>สถานะ</th>
                  <th>ใบแจ้งซ่อม</th>
              </tr>
          </thead>
          <tbody>
            <tr ng-repeat="line in report">
                <td>
                    <a href="" ng-click="open(line)" data-toggle="modal" data-target="#myModal">
                    {{ line.name }}
                    </a>
                </td>
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


     <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 70%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">รายละเอียดงาน</h4>
      </div>
      <div class="modal-body">
           
        <div class="row">
        <div class="col-lg-6">
           <table class="table table-striped table-bordered">
                <tr>
                    <td>ID</td>
                    <td>{{ modline.id }}</td>
                </tr>
                <tr><td>เลขที่เอกสาร</td><td>{{ modline.name }}</td></tr>
                <tr>
                    <td>วันที่บันทึก</td>
                    <td>{{ modline.date_create }}</td>
                </tr>
                <tr>
                    <td>หมายเลขคุรภัณฑ์</td>
                    <td>{{ modline.sn_number }}</td>
                </tr>
                <tr>
                    <td>ตรวจสอบเบื้องต้น (ระบุสาเหตุ/ปัญหาที่พบ)</td>
                    <td>{{ modline.description }}</td>
                </tr>
                <tr>
                    <td>รุ่น/ยี่ห้อ</td>
                    <td>{{ modline.brand }}</td>
                </tr>
                <tr>
                    <td>แจ้งปัญหา</td>
                    <td>
                        <span ng-if="modline.problem == 'computer'"> ปัญหาคอมพิวเตอร์ </span>
                        <span ng-if="modline.problem == 'network'"> ปัญหาเครือข่าย </span>
                        <span ng-if="modline.problem == 'other'"> อื่นๆ  &nbps;&nbps;&nbsp; {{ modline.other }}
                    </td>
                </tr>
                <tr>
                    <td>บริการแจ้งซ่อมอาคาร</td>
                    <td>{{ modline.building }}</td>
                </tr>
                <tr>
                    <td>บริการงานธุรการ</td>
                    <td>{{ modline.officer }}</td>
                </tr>
                <tr>
                    <td>ผู้บันทึกแจ้งซ่อม</td>
                    <td>{{ modline.firstname }} &nbsp; {{ modline.lastname }}</td>
                </tr>
           </table>
        </div>
        <div class="col-lg-6">
            <table class="table table-striped table-bordered">
                <tr>
                    <td>วันที่ปิดงานซ่อม</td>
                    <td>{{ modline.date_close }}</td>
                </tr>
                <tr><td>ปัญหา/สาเหตุ</td><td>{{ modline.answer }}</td></tr>
                <tr>
                    <td>รายละเอียดการแก้ปัญหา</td>
                    <td>{{ modline.detail_work }}</td>
                </tr>
                <tr>
                    <td>รายละเอียดบริการซ่อมอาคาร</td>
                    <td>{{ modline.detail_building }}</td>
                </tr>
                <tr>
                    <td>รายละเอียดบริการงานธุรการ</td>
                    <td>{{ modline.detail_officer }}</td>
                </tr>
                <tr>
                    <td>ผู้ปิดงานซ่อม</td>
                    <td>{{ modline.close_firstname }} &nbsp; {{ modline.close_lastname }}</td>
                </tr>
           </table>
        </div>

        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</div> <!-- end angular -->