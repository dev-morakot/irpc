<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\asset\models\Asset */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ทะเบียนครุภัณฑ์'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-asset-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <input type="hidden" id="myId" value="<?= $model->id ?>">
    <p>
        <div class="pull-left">
        <?= Html::a(Yii::t('app', 'แก้ไข'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'ลบ'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
        </div>
        
        <div class="clearfix"></div>
    </p>
    <p>
        <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'category.name:text:ประเภทสินทรัพย์',
                    'certificate:text:เลขที่ใบรับรอง',
                    'name:text:หมายเลขครุภัณฑ์',
                    'description:text:รายการ',
                    'notes:text:หมายเหตุ',
                    'qty:text:จำนวน',
                    'unit.name:text:หน่วย',
                    
                ],
            ])
            ?>
    </p>
</div>