<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AssetLocation */

$this->title = 'สร้างสถานที่/ห้อง';
$this->params['breadcrumbs'][] = ['label' => 'สถานที่/ห้อง', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-location-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
