<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\components\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use app\modules\asset\models\ReturnOrder;
use app\modules\asset\Asset;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\product\models\ProductProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'คืนครุภัณฑ์สรุปรวมทั้งหมด');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-all">
    <h2><?= Html::encode("จ่ายคืนแล้ว") ?></h2>
    <?php $states = ArrayHelper::map(Asset::asState(), 'id', 'name'); ?>

    <div class="table-responsive">
        <?php Pjax::begin(['id' => 'request_pjax_id']) ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'filterPosition' => GridView::FILTER_POS_BODY,
            'filterRowOptions' => [
                'class' => 'form-group-sm'
            ],
            'showFooter' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'yii\grid\DataColumn',
                    'contentOptions' => ['class' => 'text-center'],
                    'attribute' => 'name',
                    'format' => 'raw',
                    'label' => 'เลขที่เอกสาร',
                   
                ],
                'full_name:text:ชื่อผู้ขอคืน',
                'group_name:text:ฝ่าย',
                'department:text:แผนก',
                'location_name:text:สถานที/ห้อง',
                'as_name:text:หมายเลขครุภัณฑ์',
                'description:text:รายการ',
                'category_name:text:ประเภทสินทรัพย์',
                [
                    'attribute' => 'qty',
                    'label' => 'จำนวน',
                    'contentOptions' => ['style' => 'text-align:right'],
                    'headerOptions' => ['class' => 'text-right'],
                   // 'format' => ['decimal',3],
                   
                    'footer' => ReturnOrder::getTotal($dataProvider->models, 'qty')
                ],
                'unit_name:text:หน่วย',
                [
                    'attribute' => 'date_approve',
                    'contentOptions' => ['class' => 'text-center'],
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'date_approve',
                        'size' => 'sm',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pickerButton' => false,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ],
                    ]),
                    'format' => 'html',
                    'value' => function ($model) {
                        return Yii::$app->thaiFormatter->asDate($model['date_approve'], 'php:d/m/Y');
                    }
                ],
               
            ]
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>

    <hr />

    <h2><?= Html::encode("ยังไม่ได้จ่ายคืน") ?></h2>
    <?php $states = ArrayHelper::map(Asset::asState(), 'id', 'name'); ?>

    <div class="table-responsive">
        <?php Pjax::begin(['id' => 'request_pjax_id']) ?>
        <?= GridView::widget([
            'dataProvider' => $gridData,
            'filterModel' => $searchModelReturn,
            'filterPosition' => GridView::FILTER_POS_BODY,
            'filterRowOptions' => [
                'class' => 'form-group-sm'
            ],
            'showFooter' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'yii\grid\DataColumn',
                    'contentOptions' => ['class' => 'text-center'],
                    'attribute' => 'name',
                    'format' => 'raw',
                    'label' => 'เลขที่เอกสาร',
                   
                ],
                'full_name:text:ชื่อผู้ขอคืน',
                'group_name:text:ฝ่าย',
                'department:text:แผนก',
                'location_name:text:สถานที/ห้อง',
                'as_name:text:หมายเลขครุภัณฑ์',
                'description:text:รายการ',
                'category_name:text:ประเภทสินทรัพย์',
                [
                    'attribute' => 'qty',
                    'label' => 'จำนวน',
                    'contentOptions' => ['style' => 'text-align:right'],
                    'headerOptions' => ['class' => 'text-right'],
                   // 'format' => ['decimal',3],
                   
                    'footer' => ReturnOrder::getTotal($gridData->models, 'qty')
                ],
                'unit_name:text:หน่วย',
                [
                    'attribute' => 'date_order',
                    'contentOptions' => ['class' => 'text-center'],
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'date_order',
                        'size' => 'sm',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pickerButton' => false,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ],
                    ]),
                    'format' => 'html',
                    'value' => function ($model) {
                        return Yii::$app->thaiFormatter->asDate($model['date_order'], 'php:d/m/Y');
                    }
                ],
               
            ]
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>