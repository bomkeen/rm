<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Prorisk */

$this->title = 'Update Prorisk: ' . ' ' . $model->pro_risk_id;
$this->params['breadcrumbs'][] = ['label' => 'Prorisks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pro_risk_id, 'url' => ['view', 'id' => $model->pro_risk_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="prorisk-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
