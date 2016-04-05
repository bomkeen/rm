<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class='well'>
    <form method="POST">
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
        <button class='btn btn-danger'>ประมวลผล</button>
    </form>
</div>
<div class="row">
    <div class="col-md-6">
    <p class="page-header"> ข้อมูลระหว่างวันที่ <?php echo $date1 ;?> ถึง <?php echo $date2 ;?></p>
    </div></div>
<div class="row">

    <div class="col-md-4">
        <table class="table table-bordered">
            <tr>
                <th class="success" rowspan="2">
            <center>ระดับความรุนแรง</center>
            </th>
            <th class="success" colspan="2">
            <center>คลินิคทั่วไป</center>
            </th>
           
           
            </tr>
            <tr>
             
                <th class="success">
            <center>เรื่อง</center>

            </th>
            <th class="success">
            <center>ความถี่(ครั้ง)</center>
            </th>
           
            
            </tr>
            <!--End Header-->
            
            <?php
                foreach ($c1 as $l1) {
         ?>

             <tr>
            <td class="info">
<?php echo $l1['level']; ?>
            </td>
            <td class="info">
            <center><?php echo $l1['n']; ?></center>

            </td>
            <td class="info">
            <center><?php echo $l1['sum']; ?></center>
            </td>
             </tr>
            <?php
        }
        ?>
            


        </table>
    </div>
    
    <!--คลินิคเฉพาะโรค-->
    <div class="col-md-4">
        <table class="table table-bordered">
            <tr>
                <th class="danger" rowspan="2">
            <center>ระดับความรุนแรง</center>
            </th>
            <th class="danger" colspan="2">
            <center>คลินิคเฉพาะโรค</center>
            </th>
           
            </tr>
            <tr>
                
                <th class="danger">
            <center>เรื่อง</center>

            </th>
            <th class="danger">
            <center>ความถี่(ครั้ง)</center>
            </th>
              
            </tr>
            <!--End Header-->
            
            <?php
                foreach ($c2 as $l2) {
         ?>

             <tr>
            <td class="info">
<?php echo $l2['level']; ?>
            </td>
            <td class="info">
            <center><?php echo $l2['n']; ?></center>

            </td>
            <td class="info">
            <center><?php echo $l2['sum']; ?></center>
            </td>
             </tr>
            <?php
        }
        ?>
            


        </table>
    </div>
    
    <!--ตารางทั่วไป-->
    <div class="col-md-4">
       
        <table class="table table-bordered">
            <tr>
           
                <th class="warning" rowspan="2">
            <center>ระดับความรุนแรง</center>                    
            </th>
            <th class="warning" colspan="2">
            <center>ด้านทั่วไป</center>                    
            </th>
            </tr>
            <tr>
          
            <th class="warning">
            <center>เรื่อง</center>

            </th>
            <th class="warning">
            <center>ความถี่(ครั้ง)</center>
            </th>
            </tr>
            <!--End Header-->
            
           
        <?php
                foreach ($nc as $l) {
         ?>

             <tr>
            <td class="info">
<?php echo $l['level']; ?>
            </td>
            <td class="info">
            <center><?php echo $l['n']; ?></center>

            </td>
            <td class="info">
            <center><?php echo $l['sum']; ?></center>
            </td>
             </tr>
            <?php
        }
        ?>
           
            


        </table>
            
   

</div>
