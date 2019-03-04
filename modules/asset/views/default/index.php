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
                <a href="javascript:;" >
                    <i class="glyphicon glyphicon-folder-open"></i>
                          <span>ครุภัณฑ์</span>
                </a>
                <ul class="sub">
                    <li><a href="index.php?r=asset/asset/index">ทะเบียนครุภัณฑ์</a></li>
                    <li><a href="index.php?r=asset/asset/add">เบิกจ่ายพัสดุครุภัณฑ์</a></li>
                    <li><a href="index.php?r=asset/return/return">คืนพัสดุครุภัณฑ์</a></li>
                </ul>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="glyphicon glyphicon-book"></i>
                          <span>ตรวจสอบใบเบิก/คืน</span>
                </a>
                <ul class="sub">
                    <li><a href="index.php?r=asset/asset/grid-asset">ตรวจสอบใบเบิก</a></li>
                    <li><a href="index.php?r=asset/return/index">ตรวจสอบใบคืน</a></li>
                    
                </ul>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="glyphicon glyphicon-list"></i>
                          <span>รายงาน</span>
                </a>
                <ul class="sub">
                    <li><a href="index.php?r=asset/report/report-all">สรุปครุภัณฑ์ทั้งหมด</a></li>
                    <li><a href="index.php?r=asset/report/report-category">สรุปตามประเภทสินทรัพย์</a></li>
                    <li><a href="index.php?r=asset/report/report-location">สรุปตามสถานที่ / ห้อง</a></li>
                    <li><a href="index.php?r=asset/report/report-balance">รายงานครุภัณฑ์คงเหลือ</a></li>
                    
                </ul>
            </li>

            
            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="glyphicon glyphicon-list-alt"></i>
                          <span>รายงานเบิกจ่ายครุภัณฑ์</span>
                </a>
                <ul class="sub">
                    
                    <li><a href="index.php?r=asset/report/report-asset-year">เบิกจ่ายแยกประจำปี</a></li>
                    <li><a href="index.php?r=asset/report/report-asset-month">เบิกจ่ายแยกประจำเดือน</a></li>
                    <li><a href="index.php?r=asset/report/report-asset-group">เบิกจ่ายแยกฝ่าย/แผนก</a></li>
                    
                </ul>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="glyphicon glyphicon-calendar"></i>
                          <span>รายงานคืนครุภัณฑ์</span>
                </a>
                <ul class="sub">
                    <li><a href="index.php?r=asset/report/report-return-all">คืนครุภัณฑ์สรุปรวมทั้งหมด</a></li>
                    <li><a href="index.php?r=asset/report/report-return-year">คืนครุภัณฑ์แยกประจำปี</a></li>
                    <li><a href="index.php?r=asset/report/report-return-month">คืนครุภัณฑ์แยกประจำเดือน</a></li>
                    <li><a href="index.php?r=asset/report/report-return-group">คืนครุภัณฑ์แยกฝ่าย/แผนก</a></li>
                    
                </ul>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="glyphicon glyphicon-briefcase"></i>
                          <span>ตั้งค่าระบบ</span>
                </a>
                <ul class="sub">
                    <!--<li><a href="index.php?r=admin/log">System log</a></li>-->
                    <li><a href="index.php?r=asset/categories/index">ประเภททรัพย์สิน</a></li>
                    <li><a href="index.php?r=asset/unit/index">หน่วย</a></li>
                    <li><a href="index.php?r=asset/location/index">สถานที่ / ห้อง</a></li>

                </ul>
            </li>


        </ul>
              <!-- sidebar menu end-->
    </div>
</aside>