<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'แจ้งซ่อม');
$this->params['breadcrumbs'][] = ['label' => 'หน้าหลัก', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div style="margin-right: 20px;margin-left: 20px" class="res-users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form') ?>

</div>