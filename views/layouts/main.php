<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\web\View;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php $this->registerJs("
            numeral.language('th');
            bootbox.setDefaults({
                backdrop:true,
                closeButton:true,
                animage:true,
                className:'bic-modal'
            });
            ",View::POS_END)?>
</head>
<body>
    <?php
    if(!isset($this->params['body_container'])){
        $this->params['body_container'] = "container";
    } 
?>
<?php $this->beginBody() ?>
    
<div class="wrap">
    <?php
    
    NavBar::begin([
        'brandLabel' => Yii::$app->params['companyLabel'],
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
        'innerContainerOptions'=>[
            'class'=>'container'
        ]
    ]);
    $menuItems = [
            [
                'label' => 'แจ้งซ่อม',
                'url'=>['/help/default'],
                'items'=>[
                    ['label'=>'หน้าหลัก','url'=>'/resource/default'],
                    '<li class="divider"></li>',
                    ['label' => 'ตรวจสอบ/รับซ่อม', 'url' => '/resource/res-partner']
                ]
            ],
            [
                'label' => 'เบิกพัสดุ-ครุภัณฑ์',
                'url'=>['/asset/default'],
                'items'=>[
                    ['label'=>'ระบบสินค้า','url'=>'/product/default'],
                    '<li class="divider"></li>',
                    ['label' => 'รายการสินค้า', 'url' => '/product/product-product'],
                    ['label' => 'กลุ่มสินค้า', 'url' => '/product/product-category'],
                    ['label' => 'ตั้งค่าหน่วยสินค้า', 'url' => '/product/product-uom'],
                ]
            ],
            [
                'label' => 'ผู้ใช้งาน',
                'url' => ['index.php?r=resource/res-users'],
                'items' => [
                    ['label' => 'ผู้ใช้งานระบบ','url' => 'index.php?r=resource/res-users'],
                    ['label' => 'ฝ่าย/แผนก', 'url' => 'index.php?r=resource/res-group'],
                    '<li class="divider"></li>',
                    ['label' => 'Role', 'url' => 'rbac/role'],
                    ['label' => 'Permission', 'url' => 'rbac/permission'],
                    ['label' => 'Assignment', 'url' => 'rbac/assignment'],
                    ['label' => 'Rule', 'url' => 'rbac/rule'],
                    '<li class="divider"></li>',
                    ['label' => 'System Log', 'url' => 'admin/log'],
                    ['label' => 'User Log', 'url' => 'admin/app-userlog'],
                    ['label' => 'Model Log', 'url' => 'admin/app-model-log'],
                ]
            ]
          
            //['label' => 'About', 'url' => ['/site/about']],
            //['label' => 'Contact', 'url' => ['/site/contact']],
        ];
       
    if(Yii::$app->user->isGuest){
        $menu[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menu[] = [
          'label' => 'Welcome To : ('.Yii::$app->user->identity->firstname.')',
          'items'=>[
             ['label'=>'Profile ('.Yii::$app->user->identity->username.')', 'url'=>['/site/profile']],
             '<li class="divider"></li>',
             [
                 'label' => 'Logout',
                 'url' => ['/site/logout'],
                 'linkOptions' => ['data-method' => 'post']
             ],
          ],
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menu,
    ]);
    NavBar::end();
    ?>
        
    <div class="<?=$this->params['body_container']?>">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'homeLink'=>isset($this->params['homeLink'])? $this->params['homeLink']:false
        ]) ?>
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('warning')): ?>
            <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <?= Yii::$app->session->getFlash('warning') ?>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?=Yii::$app->params['companyLabel']?> <?= date('Y') ?></p>

        <p class="pull-right"><?php Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>