<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="res-users-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>
  
     <div class="row">
      <div class="col-md-2">
        <div class="well text-center">
          <?= Html::img($model->getPhotoViewer(),['style'=>'width:100px;','class'=>'img-rounded']); ?>
        </div>
      </div>
      <div class="col-md-10">
            <?= $form->field($model, 'img')->fileInput()->label('รูปภาพประจำตัว') ?>
      </div>
    </div>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true])->label('ชื่อ') ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true])->label('นามสกุล') ?>
    <?= $form->field($model, 'tel')->textInput(['maxlength' => true])->label('เบอร์โทรศัพท์') ?>

    <?= $form->field($model, 'active')->radioList([1 => 'อนุมัติ', 0 => 'ไม่อนุญาติ'])->label("ใช้งานระบบ") ?>

    


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>