<?php

/* @var $this yii\web\View */

$this->title = 'IRPCT Application';
?>
<div class="site-index">
<?php 
$id = Yii::$app->user->identity->id;
$user = \app\modules\resource\models\ResUsers::findOne(['id' => $id]);
?>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                
            </div>
        </div>
        <div class="row">
            <?php if($user->select_help == 1): ?>
            <div class="col-lg-3">
                <h2>Help Desk</h2>
                <p>ระบบแจ้งซ่อมคอมพิวเตอร์</p>
                <p><a class="btn btn-default" href="index.php?r=helpdesk/request/create">Help Desk &raquo;</a></p>
            </div>
            <?php endif; ?>
            <?php if($user->select_asset == 1): ?>
            <div class="col-lg-3">
                <h2>Asset</h2>
                <p>ระบบเบิกพัสดุ-ครุภัณฑ์</p>
                <p><a class="btn btn-default" href="index.php?r=asset/asset/index">Asset &raquo;</a></p>
            </div>
            <?php endif; ?>
            <?php if($user->select_admin == 1): ?>
            <div class="col-lg-3">
                <h2>Admin</h2>
                <p>การจัดการระบบ</p>
                <p><a class="btn btn-default" href="index.php?r=resource/res-users">Admin</a></p>
            </div>
            <?php endif; ?>
            
        </div>
       
    </div>
</div>