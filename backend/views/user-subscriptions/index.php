<?php

use common\models\UserSubscriptions;
use kartik\select2\Select2;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\UserSubscriptionsSearch $searchModel
 */

$base = Yii::$app->request->baseUrl;
$this->title = "User Subscriptions";
?>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?= $this->title ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active"><?= $this->title ?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="user-subscriptions-index">
           <?= GridView::widget([
              'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
              'columns' => [
                 ['class' => 'yii\grid\SerialColumn'],
                 
                 //'id',
                 [
                    'attribute' => 'plan_id',
                    'value' => function ($data) {
                       $courseName = $data->plan->courseName ?? "Not Set";
                       $planName = $data->plan->name_en ?? "Not Set";
                       return "$courseName || $planName";
                    },
                    'filter' => Select2::widget([
                       'model' => $searchModel,
                       'attribute' => 'plan_id',
                       'data' => \yii\helpers\ArrayHelper::map(\common\models\SubscriptionPlans::find()->all(), 'id', 'name_en', 'courseName'),
                       'options' => [
                          'placeholder' => 'Select plan',
                          'id' => 'plan_id',
                       ],
                       'pluginOptions' => [
                          'allowClear' => true,
                       
                       ]
                    ]),
                    'format' => 'raw',
                 ],
                 [
                    'attribute' => 'user_id',
                    'value' => function ($data) {
                       return $data->user->username;
                    },
                    'filter' => Select2::widget([
                       'model' => $searchModel,
                       'attribute' => 'user_id',
                       'data' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'username'),
                       'options' => [
                          'placeholder' => 'Select user',
                          'id' => 'user_id',
                       ],
                       'pluginOptions' => [
                          'allowClear' => true,
                       ]
                    ])
                 ],
                 
                 [
                    'header' => 'Period',
                    'value' => function ($data) {
                       $start = date('d.m.Y', $data->start_date);
                       $end = date('d.m.Y', $data->expires_date);
                       return "$start - $end";
                    }
                 ],
                 [
                    'attribute' => 'updated_at',
                    'value' => function ($data) {
                       return date('d.m.Y H:i', $data->updated_at);
                    }
                 ],
                 [
                    'attribute' => 'amount',
                    'value' => function ($data) {
                       return Yii::$app->formatter->asDecimal($data->amount);
                    }
                 ],
                 'payment_transaction_id',
                 [
                    'attribute' => 'payment_provider',
                    'value' => function ($data) {
                       return $data->payment_provider;
                    },
                 ],
                 [
                    'attribute' => 'status',
                    'value' => function ($data) {
                       return Yii::$app->params['status'][$data->status];
                    },
                    'filter' => Yii::$app->params['status']
                 ],
              ],
           ]); ?>
        </div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->


