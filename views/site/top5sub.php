<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use rmrevin\yii\fontawesome\FA;
use app\models\Prorisk;
use app\models\Proriskdetail;
use app\models\Level;
use yii\helpers\ArrayHelper;
rmrevin\yii\fontawesome\AssetBundle::register($this);
?>
<div class="row">
    
    <div class="col-md-3 col-md-offset-9">
         <a class="btn btn-block btn-lg btn-success glyphicon glyphicon-refresh" href="<?= \yii\helpers\Url::to(['/site/top5sub']) ?>"> ประมวลผลใหม่</a>
        </div>
    
</div>
<div class="row">
    <div class="col-md-12 ">
        
         <form method="POST" class="form-inline">  
             ระหว่าง:
        <?php
        echo yii\jui\DatePicker::widget([
            'name' => 'date1',
            'value' => $date1,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'changeMonth' => true,
                'changeYear' => true,
            ],
        ]);
        ?>
        ถึง:
        <?php
        echo yii\jui\DatePicker::widget([
            'name' => 'date2',
            'value' => $date2,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'changeMonth' => true,
                'changeYear' => true,
            ]
        ]);
        ?>
            
            <?php
        
     
                $list = yii\helpers\ArrayHelper::map(Prorisk::find()->all(), 'pro_risk_id', 'pro_risk_name');
    
         
        echo yii\helpers\Html::dropDownList('prorisk',$prorisk, $list, [
                    'prompt' => 'เลือกหน่วยงาน',
                    'class' => 'form-control',
                    
                    
                ]);
                ?>
        <button class='btn btn-primary'>ประมวลผล</button>
   
    </form>
        
    </div>
    
 
</div>


   
<div class="row">
    <?php if($key==0){ ?>
       <div class="col-md-6">
        <div class="panel panel-warning">
            <div class="panel panel-heading">รายละเอียดอ่อย</div>
            <div class="panel panel-body">
<ul class="list-group">             
     <?php
                foreach ($pro_risk_detail as $d) {
         ?>
<a href="<?= \yii\helpers\Url::to(['/site/top5sub','prorisk'=>$prorisk,'pro_risk_detail_id'=>$d['pro_risk_detail_id'],'date1'=>$date1,'date2'=>$date2,'key'=>99]) ?>">
 
    <li class="list-group-item fa fa-check"><?php echo $d['pro_risk_detail_name'];?></li>
</a>
<?php
        }
        ?>
       </ul>          
                
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if($key==99){ ?>
    
    <?php 
    $pro= Prorisk::findOne(['pro_risk_id'=>$prorisk]);
    $prod=  Proriskdetail::findone(['pro_risk_detail_id'=>$pro_de]);
    ?>
    <div class="col-md-6">
        <h4><div class="label label-success glyphicon glyphicon-play-circle">  โปรแกรมความเสี่ยง  <?php echo  $pro->pro_risk_name;?> </div></h4>
        <h4><div class="label label-success glyphicon glyphicon-play-circle">  หมวดย่อย  <?php echo $prod->pro_risk_detail_name;?> </div></h4>
        <h4><div class="label label-success glyphicon glyphicon-calendar">  ตั้งแต่วันที่  <?php echo $date1;?> ถึงวันที่ <?php echo $date2 ;?> </div></h4>
    </div>
    <div class="col-md-6 col-md-offset-0">
      
          
            
                
                <table class="table table-hover">
                    <tr class="success">
                        <th>
                            จำนวนครั้ง
                        </th>
                        <th>
                            ระดับความรุนแรง
                        </th>
                        <th>
                            รายชื่อหมวดย่อย
                        </th>
                    </tr>
           <?php
                foreach ($result as $s) {
         ?>     
                    <tr>
                        <td class="danger">
                        <?php echo $s['n']; ?>
                    </td>
                    <td>
                        <?php echo $s['s']; ?>
                    </td>
                    <td>
                        <?php echo $s['pro_risk_sub_detail_name']; ?>
                    </td>
                
                    </tr>
                    <?php
        }
        ?>
</table>
     

 
   


               
                
         
    </div>
    <?php }?>
</div>




