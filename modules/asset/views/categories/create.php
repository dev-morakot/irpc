<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AssetCategories */

$this->title = 'สร้างประเภททรัพย์สิน';
$this->params['breadcrumbs'][] = ['label' => 'ประเภททรัพย์สิน', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
