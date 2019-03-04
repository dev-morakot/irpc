<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResGroup */

$this->title = Yii::t('app', 'สร้างฝ่าย/แผนก');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'กลุ่มผู้ใช้งาน'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="res-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>