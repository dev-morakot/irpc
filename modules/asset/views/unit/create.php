<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AssetUnit */

$this->title = 'สร้างหน่วย';
$this->params['breadcrumbs'][] = ['label' => 'หน่วย', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-unit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
