<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AssetUnit */

$this->title = 'แก้ไขหน่วย: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'หน่วย', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="asset-unit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
