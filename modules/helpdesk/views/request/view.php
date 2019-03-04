<?php 
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\DocStateWidget;
use yii\helpers\ArrayHelper;
use app\modules\helpdesk\HelpDesk;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;
use app\modules\helpdesk\models\Request;
use yii\web\View;
use app\components\DocMessageWidget;
use app\modules\helpdesk\assets\RequestAsset;
RequestAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\helpdesk\models\Request */

$this->title = $model->name;
$this->params['homeLink'] = ['label' => 'หน้าหลัก','url' => Url::to('index.php?r=helpdesk/request/index')];
$this->params['breadcrumbs'][] = $this->title;

$userId = Yii::$app->user->identity->id;
$users = \app\modules\resource\models\ResUsers::findOne(['id' => $userId]);

?>

<div ng-app="RequestApp" ng-controller="RequestViewController as ctrl" style="margin-left:20px;margin-right:20px" class="request-view">
    <input type="hidden" id="getId" value="<?php echo $model->id; ?>" />
    <h1>เลขที่เอกสาร : <?= Html::encode($this->title) ?></h1>
    <p class="pull-left">
        <?php if($model->state == Request::WAIT) {
            echo Html::a(Yii::t('app', 'แก้ไข'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary','data' =>['method' => 'post']]); 
            echo Html::a(Yii::t('app', 'ลบ'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'คุณต้องการลบรายการนี้หรือไม่?'),
                        'method' => 'post',
                    ],
                ]);
        }
        ?>
    </p>
    <p class="pull-right">


        <?php if($users->rule_help !== "user"): ?>

        <?php if($model->state == Request::WAIT): ?>
        <a href="" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
            ตรวจสอบ / รับซ่อม
        </a>
        <?php endif; ?>

        
        <?php if($model->state == Request::REPAIR): ?>
        <a href="" class="btn btn-primary" data-toggle="modal" data-target="#myRepair">
            รับซ่อม / อยู่ระหว่างการซ่อม
        </a>
        <?php endif; ?>

        <?php if($model->state == Request::CANCEL): ?>
        <?= Html::a(Yii::t('app','ตั้งเป็นรอรับซ่อม'),['set-wait','id' => $model->id], 
            ['class' => 'btn btn-success',
            'data' => [
                'confirm' => 'คุณต้องการคืนสถานะเป็นรอรับซ่อมหรือไม่ ?',
                'method' => 'post'
            ],
        ]);
        ?>
        <?php endif; ?>

        <?php if($model->state == Request::CLAME || $model->state == Request::BUY): ?>
        <a href="" class="btn btn-primary" data-toggle="modal" data-target="#close">
            ปิดงานซ่อม
        </a>
        <a href="" class="btn btn-danger" data-toggle="modal" data-target="#cancel">
            ยกเลิกซ่อม
        </a>
        <?php endif; ?>

        <?php endif; ?>
        <?Php 
        $searchId = Yii::$app->user->identity->id;
        $doRequest = \app\modules\helpdesk\models\Request::findOne(['id' => $model->id]);
        
        if($searchId == $doRequest->requested_by && $model->state == Request::CLOSE):
        ?>
        <a href="" class="btn btn-primary" data-toggle="modal" data-target="#comment">
            ประเมินความพึงพอใจ
        </a>

        <?php endif; ?>

        <?= Html::a(Yii::t('app','พิมพ์ใบแจ้งซ่อม'), ['print', 'id' => $model->id], ['class' => 'btn btn-success','target' => '_blank']) ?>
    </p>
    <div style="clear: both;">

    <?= DocStateWidget::widget([
        'stateList' => ArrayHelper::map(HelpDesk::reState(), 'id', 'name'),
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
                "value"=> Yii::$app->thaiFormatter->asDate($model->date_create,'php:d/m/Y'),
            ],
            'sn_number:text:หมายเลขคุรภัณฑ์',
            'description:text:ตรวจสอบเบื้องต้น (ระบุสาเหตุ/ปัญหาที่พบ)',
            'brand:text:รุ่น/ยี่ห้อ',
            [
                'attribute'=>'problem',
                'label' => 'แจ้งปัญหา',
                'format' => 'raw',
                'value' => function ($model) {
                    if($model->problem == "computer") {
                        return "ปัญหาคอมพิวเตอร์";
                    } else if($model->problem == "network") {
                        return "ปัญหาเครือข่าย";
                    } else if($model->problem == "printer") {
                        return "ปัญหาปริ้นเตอร์";
                    } else if($model->problem == "other") {
                        return "อื่นๆ".($model->other)?$model->other:"-";
                    }
                }
            ],
            'building:text:บริการแจ้งซ่อมอาคาร',
            'officer:text:บริการงานธุรการ',
            [
                'label' => 'ผู้บันทึกแจ้งซ่อม',
                'format' => 'raw',
                'value' => function ($model) {
                    return ($model->user)?$model->user->firstname. "  " . $model->user->lastname:"-";
                }
            ]
        ]
    ]);
    ?>

            <h4>รายการที่ถูกยกเลิก</h4>
            <?=
                DetailView::widget([
                    'model'=>$model,
                    'attributes' => [
                        [
                            'label' => 'วันที่ยกเลิก',
                            'format' => 'html',
                            'value' => Yii::$app->thaiFormatter->asDate($model->date_cancel, 'php:d/m/Y')
                        ],
                        'note_cancel:text:หมายเหตุยกเลิก',
                        [
                            'attribute' => 'repair_id',
                            'label' => 'ผู้ยกเลิกการซ่อม',
                            'format' => 'raw',
                            'value'=> function($model) {
                                return ($model->cancel)?$model->cancel->firstname. "  " . $model->cancel->lastname:"-";
                            } 
                        ],
                    ]
                ]);
            ?>

            
            <h4>รายการที่ส่งเคลม/จัดซื้ออุปกรณ์</h4>
            <?=
                DetailView::widget([
                    'model'=>$model,
                    'attributes' => [
                        [
                            'label' => 'วันที่ส่งซ่อม/จัดซื้อ',
                            'format' => 'html',
                            'value' => Yii::$app->thaiFormatter->asDate($model->date_clame, 'php:d/m/Y')
                        ],
                        'budget:text:โดยใช้งบ',
                    ]
                ]);
            ?>
    
            
        
    
    </div>
        <div class="col-sm-6">
            <h4>รายการที่อยู่ระหว่างการซ่อม / ซ่อมอาคาร / บริการงานธุรการ</h4>
            <?= 
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'attribute' => 'repair_id',
                            'label' => 'ผู้ดำเนินการซ่อม',
                            'format' => 'raw',
                            'value'=> function($model) {
                                
                                return ($model->repair)?$model->repair->firstname. "  " . $model->repair->lastname:"-";
                              
                            } 
                        ],
                        [
                            'attribute' => 'builder1_id',
                            'label' => 'ผู้ซ่อมอาคาร',
                            'format' => 'raw',
                            'value'=> function($model) {
                                
                                return ($model->builder1)?$model->builder1->firstname. "  " . $model->builder1->lastname:"-";
                              
                            } 
                        ],
                        [
                            'attribute' => 'builder2_id',
                            'label' => 'ผู้ซ่อมอาคาร',
                            'format' => 'raw',
                            'value'=> function($model) {
                                
                                return ($model->builder2)?$model->builder2->firstname. "  " . $model->builder2->lastname:"-";
                              
                            } 
                        ],
                        [
                            'attribute' => 'builder3_id',
                            'label' => 'ผู้ซ่อมอาคาร',
                            'format' => 'raw',
                            'value'=> function($model) {
                                
                                return ($model->builder3)?$model->builder3->firstname. "  " . $model->builder3->lastname:"-";
                              
                            } 
                        ],
                        [
                            'attribute' => 'builder4_id',
                            'label' => 'ผู้ซ่อมอาคาร',
                            'format' => 'raw',
                            'value'=> function($model) {
                                
                                return ($model->builder4)?$model->builder4->firstname. "  " . $model->builder4->lastname:"-";
                              
                            } 
                        ],
                        [
                            'attribute' => 'officer_id',
                            'label' => 'บริการงานธุรการ',
                            'format' => 'raw',
                            'value'=> function($model) {
                                
                                return ($model->officers)?$model->officers->firstname. "  " . $model->officers->lastname:"-";
                              
                            } 
                        ],
                        [
                            "label"=>"วันที่รับแจ้ง",
                            'format'=>'html',
                            "value"=> Yii::$app->thaiFormatter->asDate($model->date_repair,'php:d/m/Y'),
                        ],
                    ]
                ]);
            ?>
        </div>
        <div class="col-sm-6">
            <h4>จบงานซ่อม</h4>
            <?=
                DetailView::widget([
                    'model'=>$model,
                    'attributes' => [
                        [
                            'label' => 'วันที่ปิดงานซ่อม',
                            'format' => 'html',
                            'value' => Yii::$app->thaiFormatter->asDate($model->date_close, 'php:d/m/Y')
                        ],
                        'answer:text:ปัญหา/สาเหตุ',
                        'detail_work:text:รายละเอียดการแก้ปัญหา',
                        'detail_building:text:รายละเอียดบริการซ่อมอาคาร',
                        'detail_officer:text:รายละเอียดบริการงานธุรการ',
                        [
                            'attribute' => 'close_id',
                            'label' => 'ผู้ปิดงานซ่อม',
                            'format' => 'raw',
                            'value'=> function($model) {
                                
                                 return ($model->close)?$model->close->firstname. "  " . $model->close->lastname:"-";
                            } 
                        ],

                    ]
                ]);
            ?>
        </div>
        <div class="col-lg-6">
            <h4>ประเมินความพึงพอใจ</h4>
            <?=
                DetailView::widget([
                    'model'=>$model,
                    'attributes' => [
                        [
                            'label' => 'วันที่ประเมาิน',
                            'format' => 'html',
                            'value' => Yii::$app->thaiFormatter->asDate($model->date_comment, 'php:d/m/Y')
                        ],
                        'comment_detail:text:ข้อเสนอแนะ',
                        [
                            'attribute' => 'comment_state',
                            'label' => 'ประเมินผลโดยผู้ขอใช้บริการ',
                            'format' => 'raw',
                            'value'=> function($model) {
                                if($model->comment_state == "improve") {
                                    return "ต้องปรับปรุง";
                                } else if($model->comment_state == "fair") {
                                    return "พอใช้";
                                } else if($model->comment_state == "medium") {
                                    return "ปานกลาง";
                                } else if($model->comment_state == "good") {
                                    return "ดี";
                                } else if($model->comment_state == "verygood"){
                                    return "ดีมาก";
                                }
                            } 
                        ],
                    ]
                ]);
            ?>
        </div>
        
        
        
    </div>

        <div>
            <h4>ประวัติเอกสาร</h4>
            <?=DocMessageWidget::widget(['model' => $model])?>
        </div>


