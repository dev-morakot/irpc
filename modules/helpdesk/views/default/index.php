<?php use yii\helpers\Html; ?>
<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
              
            <p class="centered"><a href=""><?= Html::img($model->getPhotoViewer(),['style'=>'width:60px;','class'=>'img-circle']); ?></a></p>
             
            <h5 class="centered"><?php echo $model->firstname. '  ' . $model->lastname; ?></h5>
            <li class="mt">
               
            </li>
                    
            <li class="sub-menu">
                <a href="index.php?r=helpdesk/request/create" >
                    <i class="glyphicon glyphicon-home"></i>
                          <span>สร้างรายการแจ้งซ่อม</span>
                </a>
            </li>

            <li class="sub-menu">
                <a href="index.php?r=helpdesk/request/index" >
                    <i class="glyphicon glyphicon-cog"></i>
                          <span>รายการแจ้งซ่อม</span>
                </a>
                
            </li>

           
            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="glyphicon glyphicon-list"></i>
                          <span>รายงาน</span>
                </a>
                <ul class="sub">
                    <li><a href="index.php?r=helpdesk/report/report-year">รายงานซ่อมประจำปี</a></li>
                    <li><a href="index.php?r=helpdesk/report/report-month">รายงานซ่อมประจำเดือน</a></li>
                    <li><a href="index.php?r=helpdesk/report/report-service">รายงานบุคคลผู้ให้บริการ</a></li>
                </ul>
            </li>


        </ul>
              <!-- sidebar menu end-->
    </div>
</aside>