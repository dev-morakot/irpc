<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

?>
<div class="res-users-view">

    <h1>{{ model.email }}</h1>

    <table class="table table-bordered table-striped" style="margin-top: 10px">
    	
    		<tr>
    			<td>ID</td>
    			<td>{{ model.id }}</td>
    		</tr>
    		<tr>
    			<td>Username</td>
    			<td>{{ model.username }}</td>
    		</tr>
    		<tr>
    			<td>Email</td>
    			<td>{{ model.email }}</td>
    		</tr>
    		<tr>
    			<td>ชื่อ</td>
    			<td>{{ model.firstname }}</td>
    		</tr>
    		<tr>
    			<td>นามสกุล</td>
    			<td>{{ model.lastname }}</td>
    		</tr>
    		<tr>
    			<td>Active</td>
    			<td ng-if="model.active === '1'"><span class="glyphicon glyphicon-ok" style="font-size: 15px;color: green"></span></td>
    			<td ng-if="model.active === null || model.active === '0'"><span class="glyphicon glyphicon-remove" style="font-size: 15px;color: red"></span></td>
    		</tr>
    		<tr>
    			<td>ภาพประจำตัว</td>
    			<td><img ng-src="../web/img_com/{{ model.img }}" style="width: 150px"></td>
    		</tr>
    </table>

    <hr />

    <h4>ผ่าย/แผนก</h4>
    <table class="table table-bordered table-striped" style="margin-top: 10px">
    	<thead>
    		<tr>
    			<th>#</th>
    			<th>ฝ่าย</th>
    			<th>แผนก</th>
    		</tr>
    	</thead>
    	<tbody>
    		<tr ng-repeat="line in Provider">
    			<td>{{ $index + 1 }}</td>
    			<td>{{ line.name}}</td>
    			<td>{{ line.department }}</td>
    		</tr>
    	</tbody>
    </table>
</div>