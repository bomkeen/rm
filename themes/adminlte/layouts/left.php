<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\web\Session;

$session = \yii::$app->session;
?>
<aside class="main-sidebar">

    <section class="sidebar">



        <?=
        Nav::widget(
                [
                    'encodeLabels' => false,
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        
                        ['label' => '<i class="fa fa-home "></i><span>Home</span>', 'url' => ['/site/index']],
                        ['label' => '<i class="fa fa-pencil-square  "></i><span>รายงานความเสี่ยง</span>', 'url' => ['/risk/info']],
                        ['label' => '<i class="fa fa-pencil-square-o"></i><span >แก้ไขความเสี่ยง</span>', 'url' => ['/risk/edit'],],
                        ['label' => '<i class="fa fa-pencil-square-o"></i><span >ปรับปรุงความเสี่ยง</span>', 'url' => ['/report/review'],'visible' => $session['level']>1],
                        ['label' => '<i class="fa fa-bar-chart"></i><span>ภาพรวม</span>', 'url' => ['/site/im'], 'visible' =>$session['level'] > 3],
    ['label' => '<i class="fa fa-bar-chart"></i><span>รายงานสรุปหน่วยงาน</span>', 'url' => ['/site/imdep'],'visible' =>$session['level'] > 1],                    
                        Yii::$app->user->isGuest ?
                                ['label' => '<i class="fa fa-user"></i><span>เข้าสู่ระบบ</span>', 'url' => ['/user/security/login']] :
                                ['label' => '<i class="fa fa-hand-o-right"></i>' . $session['fullname']],
                                ['label' => '<i class="fa fa-user-times"></i><span>ออกจากระบบ</span>', 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post'],'visible' => !Yii::$app->user->isGuest],
                            
                        ['label' => '<i class="fa fa-user-plus"></i><span>ลงทะเบียน</span>', 'url' => ['/user/registration/register'],'visible' => Yii::$app->user->isGuest],
                    ],
                ]
        );
        ?>
<ul class="sidebar-menu">
     <?php if(!Yii::$app->user->isGuest){?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-tachometer"></i> <span> ระบบรายงาน</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= \yii\helpers\Url::to(['/report/userreport']) ?>"><span class="fa fa-bar-chart"></span> จำนวนความเสี่ยงรายบุคล/บุคคลในหน่วยงาน</a>
                    </li>
                    <?php if ($session['th']==1){?>
                    <li>
                        <a href="<?= \yii\helpers\Url::to(['/report/teamreport']) ?>"><span class="fa fa-bar-chart"></span> จำนวนความเสี่ยงรายบุคล/บุคคลในทีม</a>
                    </li>
                    <?php } ?>
                    <?php if ($session['level']>2) { ?>
                    <li><a href="<?= \yii\helpers\Url::to(['/report/sumdep']) ?>"><span class="fa fa-bar-chart"></span> สรุปจำนวนความเสี่ยงแยกตามหน่วยงาน</a>
                    </li>
                    <li><a href="<?= \yii\helpers\Url::to(['/report/sumteam']) ?>"><span class="fa fa-bar-chart"></span> สรุปจำนวนความเสี่ยงตามทีมคล่อมสายงาน</a>
                    </li>
                    <li><a href="<?= \yii\helpers\Url::to(['/report/matrixall']) ?>"><span class="fa fa-bar-chart"></span> รายงาน matrix ภาพรวม</a>
                    </li>
                     <li><a href="<?= \yii\helpers\Url::to(['/report/matrixdep']) ?>"><span class="fa fa-bar-chart"></span> รายงาน matrix หน่วยงาน</a>
                    </li>
                     <li><a href="<?= \yii\helpers\Url::to(['/report/matrixteam']) ?>"><span class="fa fa-bar-chart"></span> รายงาน matrix ทีม</a>
                    </li>
                     <li><a href="<?= \yii\helpers\Url::to(['/site/top5sub']) ?>"><span class="fa fa-bar-chart"></span> Top 5</a>
                    </li>
                     <li><a href="<?= \yii\helpers\Url::to(['/report/tablesum']) ?>"><span class="fa fa-bar-chart"></span> ตารางสรุป</a>
                    </li>
                    <?php  } ?>
                </ul>
            </li>
    <?php }?>
    <?php if($session['level'] == 5){?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cogs"></i> <span>ข้อมูลพื้นฐานของระบบ</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= \yii\helpers\Url::to(['/dep']) ?>"><span class="fa fa-cog"></span> รหัสหน่วยงาน</a>
                    </li>
                    <li><a href="<?= \yii\helpers\Url::to(['/team']) ?>"><span class="fa fa-cog"></span> ทีมคล่อมสายงาน</a>
                    </li><li><a href="<?= \yii\helpers\Url::to(['/group']) ?>"><span class="fa fa-cog"></span> กลุ่มหน่วยงาน</a>
                    </li>
                    <li><a href="<?= \yii\helpers\Url::to(['/source']) ?>"><span class="fa fa-cog"></span> ที่มาของข้อมูล</a>
                    </li>
                    <li><a href="<?= \yii\helpers\Url::to(['/user/admin/index']) ?>"><span class="fa fa-cog"></span> จัดการ User</a>
                    </li>
                </ul>
            </li>
    <?php }?>
        

</ul>
</section>

</aside>
