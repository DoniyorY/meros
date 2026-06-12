<?php

use common\models\Billing;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\BillingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Billings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Billing', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'billing_token',
            'user_id',
            'subscription_id',
            'start_date',
            //'expires_date',
            //'created_at',
            //'updated_at',
            //'payment_transaction_id',
            //'payment_provider',
            //'payment_status',
            //'amount',
            //'status',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Billing $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
