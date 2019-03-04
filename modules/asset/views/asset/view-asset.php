<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\DocStateWidget;
use yii\helpers\ArrayHelper;
use app\modules\asset\Asset;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;
use app\modules\asset\models\AssetOrder;
use yii\web\View;
use app\components\DocMessageWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\purchase\models\PurchaseOrder */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ตรวจสอบใบเบิก'), 'url' => ['grid-asset']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view-asset">
    <h1>เลขที่เอกสาร : <?= Html::encode($this->title) ?></h1>
    <p class="pull-left">
        <?php if($model->state == AssetOrder::WAIT) {
            echo Html::a(Yii::t('app', 'ลบ'), ['delete-asset', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'คุณต้องการลบรายการนี้หรือไม่?'),
                        'method' => 'post',
                    ],
                ]);
        }
        ?>
    </p>
    <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
    <p class="pull-right">

        <?php if($model->state == AssetOrder::WAIT): ?>
        <?= Html::a(Yii::t('app','อนุมัติ'),['approved','id' => $model->id], 
            ['class' => 'btn btn-primary',
            'data' => [
                'confirm' => 'ยืนยันอนุมัติใบเบิก ?',
                'method' => 'post'
            ],
        ]);
        ?>
        <?= Html::a(Yii::t('app','ยกเลิก'),['cancel','id' => $model->id], 
            ['class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'ยกเลิกใบเบิก ?',
                'method' => 'post'
            ],
        ]);
        ?>
        <?php endif; ?>
        <?php if($model->state == AssetOrder::APPROVED): ?>
            <?= Html::a(Yii::t('app','พิมพ์ใบเบิก'), ['print', 'id' => $model->id], ['class' => 'btn btn-success','target' => '_blank']) ?>
        <?php endif; ?>
    </p>
    <div style="clear: both;">
    <?= DocStateWidget::widget([
        'stateList' => ArrayHelper::map(Asset::asState(), 'id', 'name'),
        'currentState' => $model->state
        ])
    ?>
    <div class="row">
        <div class="col-sm-6">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                     'name:text:เลขที่เอกสาร',
                    [
                        "label"=>"วันที่บันทึก",
                        'format'=>'html',
                        "value"=> Yii::$app->thaiFormatter->asDate($model->date_order,'php:d/m/Y'),
                    ],
                    'full_name:text:ชื่อผู้ขอเบิก',
                    'group.name:text:ฝ่าย',
                    'group.department:text:แผนก',
                    'location.name:text:สถานที่/ห้อง',
                    'notes:text:รายละเอียด',
                    [
                        'label' => 'ผู้ทำรายการ',
                        'format' => 'html',
                        'value' => function ($model) {
                            return $model->create->firstname."    " . $model->create->lastname;
                        }
                    ]
                ]
            ])
            ?>
        </div>
        <div class="col-sm-6">
            <h4>อนุมัติรายการ</h4>
            <?= 
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        "label"=>"วันที่อนุมัติ",
                        'format'=>'html',
                        "value"=> Yii::$app->thaiFormatter->asDate($model->date_approve,'php:d/m/Y'),
                    ],
                    [
                        'label' => 'ผู้อนุมัติ',
                        'format' => 'html',
                        'value' => function ($model) {
                            return ($model->approve)?$model->approve->firstname. "  " . $model->approve->lastname:"-";
                        }
                    ]
                ]
            ])
            ?>

            <h4>ยกเลิกรายการ</h4>
            <?= 
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        "label"=>"วันที่ยกเลิก",
                        'format'=>'html',
                        "value"=> Yii::$app->thaiFormatter->asDate($model->date_cancel,'php:d/m/Y'),
                    ],
                    [
                        'label' => 'ผู้ยกเลิก',
                        'format' => 'html',
                        'value' => function ($model) {
                            return ($model->cancel)?$model->cancel->firstname. "  " . $model->cancel->lastname:"-";
                        }
                    ]
                ]
            ])
            ?>
        </div>
        <div class="col-sm-12">
        <h4>รายการใบเบิกพัสดุ-ครุภัณฑ์:</h4>
                <?= GridView::widget([
                    'dataProvider' => $detailDataProvider,
                    'columns' => [
                        'id',
                        'asset.certificate:text:เลขทีใบรับรอง',
                        'asset.name:text:หมายเลขครุภัณฑ์',
                        'asset.description:text:รายการ',
                        'asset.category.name:text:ประเภทสินทรัพย์',
                        'qty:decimal:จำนวน',
                        'asset.unit.name:text:หน่วย'

                    ],
                ]); ?>
            </div>
    </div>
    <div>
        <h4>ประวัติเอกสาร</h4>
        <?=DocMessageWidget::widget(['model' => $model])?>
    </div>
</div>