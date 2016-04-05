<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProriskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Prorisks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prorisk-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Prorisk', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pro_risk_id',
            'pro_risk_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
