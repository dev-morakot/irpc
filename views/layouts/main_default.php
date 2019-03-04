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

<section id="container" >
      <!--header start-->
      <header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <a href="index.html" class="logo"><b>DASH<span>IO</span></b></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                
            </div>
            <div class="top-menu">
                <ul class="nav pull-right top-menu">
                    <li><a class="logout" href="/site/logout">Logout</a></li>
                </ul>
            </div>
        </header>
      <!--header end-->

      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
              
                  <p class="centered"><a href="profile.html"><img src="assets/img/ui-sam.jpg" class="img-circle" width="80"></a></p>
                  <h5 class="centered">Sam Soffes</h5>
                    
                  <li class="mt">
                      <a href="index.html">
                          <i class="glyphicon glyphicon-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>

                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="glyphicon glyphicon-desktop"></i>
                          <span>UI Elements</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="general.html">General</a></li>
                          <li><a  href="buttons.html">Buttons</a></li>
                          <li><a  href="panels.html">Panels</a></li>
                          <li><a  href="font_awesome.html">Font Awesome</a></li>
                      </ul>
                  </li>
              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->

        <!--main content start-->
        <section id="main-content">
            <section class="wrapper site-min-height">
                <h3><i class="fa fa-angle-right"></i> Blank Page</h3>
                <div class="row mt">
                  
                <?= $content ?>
  
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