<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\asset\models\Asset;
use app\modules\asset\models\AssetUnit;
use yii\helpers\Url;
use app\modules\asset\models\AssetCategories;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\product\models\ProductProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'รายงานครุภัณฑ์คงเหลือ');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-asset-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  
    $unit_list = AssetUnit::find()->all();
    $unit_filter = \yii\helpers\ArrayHelper::map($unit_list, 'id', 'name');
    $AssetTypeFilter = ArrayHelper::map(AssetCategories::find()->all(), 'id', 'name');
    ?>
   
     <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'name:text:หมายเลขครุภัณฑ์',
            'certificate:text:เลขที่ใบรับรอง',
            'description:text:รายการ',
            [
                'attribute' => 'categories_id',
                'label' => 'ประเภทสินทรัพย์',
                'filter'=> $AssetTypeFilter,
                'value' => function($model) {
                    return ArrayHelper::map(AssetCategories::find()->all(), 'id', 'name')[$model->categories_id];
                },
            ],
        
            [
                    'attribute' => 'qty',
                    'label' => 'จำนวนคงเหลือ',
                    'contentOptions' => ['style' => 'text-align:right'],
                    'headerOptions' => ['class' => 'text-right'],
                   // 'format' => ['decimal',3],
                   
                    'footer' => Asset::getTotal($dataProvider->models, 'qty')
                ],
            [
                'attribute' => 'unit_id',
                'filter'=> $unit_filter,
                'value' => 'unit.name', //from getUserOwner()->username;
                'label' => Yii::t('app', 'หน่วย'),
            ],
           
        ],
    ]);
    ?>
</div>