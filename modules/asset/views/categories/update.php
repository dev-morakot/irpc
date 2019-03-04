<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AssetCategories */

$this->title = 'แก้ไขประเภททรัพย์สิน: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'ประเภททรัพย์สิน', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="asset-categories-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
