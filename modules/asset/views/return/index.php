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

$this->title = Yii::t('app', 'ตรวจสอบใบคืน');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="index">
    <h2><?= Html::encode($this->title) ?></h2>
    <?php $states = ArrayHelper::map(Asset::asState(), 'id', 'name'); ?>
    <div class="pull-right" style="margin-bottom: 2px">

        <div class="btn-group" role="group">
            <a class="btn btn-default btn-sm"
                href="index.php?r=asset/return/index&ReturnOrderSearch%5Bcreate_id%5D=<?=Yii::$app->user->id?>"
                >รายการของฉัน <span class="badge"><?php echo $countMyDoc; ?></span>
            </a>
             <a class="btn btn-default btn-sm"
               href="index.php?r=asset/return/index"> รายการทั้งหมด <span class="badge"><?php echo $countAllDoc; ?></span></a>


            <a class="btn btn-default btn-sm"
               href="index.php?r=asset/return/index&ReturnOrderSearch%5Bstate%5D=wait">
                รออนุมัติ <span class="badge"><?php echo $countWait; ?></span></a>

            <a class="btn btn-default btn-sm"
               href="index.php?r=asset/return/index&ReturnOrderSearch%5Bstate%5D=approved">
                อนุมัติ <span class="badge"><?php echo $countApprover;?></span></a>
            
            <a class="btn btn-default btn-sm"
               href="index.php?r=asset/return/index&ReturnOrderSearch%5Bstate%5D=cancel">
                ยกเลิก <span class="badge"><?php echo $countCancel;?></span></a>
       
        </div>
    
    </div>
    <div class="clearfix"></div>
    <div class="table-responsive">
        <?php Pjax::begin(['id' => 'request_pjax_id']) ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'filterPosition' => GridView::FILTER_POS_BODY,
            'filterRowOptions' => [
                'class' => 'form-group-sm'
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'yii\grid\DataColumn',
                    'contentOptions' => ['class' => 'text-center'],
                    'attribute' => 'name',
                    'format' => 'raw',
                    'label' => 'เลขที่เอกสาร',
                    'value'=> function ($model) {
                        return Html::a($model->name, Url::to(['/asset/return/view', 'id' => $model->id]), 
                        ['data-pjax' => 0]);
                    }
                ],
                'full_name:text:ชื่อผู้ขอคืน',
                'group.name:text:ฝ่าย',
                'group.department:text:แผนก',
                'location.name:text:สถานที/ห้อง',
                'notes:text:รายละเอียด',
                
                [
                    'attribute' => 'create_id',
                    'contentOptions' => ['class' => 'text-center'],
                    'label' => 'ผู้ทำรายการ',
                    'format' => 'html',
                    'value' => function ($model) {
                        return "<small>". $model->create->firstname . "</small>";
                    }
                ],
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
                        return Yii::$app->thaiFormatter->asDate($model->date_order, 'php:d/m/Y');
                    }
                ],
                [
                    'attribute' => 'state',
                    'label' => 'สถานะ',
                    'contentOptions' => ['class' => 'text-center'],
                    'filter' => $states,
                    'format' => 'html',
                    'value' => function ($model) {
                        $state = ArrayHelper::map(Asset::asState(), 'id' ,'name')[$model->state];
                        if($model->state == ReturnOrder::WAIT) {
                            return  "<span class='label label-primary'> รออนุมัติ </span>";
                        } else if($model->state == ReturnOrder::APPROVED) {
                            return "<span class='label label-success'> รออนุมัติ </span>";
                        } else if($model->state == ReturnOrder::CANCEL) {
                            return "<span class='label label-danger'> ยกเลิก </span>";
                        }
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['class' => 'text-center'],
                    'template' => '<div class="btn-group btn-group-sm text-center" role="group"> {view}  {delete} </div>',
                    'header' => 'รายการ',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            $url = "index.php?r=asset/return/view&id=$model->id";
                             return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => 'ดู',
                                       
                                    ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            $url = "index.php?r=asset/return/delete&id=$model->id";
                                if(in_array($model->state, [ReturnOrder::WAIT, ReturnOrder::CANCEL])) {
                                    return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, [
                                        'title' => 'ลบ',
                                        'onclick' => 'confirm("ยืนยันการลบ ?")'
                                    ]);
                                }
                            }
                        
                    ]
                ]
            ]
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>