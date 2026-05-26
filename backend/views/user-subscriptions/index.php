<?php

use common\models\UserSubscriptions;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\UserSubscriptionsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'User Subscriptions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-subscriptions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User Subscriptions', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'plan_id',
            'user_id',
            'status',
            'subscription_key',
            //'start_date',
            //'expires_date',
            //'created_at',
            //'updated_at',
            //'amount',
            //'currency_code',
            //'payment_transaction_id',
            //'payment_provider',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, UserSubscriptions $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
