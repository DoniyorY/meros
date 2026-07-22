<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$base = Yii::$app->request->baseUrl;
?>
<div class="auth-page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mt-sm-5 mb-4 text-white-50">
                    <div>
                        <a href="<?= Yii::$app->homeUrl ?>" class="d-inline-block auth-logo">
                            <img src="<?= "$base/logo-white.png" ?>" alt="" height="70">
                        </a>
                    </div>
                    <p class="mt-3 fs-15 fw-medium">Premium Admin & Dashboard</p>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-4 card-bg-fill">

                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <h5 class="text-primary">Welcome Back !</h5>
                            <p class="text-muted">Sign in to continue to <?= Yii::$app->name ?>.</p>
                        </div>
                       <?php if (Yii::$app->session->hasFlash('warning')): ?>
                           <div class="alert alert-warning alert-dismissible fade show" role="alert">
                              <?= Yii::$app->session->getFlash('warning') ?>
                           </div>
                       <?php endif; ?>
                        <div class="p-2 mt-4">
                           <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                            <div class="mb-3">
                               <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'id' => 'username', 'placeholder' => 'Enter Username']) ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password-input">Password</label>
                                <div class="position-relative auth-pass-inputgroup mb-3">
                                   <?= $form->field($model, 'password')->passwordInput(['id' => 'password-input', 'placeholder' => 'Enter Password', 'class' => 'form-control pe-5 password-input'])->label(false) ?>
                                </div>
                            </div>
                            <?=$form->field($model, 'rememberMe')->checkbox()?>

                            <div class="mt-4">
                                <button class="btn btn-primary w-100" type="submit">Sign In</button>
                            </div>
                           <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end auth page content -->
