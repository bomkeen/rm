<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Risk */

$this->title = 'Update Risk: ' . ' ' . $model->risk_id;
$this->params['breadcrumbs'][] = ['label' => 'Risks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->risk_id, 'url' => ['view', 'id' => $model->risk_id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="row">
    <div class="col-md-12">
<div class="risk-update">

    <h1><?= 'แก้ไขความเสี่ยง' ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>
</div>
</div>
</div>