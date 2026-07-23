<?php

use common\models\ChangePass;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/** @var yii\web\View $this */
/** @var common\models\User $model */
$this->title = $model->fullname;
\yii\web\YiiAsset::register($this);
$host = $_SERVER['SERVER_NAME'];
$base = Yii::$app->request->baseUrl;
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
                            <li class="breadcrumb-item">
                                <a href="<?= Yii::$app->homeUrl ?>"><?= Yii::$app->name ?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?= Url::to(['index']) ?>"><?= "Users" ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= \yii\helpers\Html::encode($this->title) ?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
       <?= Alert::widget() ?>
        <div class="row">
            <div class="col-xxl-3">
                <div class="card card-bg-fill">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                <img src="<?="$base/"?>images/users/user-dummy-img.jpg" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                                <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                    <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                                    <span class="avatar-title rounded-circle bg-light text-body">
                                                        <i class="ri-camera-fill"></i>
                                                    </span>
                                    </label>
                                </div>
                            </div>
                            <h5 class="fs-16 mb-1"><?=$model->fullname?></h5>
                            <p class="text-muted mb-0"><?=$model->username . ' / ' . $model->assignment->item_name?></p>
                        </div>
                    </div>
                </div>
                <!--end card-->
                <?php if ($model->assignment->item_name == "admin"):?>
                <div class="card">
                    <div class="card-body p-2">
                       <?php if (!$model->staff_telegram_id) {
                          echo Html::a(
                             'Подключить служебный Telegram',
                             ['/telegram-staff-connect/connect'],
                             ['class' => 'btn btn-primary w-100']
                          );
                       }else{
                          echo Html::a(
                             'Отключить служебный Telegram',
                             ['/telegram-staff-connect/disconnect'],
                             [
                                'class' => 'btn btn-outline-danger w-100',
                                'data-method' => 'post',
                             ]
                          );
                       }
                       ?>
                    </div>
                </div>
                <?php endif;?>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-0">User Info</h5>
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
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-xxl-9">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                    <i class="fas fa-home"></i> Personal Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                    <i class="far fa-user"></i> Change Password
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#user-subscriptions" role="tab">
                                    <i class="far fa-envelope"></i> User Subscriptions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#userBilling" role="tab">
                                    <i class="far fa-envelope"></i> User Billings
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">
                            <div class="tab-pane active" id="personalDetails" role="tabpanel">
                               <?= $this->render('_user_settings', ['model' => $model]) ?>
                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane" id="changePassword" role="tabpanel">
                                <?=$this->render('_change_password', ['model' => $model,'changePass'=>new ChangePass($model)])?>
                                <?=$this->render('_session_history',['model' => $model,'loginSessions'=>$loginSessions])?>
                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane" id="user-subscriptions" role="tabpanel">
                                <?=$this->render('_user_subscriptions', ['model' => $model])?>
                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane" id="userBilling" role="tabpanel">
                                <?=$this->render('_user_billing',['model' => $model])?>
                            </div>
                            <!--end tab-pane-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

    </div>
    <!-- container-fluid -->
</div><!-- End Page-content -->
