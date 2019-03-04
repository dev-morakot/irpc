<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\modules\helpdesk\assets\DefaultAsset;
use yii\web\View;
use app\modules\resource\models\ResUsers;


DefaultAsset::register($this);
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
    <?php /*$this->registerJs("
            numeral.language('th');
            bootbox.setDefaults({
                backdrop:true,
                closeButton:true,
                animage:true,
                className:'bic-modal'
            });
            ",View::POS_END)*/?>
</head>
<body>
    <?php
    if(!isset($this->params['body_container'])){
        $this->params['body_container'] = "container";
    } 
?>
<?php $this->beginBody() ?>

<section id="container" >
      <!--header start-->
      <header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <?php $url = ['link' => "/irpc3/web/"]; ?>
            <a href="<?= $url['link']; ?>" class="logo"><b>IRPCT<span>GROUP</span></b></a>


            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                
            </div>
            <?php if(Yii::$app->user->isGuest){ ?>
            <div class="top-menu">
                <ul class="nav pull-right top-menu">
                    <li><?= Html::a('Login', ['/site/login'],['class' =>'logout']) ?></li>
                </ul>
            </div>
            <?php } else { ?>
            <div class="top-menu">
                <ul class="nav pull-right top-menu">
                    <li><a href="index.php?r=site/logout" data-method="post" class="logout"> Logout</a></li>
                </ul>
            </div>
            <?php } ?>
        </header>
      <!--header end-->

      <!--sidebar start-->
      
      <?php $id = Yii::$app->user->identity->id;
      $model = ResUsers::findOne(['id' =>$id]); ?>
      <?php echo $this->render('/default/index',['model' => $model]); ?>
      <!--sidebar end-->

        <!--main content start-->
        <section id="main-content">
            <section class="wrapper site-min-height">
                <div style="margin-top: 15px"> 
                  <?= Breadcrumbs::widget([
                  'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                  'homeLink'=>isset($this->params['homeLink'])? $this->params['homeLink']:false
                ]) ?>
       
                </div>
                <div class="row mt">
                    <div class="col-md-12">
                        <div class="content-panel" style="padding: 20px">
                        <?= $content ?>
                        </div>
                    </div>
                </div>
            
            </section> <!--/wrapper -->
        </section><!-- /MAIN CONTENT -->

       

      <!--footer start-->
    <footer class="site-footer">
        <div class="container">
        <p class="pull-left">&copy; <?=Yii::$app->params['companyLabel']?> <?= date('Y') ?></p>

        <p class="pull-right"><?php Yii::powered() ?></p>
        </div>
    </footer>
      <!--footer end-->
</section>
    




<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>