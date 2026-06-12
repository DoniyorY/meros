<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\SubscriptionPlans $model
 */

$this->title = "Invoice";
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;

?>

<div id="page-content">
    <div class="container py-5">

        <div class="card shadow border-0">
            <div class="card-body p-4">
                <div class="alert alert-warning">
                    Вы ещё не зарегистрированы. Пожалуйста, <a href="<?= Url::to(['site/login']) ?>">войдите в
                        систему</a> или <a href="<?= Url::to(['site/signup']) ?>">зарегистрируйтесь</a>.
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1">Invoice</h1>
                        <small class="text-muted">
                            Invoice #12345
                        </small>
                    </div>

                    <div class="text-end">
                        <div><strong>Date:</strong> <?= date('d.m.Y') ?></div>
                        <div><strong>Status:</strong>
                            <span class="badge bg-warning">
                            Pending
                        </span>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row g-4">

                    <!-- Customer Info -->
                    <div class="col-lg-7">

                        <h5 class="mb-3">Customer Information</h5>
                       
                       <?php $form = ActiveForm::begin(); ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>First Name</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Last Name</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Username</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Password</label>
                                <input type="password" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control">
                            </div>
                        </div>
                       
                       <?php ActiveForm::end(); ?>

                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-5">

                        <div class="card bg-light border-0">
                            <div class="card-body">

                                <h5 class="mb-4">
                                    Order Summary
                                </h5>

                                <div class="d-flex justify-content-between mb-3">
                                    <span>Product</span>
                                    <strong>
                                       <?= $model->{"name_$lang"} ?>
                                    </strong>
                                </div>

                                <div class="d-flex justify-content-between mb-3">
                                    <span>Duration</span>
                                    <strong>3 Months</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-3">
                                    <span>Price</span>
                                    <strong>
                                       <?= Yii::$app->formatter->asDecimal($model->price) ?>
                                        UZS
                                    </strong>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0">Total</span>
                                    <span class="h4 text-primary mb-0">
                                    <?= Yii::$app->formatter->asDecimal($model->price) ?>
                                    UZS
                                </span>
                                </div>
                                <button id="btn-confirm" class="btn w-100 mt-2 btn-secondary me-2">Подтвердить</button>
                                <button id="btn-click" class="btn w-100 mt-2 btn-success me-2">
                                    <!-- Место для логотипа Click -->
                                    <img src="https://click.uz/click/images/logo.svg" alt="Click" height="20"></button>
                                <button id="btn-payme" class="btn w-100 mt-2 btn-primary">
                                    <!-- Место для логотипа Payme -->
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Paymeuz_logo.png"
                                         alt="Payme" style="height: 70px; object-fit: cover;">
                                </button>

                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
</div>
