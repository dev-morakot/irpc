<?php 

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use app\modules\helpdesk\assets\RequestAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\helpdesk\models\Request */
/* @var $form yii\widgets\ActiveForm */
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
RequestAsset::register($this, View::POS_READY);
?>

<div ng-app="RequestApp" ng-cloak ng-controller="RequestFormController as ctrl">


    
    <form novalidate class="form-horizontal" name="requestForm" id="requestForm">
        <div bic-doc-status state="model.state" list="states"></div>
        <div class="pull-left">
            <div ng-if="true == requestForm.$valid">
                <span class="label label-success">ฟอร์มสมบูรณ์</span>
            </div>
            <div ng-if="false == requestForm.$valid">
                <span class="label label-warning">ฟอร์มข้อมูลไม่ครบถ้วน</span>
            </div>
        </div>
       
        <div class="clearfix"></div>
        <div id="my-message"></div>
        <div class="well" style="margin-top: 10px">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-3">เลขที่เอกสาร</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <p id="doc-name" type="text" class="form-control bic-required-field" 
                                   style="width: 230px"
                                  >
                                  {{ model.name }}
                                </p>
                                <span class="input-group-btn" style="width:230px">
                                <button class="btn" 
                                        id="doc-auto" 
                                        ng-class="(model.autodoc)?'btn-primary':'btn-default'"
                                        ng-show="model.id==-1"
                                        ng-click="ctrl.onClickAutoDoc($event)">อัตโนมัติ</button>
                                </span>
                            </div>
                        </div>
                    
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3"> หมายเลขคุรภัณฑ์ </label>
                        <div class="col-sm-9" id="sn_number">
                           <input type="text" ng-model="model.sn_number" style="width: 300px;" class="form-control" required />
                           <div class="help-block help-block-error"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3">ตรวจสอบเบื้องต้น (ระบุสาเหตุ/ปัญหาที่พบ)</label>
                        <div class="col-sm-7" id="description">
                            <textarea rows="4" cols="30" class="form-control" required ng-model="model.description" bic-required-field> {{ model.description }}</textarea>
                            <div class="help-block help-block-error"></div>
                        </div>
                    </div>

                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-3"> รุ่น/ยี่ห้อ </label>
                        <div class="col-sm-9" id="brand">
                           <input type="text" ng-model="model.brand" style="width: 300px;" class="form-control" required />
                           <div class="help-block help-block-error"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3"> แจ้งปัญหา </label>
                        <div class="col-sm-9" id="problem">
                            <select ng-model="model.problem" class="form-control" style="width: 300px">
                                <option value="computer">ปัญหาคอมพิวเตอร์</option>
                                <option value="network">ปัญหาเครือข่าย</option>
                                <option value="printer">ปัญหาปริ้นเตอร์</option>
                                <option value="other"> อื่นๆ </option>
                            </select>

                            <div calss="col-sm-9">
                            <span ng-show="model.problem === 'other'">
                                <input type="text" ng-model="model.other" placeholder="อื่นๆ" class="form-control" style="width: 250px" />
                            </span>
                            </div>

                            <div class="help-block help-block-error"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3"> บริการแจ้งซ่อมอาคาร </label>
                        <div class="col-sm-9" id="building">
                            <input type="text" ng-model="model.building" class="form-control" style="width: 300px">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3"> บริการงานธุรการ </label>
                        <div class="col-sm-9" id="officer">
                            <input type="text" ng-model="model.officer" class="form-control" style="width: 300px">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 col-xs-offset-0"></label>
            <div class="col-sm-10">
                <button type="button" class="btn btn-primary" ng-click="save()"> บันทึกรายการ </button>
            </div>
        </div>
    </form>

</div>