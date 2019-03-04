<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\asset\models\Asset */

$this->title = Yii::t('app', 'แก้ไข {modelClass}: ', [
    'modelClass' => 'ทะเบียนครุภัณฑ์',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ทะเบียนครุภัณฑ์'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'แก้ไข');
?>
<div class="asset-asset-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        
    ]) ?>

</div>