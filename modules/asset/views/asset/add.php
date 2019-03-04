<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use app\modules\asset\assets\AssetOrderAsset;


/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'เบิกพัสดุ-ครุภัณฑ์');
$this->params['breadcrumbs'][] = ['label' => 'เบิกพัสดุ-ครุภัณฑ์', 'url' => ['add']];
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
AssetOrderAsset::register($this);
?>
<div ng-app="myApp" ng-controller="AddController" class="add-index" ng-cloak>

    <h1><?= Html::encode($this->title) ?></h1>

    <button class="btn btn-success">
        เบิกพัสดุ-ครุภัณฑ์ <span class="badge">{{ cart.length }}</span>
    </button>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" style="margin-top: 15px">
            <thead>
                <tr>
                    <th width="auto" style="text-align: center">#</th>
                    <th width="auto" style="text-align: center">	หมายเลขครุภัณฑ์</th>
                    <th width="auto" style="text-align: center">รายการ</th>
                    <th width="auto" style="text-align: center">ประเภททรัพย์สิน</th>
                    <th width="auto" style="text-align: center">จำนวนคงเหลือ	</th>
                    <th width="auto" style="text-align: center">หน่วย	</th>
                    <th width="auto" style="text-align: center">	</th>
                </tr>
                <tr>
                    <th></th>
                    <th><input type="text" ng-model="search.name" class="form-control"></th>
                    <th><input type="text" ng-model="search.description" class="form-control"></th>
                    <th>
                        <select class="form-control" ng-model="search.categories_id">
                            <option value="">--- เลือกรายการ ---</option>
                            <option ng-repeat="c in category" value="{{ c.id }}">{{ c.name }}</option>
                        </select>
                    </th>
                    <th><input type="text" ng-model="search.qty" class="form-control"></th>
                    <th>
                        <select class="form-control" ng-model="search.unit_id">
                            <option value="">--- เลือกรายการ ---</option>
                            <option ng-repeat="u in unit" value="{{ u.id }}">{{u.name}}</option>
                        </select>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="line in listAsset | filter: search">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ line.name }}</td>
                    <td>{{ line.description}}</td>
                    <td>{{line.category.name}}</td>
                    <td align="right"> {{ line.qty | number }}</td>
                    <td align="right">{{ line.unit.name }} </td>
                    <td align="center">
                        <button class="btn btn-primary" ng-click="addData(line)" data-toggle="modal" data-target="#myModal"> เบิกพัสดุ </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php echo $this->render("_modal_asset"); ?>

    <?php echo $this->render("_form_asset"); ?>



</div> 