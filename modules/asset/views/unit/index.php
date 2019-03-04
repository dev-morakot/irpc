<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AssetUnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'หน่วย';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-unit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('สร้างหน่วย', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name:text:ชื่อ',
           // 'create_uid',
            'create_date:text:วันที่บันทึก',
           // 'write_uid',
            // 'write_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
