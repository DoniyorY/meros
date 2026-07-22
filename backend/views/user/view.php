<?php

use common\models\ChangePass;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/** @var yii\web\View $this */
/** @var common\models\User $model */
$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
                                <a class="nav-link" data-bs-toggle="tab" href="#privacy" role="tab">
                                    <i class="far fa-envelope"></i> Privacy Policy
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
                            <div class="tab-pane" id="privacy" role="tabpanel">
                                <div class="mb-4 pb-2">
                                    <h5 class="card-title text-decoration-underline mb-3">Security:</h5>
                                    <div class="d-flex flex-column flex-sm-row mb-4 mb-sm-0">
                                        <div class="flex-grow-1">
                                            <h6 class="fs-14 mb-1">Two-factor Authentication</h6>
                                            <p class="text-muted">Two-factor authentication is an enhanced security meansur. Once enabled, you'll be required to give two types of identification when you log into Google Authentication and SMS are Supported.</p>
                                        </div>
                                        <div class="flex-shrink-0 ms-sm-3">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary">Enable Two-facor Authentication</a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column flex-sm-row mb-4 mb-sm-0 mt-2">
                                        <div class="flex-grow-1">
                                            <h6 class="fs-14 mb-1">Secondary Verification</h6>
                                            <p class="text-muted">The first factor is a password and the second commonly includes a text with a code sent to your smartphone, or biometrics using your fingerprint, face, or retina.</p>
                                        </div>
                                        <div class="flex-shrink-0 ms-sm-3">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary">Set up secondary method</a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column flex-sm-row mb-4 mb-sm-0 mt-2">
                                        <div class="flex-grow-1">
                                            <h6 class="fs-14 mb-1">Backup Codes</h6>
                                            <p class="text-muted mb-sm-0">A backup code is automatically generated for you when you turn on two-factor authentication through your iOS or Android Twitter app. You can also generate a backup code on twitter.com.</p>
                                        </div>
                                        <div class="flex-shrink-0 ms-sm-3">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary">Generate backup codes</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h5 class="card-title text-decoration-underline mb-3">Application Notifications:</h5>
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex">
                                            <div class="flex-grow-1">
                                                <label for="directMessage" class="form-check-label fs-14">Direct messages</label>
                                                <p class="text-muted">Messages from people you follow</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="directMessage" checked />
                                                </div>
                                            </div>
                                        </li>
                                        <li class="d-flex mt-2">
                                            <div class="flex-grow-1">
                                                <label class="form-check-label fs-14" for="desktopNotification">
                                                    Show desktop notifications
                                                </label>
                                                <p class="text-muted">Choose the option you want as your default setting. Block a site: Next to "Not allowed to send notifications," click Add.</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="desktopNotification" checked />
                                                </div>
                                            </div>
                                        </li>
                                        <li class="d-flex mt-2">
                                            <div class="flex-grow-1">
                                                <label class="form-check-label fs-14" for="emailNotification">
                                                    Show email notifications
                                                </label>
                                                <p class="text-muted"> Under Settings, choose Notifications. Under Select an account, choose the account to enable notifications for. </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="emailNotification" />
                                                </div>
                                            </div>
                                        </li>
                                        <li class="d-flex mt-2">
                                            <div class="flex-grow-1">
                                                <label class="form-check-label fs-14" for="chatNotification">
                                                    Show chat notifications
                                                </label>
                                                <p class="text-muted">To prevent duplicate mobile notifications from the Gmail and Chat apps, in settings, turn off Chat notifications.</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="chatNotification" />
                                                </div>
                                            </div>
                                        </li>
                                        <li class="d-flex mt-2">
                                            <div class="flex-grow-1">
                                                <label class="form-check-label fs-14" for="purchaesNotification">
                                                    Show purchase notifications
                                                </label>
                                                <p class="text-muted">Get real-time purchase alerts to protect yourself from fraudulent charges.</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="purchaesNotification" />
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="card-title text-decoration-underline mb-3">Delete This Account:</h5>
                                    <p class="text-muted">Go to the Data & Privacy section of your profile Account. Scroll to "Your data & privacy options." Delete your Profile Account. Follow the instructions to delete your account :</p>
                                    <div>
                                        <input type="password" class="form-control" id="passwordInput" placeholder="Enter your password" value="make@321654987" style="max-width: 265px;">
                                    </div>
                                    <div class="hstack gap-2 mt-3">
                                        <a href="javascript:void(0);" class="btn btn-soft-danger">Close & Delete This Account</a>
                                        <a href="javascript:void(0);" class="btn btn-light">Cancel</a>
                                    </div>
                                </div>
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
