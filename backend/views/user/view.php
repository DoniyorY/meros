<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */
$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$host = $_SERVER['SERVER_NAME']
?>
<div class="user-view">
    <div class="row">
        <div class="col-md-12">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-3">
           <?php if (!$model->staff_telegram_id) {
              echo Html::a(
                 'Подключить служебный Telegram',
                 ['/telegram-staff-connect/connect'],
                 ['class' => 'btn btn-primary']
              );
           }else{
              echo Html::a(
                 'Отключить служебный Telegram',
                 ['/telegram-staff-connect/disconnect'],
                 [
                    'class' => 'btn btn-outline-danger',
                    'data-method' => 'post',
                 ]
              );
           }
           ?>
            <div class="card text-center my-2">
                <div class="card-header">
                    <img src="<?= "http://$host/uploads/user/$model->image" ?>" alt="user_photo"
                         style="width: 100%;height: 150px; object-fit: contain">
                </div>
                <div class="card-body">
                   <?= "$model->fullname / $model->username" ?>
                </div>
            </div>
           <?= DetailView::widget([
              'model' => $model,
              'attributes' => [
                 //'username',
                 //'fullname',
                 'email:email',
                 'phone',
                 'address',
                 'image',
                 [
                    'attribute' => 'subscription_status',
                    'value' => function ($data) {
                       return Yii::$app->params['user_subscription_status'][$data->subscription_status];
                    }
                 ],
                 [
                    'attribute' => 'status',
                    'value' => function ($model) {
                       return Yii::$app->params['user_status'][$model->status];
                    }
                 ],
                 [
                    'attribute' => 'created_at',
                    'value' => function ($model) {
                       return Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i:s');
                    }
                 ],
                 [
                    'attribute' => 'updated_at',
                    'value' => function ($model) {
                       return Yii::$app->formatter->asDatetime($model->updated_at, 'php:d.m.Y H:i:s');
                    }
                 ],
              
              ],
           ]) ?>
        </div>

        <div class="col-md-8" style="margin-left: 50px;">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#user-settings"
                            type="button" role="tab" aria-controls="user-settings" aria-selected="true">User Settings
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#user-subscriptions"
                            type="button" role="tab" aria-controls="user-subscriptions" aria-selected="false">User
                        Subscriptions
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#user-lessons"
                            type="button" role="tab" aria-controls="user-lessons" aria-selected="false">User Lessons
                    </button>
                </li>

            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active p-3" id="user-settings" role="tabpanel" aria-labelledby="home-tab"
                     tabindex="0">
                   <?= $this->render('_user_settings', ['model' => $model]) ?>
                </div>
                <div class="tab-pane fade p-3" id="user-subscriptions" role="tabpanel" aria-labelledby="profile-tab"
                     tabindex="0">
                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                        <tr>
                            <th> #</th>
                            <th>Plan</th>
                            <th>Subscription Key</th>
                            <th>Created At</th>
                            <th>Period</th>
                            <th>Amount</th>
                            <th>CODE</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;
                        foreach ($model->subscriptions as $item): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $item->plan->name_en ?></td>
                                <td><?= $item->subscription_key ?></td>
                                <td><?= date('d.m.Y', $item->created_at) ?></td>
                                <td>
                                   <?php $start = date('d.m.Y', $item->start_date);
                                   $end = date('d.m.Y', $item->expires_date);
                                   echo "$start - $end";
                                   ?>
                                </td>
                                <td>
                                   <?= Yii::$app->formatter->asDecimal($item->amount) ?>
                                </td>
                                <td>
                                   <?= $item->currency_code ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade p-3" id="user-lessons" role="tabpanel" aria-labelledby="contact-tab"
                     tabindex="0">...
                </div>
            </div>
        </div>
    </div>
</div>
