<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'แก้ไขแจ้งซ่อม');
$this->params['homeLink'] = ['label'=>'หน้าหลัก','url'=>['/helpdesk/default']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'แจ้งซ่อม'), 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div style="margin-left:20px;margin-right:20px" class="return-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        
    ]) ?>

</div>