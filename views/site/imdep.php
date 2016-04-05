<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;
use rmrevin\yii\fontawesome\FA;
use app\models\Dep;
use app\models\Level;
use yii\helpers\ArrayHelper;
rmrevin\yii\fontawesome\AssetBundle::register($this);
?>

<div class="row">
    <?php $session = Yii::$app->session;
        //$d = $session['dep'];
        $g2= Dep::findOne(['dep_id'=>$dep]);
        $l=$session['level'];
        $ln=  Level::findOne(['level_id'=>$l]);
    ?>
    <div class="col-md-8">
        <h3> <div  class="label label-warning">ข้อมูลของหน่วยงาน <?php echo $g2->dep_name;?> เข้าดูในฐานะ<?php echo $ln->level_name;?></div></h3>    
         <form method="POST" class="form-inline">   
            
            <?php
        
        $d = $session['dep'];
        if ($session['level'] == 2) {
                $list = yii\helpers\ArrayHelper::map(dep::find()->where(['dep_id' => $d])->all(), 'dep_id', 'dep_name');
        }
         else if ($session['level'] > 2)
        {
            $d = $session['dep'];
        $g = Dep::find()->select('group_id')->where(['dep_id' => $d]);
        $list = yii\helpers\ArrayHelper::map(dep::find()->where(['group_id' => $g])->all(), 'dep_id', 'dep_name');
        }
 else {
            return;
     
 }
         
        echo yii\helpers\Html::dropDownList('dep', $dep, $list, [
                    'prompt' => 'เลือกหน่วยงาน',
                    'class' => 'form-control',
                    
                    
                ]);
                ?>
        <button class='btn btn-primary'>ประมวลผล</button>
   
    </form>
    
    
    </div>
    <div class="col-md-4">
        <h4><?php  echo FA::icon('fa fa-exclamation-circle')->rotate(FA::ROTATE_180);?>&nbsp;<div class="label label-success">ความเสี่ยงทั้งหมด <?=$noti1[0];?> ความเสี่ยง </div></h4>
        <h4><?php  echo FA::icon('fa fa-clock-o')->rotate(FA::ROTATE_180);?>&nbsp;<div class="label label-info">ความเสี่ยงในวัน <?=$noti3[0];?> ความเสี่ยง </div></h4>
        <h4><?php  echo FA::icon('fa fa-refresh fa-spin')->rotate(FA::ROTATE_90);?>&nbsp;<div class="label label-danger">ความเสี่ยงที่ไม่ได้แก้ไข <?=$noti2[0];?> ความเสี่ยง </div></h4>
        
    </div>
</div>
<div class="row">
     
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">ความเสี่ยงตาม Matrix</div>
            <div class="panel-body">
        <?php
                foreach ($q1 as $a1) {
         ?>
<div class="label custom_class" style="background-color:#<?=$a1['color'];?>">&nbsp;<?=$a1['cname'];?>&nbsp;  <?=$a1['mn'];?> เรื่อง </div>&nbsp;
<?php
        }
        ?>
            </div>
        </div>
    </div>

    
    <div class="col-md-6 ">
        <?=
        \yii2fullcalendar\yii2fullcalendar::widget(array(
            'events' => $events,
        ));
        ?>
    </div>
</div>