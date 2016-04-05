<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;
use rmrevin\yii\fontawesome\FA;
use app\models\Dep;
use app\models\Level;
use app\models\Prorisk;
use yii\helpers\ArrayHelper;
rmrevin\yii\fontawesome\AssetBundle::register($this);
?>


    <?php $session = Yii::$app->session;
        $d = $session['dep'];
        $g2= Dep::findOne(['dep_id'=>$d]);
        $l=$session['level'];
        $ln=  Level::findOne(['level_id'=>$l]);
        $list=  Prorisk::find()->all();
    ?>
 
<div class="row">
     
    <div class="col-md-5">
        <div class="panel panel-info">
            <div class="panel-heading">ความเสี่ยงตาม Matrix</div>
            <div class="panel-body">
        <ul class="list-group">
                <?php
                foreach ($list as $l) {
         ?>

                
   
        <a href="<?= \yii\helpers\Url::to(['/report/top5sub','pro_risk_id'=>$l['pro_risk_id']]) ?>">
         <li class="list-group-item fa fa-share" ><?php echo $l['pro_risk_name']; ?></li>
        </a>
    
             
        <?php
        }
        ?>
            </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-5 col-md-offset-1">
    <div class="panel panel-warning">
        <div class="panel panel-heading">test</div>
        <div class="panel panel-body">
           <?php
if (isset($dataProvider))
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '-'],
        'responsive' => TRUE,
        'showPageSummary' => true,
        'hover' => true,
        'floatHeader' => true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i></h3>',
            'before' => '',
            'type' => \kartik\grid\GridView::TYPE_SUCCESS,
            
        ],
        'columns' => [
            [
            'attribute' => 'n',
            'header' => 'หน่วยงาน',
                'pageSummary'=>'รวม'
                
        ],
        
     
           
        ],
    ]);
?>
 
            
            
        </div>
        
    </div>
    </div>
</div>