<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\asset\models\Asset */

$this->title = Yii::t('app', 'สร้างทะเบียนครุภัณฑ์');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ทะเบียนครุภัณฑ์'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-asset-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>