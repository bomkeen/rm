<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Dep;
use app\models\Review;
use app\models\Follow;
use app\models\Team;
use yii\helpers\Url;
use yii\helpers\VarDumper;
?>

<div class="risk-form form-inline col-md-10 col-md-offset-1">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-1"> 
            <?= $form->field($model, 'edit_user_id')->hiddenInput(array('value' => Yii::$app->user->identity->id)) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'date_edit')->input('date', ['required']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'edit_dep_id')->dropDownList(ArrayHelper::map(Dep::find()->all(), 'dep_id', 'dep_name'), ['prompt' => '--Select--']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'edit_team_id')->dropDownList(ArrayHelper::map(Team::find()->all(), 'team_id', 'team_name'), ['prompt' => '--Select--']) ?>
        </div>
        
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'review_date')->widget(\yii\jui\DatePicker::classname(), [
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
            <?= $form->field($model, 'review_id')->dropDownList(ArrayHelper::map(Review::find()->all(), 'review_id', 'review_name'), ['prompt' => '--Select--']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'method')->textarea(['maxlength' => true, 'rows' => 2, 'cols' => 120]) ?>
        </div>
    </div>
    <div class="row">
        
        <div class="col-md-4 ">
            <?= $form->field($model, 'follow_id')->dropDownList(ArrayHelper::map(Follow::find()->all(), 'follow_id', 'follow_name'), ['prompt' => '--Select--']) ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'team_id')->dropDownList(
                    ArrayHelper::map(Team::find()->all(), 'team_id', 'team_name'), ['prompt' => 'เลือกทีมคล่อมสายงานที่เกิดความเสี่ยง']
            )
            ?>
        </div>
    </div>
    <?= $form->field($model, 'review_detail')->textarea(['maxlength' => true, 'rows' => 2, 'cols' => 120]) ?>
    <div class="row">
        <div class="col-md-4">
            <?= Html::submitButton($model->isNewRecord ? 'บันทึก' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block glyphicon glyphicon-floppy-save' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
