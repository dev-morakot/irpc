<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\modules\resource\assets\ResUsersManageAsset;
use yii\widgets\ActiveForm;


ResUsersManageAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel app\modules\resource\models\ResUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'ผู้ใช้งานระบบ');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin/default']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
    $this->registerCss('[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
        display: none !important;
    }');
    $this->registerCss('
            .no-border{
                border:0;
                box-shadow:none;
            }
            
            .show-number{
                background-color:#449D44;
                color: white;
            }

            ');
    ?>
<div ng-app="ResUsersApp" ng-cloak ng-controller="ResUsersManageController as ctrl" class="res-users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="pull-left">
        <?= Html::a(Yii::t('app', 'จัดการผู้ใช้งานระบบ'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="pull-right"> <b class="glyphicon glyphicon-user"></b> ผู้ใช้งานทั้งหมด : <span class="badge"><?php echo $count; ?></span> คน  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;  <b class="glyphicon glyphicon-remove"></b> ไม่สามารถเข้าระบบ : <span class="badge"><?php echo $uncount; ?></span> คน</p>
    <p class="clearfix"></p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped" style="margin-top: 10px">
            <thead>
                <tr>
                    <th width="50px" style="text-align: center">#</th>
                    <th width="auto" style="text-align: center">รหัสพนักงาน</th>
                    <th width="auto" style="text-align: center">Username</th>
                    <th width="auto" style="text-align: center">ชื่อ - นามสกุล</th>
                   
                    <th width="auto" style="text-align: center">Email</th>
                    <th width="auto" style="text-align: center">ฝ่าย/แผนก</th>
                    <th width="auto" style="text-align: center">active</th>
                </tr>
                <tr>
                    <th></th>
                    <th><input type="text" ng-model="search.code" placeholder="ค้นหารหัส" class="form-control" /></th>
                    <th><input type="text" ng-model="search.username" placeholder="ค้นหา username" class="form-control" /></th>
                    <th><input type="text" ng-model="search.firstname" placeholder="ค้นหาชื่อ" class="form-control" /></th>
                    <th><input type="text" ng-model="search.email" placeholder="ค้นหาอีเมล์" class="form-control" /></th>
                    <th><input type="text" ng-model="search.group_name" class="form-control" placeholder="ค้นหาฝ่าย"></th>
                    <th>
                        <select ng-model="search.active" class="form-control">
                            <option vlaue=""></option>
                            <option value="1">อนุญาติ</option>
                            <option value="0">ไม่อนุญาติ</option>
                        </select>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="line in ctrl.dataUser | filter: search">
                    <td align="center">
                      {{ $index + 1}}
                    </td>
                    <td align="center">
                       <span ng-if="line.code !== null">
                        <a href="javascript:void(0)" ng-click="ctrl.clickLine(line)" data-toggle="modal" data-target="#myModal">
                            {{ line.code }}
                        </a>
                        </span>
                        <span ng-if="line.code === null || line.code === ''">
                            <a href="javascript:void(0)" ng-click="ctrl.clickLine(line)" data-toggle="modal" data-target="#myModal">
                                ไม่มีรหัสพนักงาน
                            </a>
                        </span>
                    </td>
                    <td>{{ line.username }}</td>
                    <td>{{ line.firstname }} &nbsp;&nbsp; {{ line.lastname }}</td>
                    
                    <td>{{ line.email }}</td>
                    <td>{{ line.group_name }} /  {{ line.department }}</td>
                    <td align="center">

                        <span ng-if="line.active === '1'">
                            <b class="glyphicon glyphicon-ok" style="color: green"></b>
                        </span>
                        <span ng-if="line.active === '0' || line.active === null">
                            <b class="glyphicon glyphicon-remove" style="color: red"></b>
                        </span>
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
        <h4 class="modal-title" id="myModalLabel">ข้อมูลผู้ใช้งาน</h4>
      </div>
      <div class="modal-body">
           
           
                
            <?php echo $this->render('_update'); ?>
                
      </div>
      <div class="modal-footer">
        <div class="pull-left">
            <button type="button" class="btn btn-danger" ng-click="ctrl.ResUserDelete(model)" data-dismiss="modal"> Delete</button>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" ng-click="ctrl.ResUserEdit(model)" data-dismiss="modal">Save changes</button>
        </div>
        <div class="clearfix"></div>
        
      </div>
    </div>
  </div>
</div>


</div>