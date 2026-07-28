<?php

use common\models\Billing;
use common\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
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
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?= \yii\helpers\Html::encode($this->title) ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a
                                        href="<?= Yii::$app->homeUrl ?>"><?= Yii::$app->name ?></a></li>
                            <li class="breadcrumb-item active"><?= \yii\helpers\Html::encode($this->title) ?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="billing-index">
           <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
           
           <?= GridView::widget([
              'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
              'pager'=>Yii::$app->params['pager'],
              'columns' => [
                 ['class' => 'yii\grid\SerialColumn'],
                 
                 'id',
                 //'billing_token',
                 [
                    'attribute' => 'user_id',
                    'value' => function ($model) {
                       return Html::a($model->user->username, ['user/view', 'id' => $model->user->id]);
                    },
                    'filter' => Select2::widget([
                       'model' => $searchModel,
                       'attribute' => 'user_id',
                       'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
                       'options' => [
                          'placeholder' => 'Select user ...',
                       
                       ]
                    ]),
                    'format' => 'raw',
                 ],
                 [
                    'attribute' => 'subscription_id',
                    'value' => function ($data) {
                       return $data->subscription->sku_id ?? "Not Set!!";
                    }
                 ],
                 [
                    'header' => 'Period',
                    'value' => function ($data) {
                       if (!$data->start_date) return "Not Set";
                       return date('d.m.Y', $data->start_date) . ' - ' . date('d.m.Y', $data->expires_date);
                    }
                 ],
                 //'expires_date',
                 //'created_at',
                 [
                    'attribute' => 'updated_at',
                    'value' => function ($data) {
                       return date('d.m.Y', $data->updated_at);
                    },
                    'filter' => false
                 ],
                 'payment_transaction_id',
                 [
                    'attribute' => 'payment_provider',
                    'value' => function ($data) {
                       return Yii::$app->params['telegramStaffPaymentMethodMap'][$data->payment_provider];
                    },
                    'filter' => Yii::$app->params['telegramStaffPaymentMethodMap'],
                 ],
                 'payment_status',
                 [
                    'attribute' => 'amount',
                    'value' => function ($data) {
                       return Yii::$app->formatter->asDecimal($data->amount);
                    }
                 ],
                 [
                    'attribute' => 'status',
                    'value' => function ($data) {
                       return Html::tag('span', Yii::$app->params['billing_status']['en'][$data->status], ['class' => Yii::$app->params['billing_status_class'][$data->status]]);
                    },
                    'format' => 'raw',
                    'filter'=>Yii::$app->params['billing_status']['en'],
                 ],
              
              ],
           ]); ?>
        </div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

