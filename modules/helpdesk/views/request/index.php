<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\components\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use app\modules\helpdesk\models\Request;
use app\modules\helpdesk\HelpDesk;

$this->title = Yii::t('app', 'รายการแจ้งซ่อมคอมพิวเตอร์');
$this->params['breadcrumbs'][] = $this->title;
$id = Yii::$app->user->identity->id;
$user = \app\modules\resource\models\ResUsers::findOne(['id' => $id]);
$this->registerCss('
    .my-table tr th {
      padding: 8px;
    }
    
    .my-table tr td {
      padding: 1px;
    }
   
  ');
?>
<div class="request-index">
    <h2><?= Html::encode($this->title) ?></h2>
    <?= Html::a('สร้างรายการแจ้งซ่อม', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    <div class="pull-right" style="margin-bottom: 2px">

        <div class="btn-group" role="group">
            <a class="btn btn-default btn-sm"
                href="index.php?r=helpdesk/request/index&RequestSearch%5Brequested_by%5D=<?=Yii::$app->user->id?>"
                >รายการของฉัน <span class="badge"><?=$countMyDoc?></span>
            </a>

            
             <a class="btn btn-default btn-sm"
               href="index.php?r=helpdesk/request/index">รายการทั้งหมด <span class="badge"><?=$countAllDoc?></span></a>
            
            <?php if($user->rule_help !== "user"): ?>
            <a class="btn btn-default btn-sm"
               href="index.php?r=helpdesk/request/index&RequestSearch%5Bstate%5D=repair">
                รับซ่อมแล้ว <span class="badge"><?=$countRepair?></span></a>
            

            <a class="btn btn-default btn-sm"
               href="index.php?r=helpdesk/request/index&RequestSearch%5Bstate%5D=close">
                รอจบงาน <span class="badge"><?=$countClose?></span></a>

            <a class="btn btn-default btn-sm"
               href="index.php?r=helpdesk/request/index&RequestSearch%5Bstate%5D=endjob">
                จบงาน <span class="badge"><?=$countEnd?></span></a>

            <a class="btn btn-default btn-sm"
               href="index.php?r=helpdesk/request/index&RequestSearch%5Bstate%5D=clame">
                ส่งซ่อม/เคลม <span class="badge"><?=$countClame?></span></a>
            <a class="btn btn-default btn-sm"
               href="index.php?r=helpdesk/request/index&RequestSearch%5Bstate%5D=buy">
                จัดซื้ออุปกรณ์ <span class="badge"><?=$countBuy?></span></a>
            
            <a class="btn btn-default btn-sm"
               href="index.php?r=helpdesk/request/index&RequestSearch%5Bstate%5D=cancel">
                ยกเลิก <span class="badge"><?=$countCancel?></span></a>
            <?php endif; ?>
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
                        return Html::a($model->name, Url::to(['/helpdesk/request/view', 'id' => $model->id]), 
                        ['data-pjax' => 0]);
                    }
                ],
                'sn_number:text:หมายเลขคุรภัณฑ์',
                'description:text:ตรวจสอบเบื้องต้น',
                'brand:text:รุ่น/ยี่ห่อ',
                [
                    'attribute' => 'problem',
                    'label' => 'แจ้งปัญหา',
                    'format' => 'html',
                    'value' => function ($model) {
                        if($model->problem == "computer") {
                            return 'ปัญหาคอมพิวเตอร์';
                        }
                        if($model->problem == "network") {
                            return 'ปัญหาเครือข่าย';
                        }
                        if($model->problem == "printer") {
                            return "ปัญหาปริ้นเตอร์";
                        }
                        if($model->problem == "other") {
                            return 'อื่นๆ';
                        }
                    }
                ],
                'building:text:บริการแจ้งซ่อมอาคาร',
                'officer:text:บริการงานธุรการ',
                [
                    'attribute' => 'user',
                    'contentOptions' => ['class' => 'text-center'],
                    'label' => 'แจ้งซ่อมโดย',
                    'format' => 'html',
                    'value' => function ($model) {
                        return "<small>". $model->user->firstname . "</small>";
                    }
                ],
                [
                    'attribute' => 'date_create',
                    'contentOptions' => ['class' => 'text-center'],
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'date_create',
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
                        return Yii::$app->thaiFormatter->asDate($model->date_create, 'php:d/m/Y');
                    }
                ],
                [
                    'attribute' => 'state',
                    'label' => 'สถานะ',
                    'contentOptions' => ['class' => 'text-center'],
                    'filter' => $states,
                    'format' => 'html',
                    'value' => function ($model) {
                        $state = ArrayHelper::map(HelpDesk::reState(), 'id' ,'name')[$model->state];
                        if($model->state == Request::WAIT) {
                            return 'รอรับซ่อม';
                        } else if($model->state == Request::REPAIR) {
                            return 'รับซ่อมแล้ว';
                        } else if($model->state == Request::CLOSE) {
                            return 'รอจบงานซ่อม';
                        } else if($model->state == Request::ENDJOB) { 
                            return "จบงานซ่อม";
                        } else if($model->state == Request::CLAME) {
                            return 'ส่งเคลม';
                        } else if($model->state == Request::BUY) {
                            return 'จัดซื้ออุปกรณ์';
                        } else if($model->state == Request::CANCEL) {
                            return 'ยกเลิก';
                        }
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['class' => 'text-center'],
                    'template' => '<div class="btn-group btn-group-sm text-center" role="group"> {view} {update} {delete} </div>',
                    'header' => 'รายการ',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            if($model->state == Request::WAIT) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'title' => 'update',
                                    'data-pjax' => 0
                                ]);
                            }
                        },
                        'delete' => function ($url, $model, $key) {
                                if(in_array($model->state, [Request::WAIT, Request::CANCEL])) {
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