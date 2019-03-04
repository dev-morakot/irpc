<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AppUserlog */

$this->title = Yii::t('app', 'Create App Userlog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'App Userlogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div style="margin-right: 20px;margin-left: 20px;" class="app-userlog-create">

    <h1 style="margin-left: 20px;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>