<?php echo $this->render("_dialogRepair", ['model' => $model]); ?>

<?php echo $this->render("_comment", ['model' => $model]); ?>

<?php echo $this->render('_close', ['model'=>$model]); ?>
<?php echo $this->render('_cancel',['model'=>$model]); ?>

        <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">ตรวจสอบ / รับซ่อม</h4>
      </div>
      <div class="modal-body">
      

         <table class="table table-striped" width="100%" style='padding: 8px'>
	
		<tr>
			<td width='25%' style='padding: 10px'> <strong> ตรวจสอบ / รับซ่อม </strong> </td>
			<td width='85%' style='padding: 10px'>  หัวหน้าสารสนเทศตรวจสอบใบแจ้งซ่อม</td>
		</tr>
		<tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			
			<input type="radio" ng-model="model.state" value="repair" /> ดำเนินการตรวจซ่อม/รับซ่อม
			
		</td>
	</tr>
	<tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			<div class="form-inline">
				<label style="width: 170px"> มอบหมายงานให้เจ้าหน้าที่ (แจ้งปัญหา)</label>
                <select class="form-control" ng-model="model.repair_id">
                    <option ng-repeat="row in use_repair" value="{{ row.id }}"> {{ row.firstname }} &nbsp; {{ row.lastname }} </option>
                </select>
			<div>
		</td>
	</tr>
    <tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			<div class="form-inline">
				<label style="width: 170px"> มอบหมายงานให้เจ้าหน้าที่ (บริการแจ้งซ่อมอาคาร)</label>
                <select class="form-control" ng-model="model.builder1_id">
                    <option ng-repeat="row in use_builder" value="{{ row.id }}"> {{ row.firstname }} &nbsp; {{ row.lastname }} </option>
                </select>
			<div>
            <div class="form-inline">
				<label style="width: 170px"> </label>
                <select class="form-control" ng-model="model.builder2_id">
                    <option ng-repeat="row in use_builder" value="{{ row.id }}"> {{ row.firstname }} &nbsp; {{ row.lastname }} </option>
                </select>
			<div>
            <div class="form-inline">
				<label style="width: 170px"> </label>
                <select class="form-control" ng-model="model.builder3_id">
                    <option ng-repeat="row in use_builder" value="{{ row.id }}"> {{ row.firstname }} &nbsp; {{ row.lastname }} </option>
                </select>
			<div>
            <div class="form-inline">
				<label style="width: 170px"> </label>
                <select class="form-control" ng-model="model.builder4_id">
                    <option ng-repeat="row in use_builder" value="{{ row.id }}"> {{ row.firstname }} &nbsp; {{ row.lastname }} </option>
                </select>
			<div>
		</td>
	</tr>
    <tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			<div class="form-inline">
				<label style="width: 170px"> มอบหมายงานให้เจ้าหน้าที่ (บริการงานธุรการ)</label>
                <select class="form-control" ng-model="model.officer_id">
                    <option ng-repeat="row in use_officer" value="{{ row.id }}"> {{ row.firstname }} &nbsp; {{ row.lastname }} </option>
                </select>
			<div>
		</td>
	</tr>
</table>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
        <button type="button" class="btn btn-primary" ng-click="saveRepair()">บันทึก</button>
      </div>
    </div>
  </div>
</div>



</div>