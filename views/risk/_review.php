<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DepDrop; //ทำdepan dropdown
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;

//////models
use app\models\Prorisk;
use app\models\Proriskdetail;
use app\models\Prorisksubdetail;
use app\models\Clinic;
use app\models\Severity;
use app\models\Born;
use app\models\Source;
use app\models\Dep;
use app\models\Team;
?>

<div class="riskform">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-2"> 
            <?= $form->field($model, 'date_stamp')->textInput(array('value' => date("Y-m-d"))) ?>
        </div>
        <div class="col-md-2">
        <?= $form->field($model, 'work_time')->radioList(array('Y'=>'ในเวลา','N'=>'นอกเวลา')); ?>
        </div>
        <div class="col-md-1"> 
            <?= $form->field($model, 'user_id')->hiddenInput(array('value' => Yii::$app->user->identity->id)) ?>
        </div>
        <!------------------------------------------------------------->
        <div class="col-md-3">
            <?=
            $form->field($model, 'pro_risk_id')->dropdownList(
                    ArrayHelper::map(Prorisk::find()->all(), 'pro_risk_id', 'pro_risk_name'), [
                'id' => 'ddl-prorisk',
                'prompt' => 'เลือกโปรแกรมความเสี่ยง'
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'pro_risk_detail_id')->dropdownList(
                    ArrayHelper::map(Proriskdetail::find()->where(['pro_risk_id'=>$model->pro_risk_id])->all(), 'pro_risk_detail_id', 'pro_risk_detail_name'), [
                //'id' => 'ddl-prorisk',
                'prompt' => 'เลือกโปรแกรมความเสี่ยง'
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
             <?=
            $form->field($model, 'pro_risk_sub_detail_id')->dropdownList(
                    ArrayHelper::map(Prorisksubdetail::find()->where(['Pro_risk_detail_id'=>$model->pro_risk_detail_id])->all(), 'pro_risk_sub_detail_id', 'pro_risk_sub_detail_name'), [
                //'id' => 'ddl-prorisk',
                'prompt' => 'เลือกโปรแกรมความเสี่ยง'
            ]);
            ?>
        </div>

        <div class="col-md-4">
            <?=
            $form->field($model, 'clinic_id')->dropdownList(
                    ArrayHelper::map(Clinic::find()->all(), 'clinic_id', 'clinic_name'), [
                'id' => 'ddl-clinic',
                'prompt' => 'เลือกคลินิค'
                    ]
            );
            ?>
        </div>
        <!----------------------------------------------------->
        <div class="col-md-4">
            <?=
            $form->field($model, 'severity_level')->dropdownList(
                    ArrayHelper::map(Severity::find()->all(), 'severity_text', 'severity_name'), [
                //'id' => 'ddl-clinic',
                'prompt' => 'เลือกคลินิค'
                    ]
            );
            ?>
           
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">   
            <?= $form->field($model, 'date_risk')->widget(\yii\jui\DatePicker::classname(), [
//'value' => date('YY-mm-dd'),
        'language' => 'th',
        'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                'changeMonth' => true,
                'changeYear' => true,
                
            ],
        
    ])
    ?>

   
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'born_id')->dropDownList(
                    ArrayHelper::map(Born::find()->all(), 'born_id', 'born_name'), ['prompt' => 'เลือกลักษณะการเกิด']
            )
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'source_id')->dropDownList(
                    ArrayHelper::map(Source::find()->all(), 'source_id', 'source_name'), ['prompt' => 'เลือกแหล่งที่มาของข้อมุล']
            )
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'detail_prob')->textarea(['maxlength' => true],'required') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?=
            $form->field($model, 'dep_id')->dropDownList(
                    ArrayHelper::map(Dep::find()->all(), 'dep_id', 'dep_name'), ['prompt' => 'เลือกหน่วยงานที่รายงาน']
            )
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'team_id')->dropDownList(
                    ArrayHelper::map(Team::find()->all(), 'team_id', 'team_name'), ['prompt' => 'เลือกทีมคล่อมสายงานที่เกิดความเสี่ยง']
            )
            ?>
        </div>
         <div class="col-md-4">
            <?=
            $form->field($model, 'num')->input('text', ['required','value'=>1])?>
        </div>
    </div>    
    <div class="row">
        <center>
            <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'บันทึก' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block glyphicon glyphicon-floppy-save' : 'btn btn-primary']) ?>
            </div>
        </center>
    </div>
<?php ActiveForm::end(); ?>

</div>
