<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\modules\asset\models\AssetUnit;
use app\modules\asset\models\AssetCategories;
use app\modules\asset\Asset;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\Asset */
/* @var $form yii\widgets\ActiveForm */
$CategoryOptions = ArrayHelper::map(AssetCategories::find()->all(), 'id', 'name');
$UnitOptions = ArrayHelper::map(AssetUnit::find()->all(),'id','name');
?>
<div class="asset-asset-form">

    <?php $form = ActiveForm::begin(); ?>
     <?= $form->field($model,'categories_id')
        ->dropDownList($CategoryOptions,['prompt'=>'-']) ?>
     <?= $form->field($model, 'certificate')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'class'=>'form-control bic-required-field']) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true,'class'=>'form-control bic-required-field']) ?>
    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'qty')->textInput(['maxlength' => true,'class'=>'form-control bic-required-field']) ?>
    <?= $form->field($model,'unit_id')
        ->dropDownList($UnitOptions,['prompt'=>'-']) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>