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
                <a href="index.php?r=resource/res-users" >
                    <i class="glyphicon glyphicon-user"></i>
                          <span>ผู้ใช้งานระบบ</span>
                </a>
                
            </li>

            <li class="sub-menu">
                <a href="index.php?r=resource/res-group" >
                    <i class="glyphicon glyphicon-flag"></i>
                          <span>กลุ่มผู้ใช้งานระบบ</span>
                </a>
            </li>


            <!--<li class="sub-menu">
                <a href="javascript:;" >
                    <i class="glyphicon glyphicon-cog"></i>
                          <span>สิทธิ์การใช้ระบบ</span>
                </a>
                <ul class="sub">
                    <li><a href="index.php?r=rbac/role">Role</a></li>
                    <li><a href="index.php?r=rbac/permission">Permission (สิทธิ์การใช้ระบบ)</a></li>
                    <li><a href="index.php?r=rbac/assignment">Assignment (เงื่อนไขผู้ใชักับกลุ่มผู้ใช้)</a></li>
                    <li><a href="index.php?r=rbac/rule">Rule</a></li>
                </ul>
            </li>-->

            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="glyphicon glyphicon-time"></i>
                          <span>Logs</span>
                </a>
                <ul class="sub">
                  <!--  <li><a href="index.php?r=admin/log">System log</a></li> -->
                    <li><a href="index.php?r=admin/app-userlog">User log</a></li>
                    <li><a href="index.php?r=admin/app-model-log">Model log</a></li>
                </ul>
            </li>


        </ul>
              <!-- sidebar menu end-->
    </div>
</aside>