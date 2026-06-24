<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\SubscriptionPlans $model
 */

$this->title = "Invoice";
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
$params = Yii::$app->params;


$merchantID = 20;
$merchantUserID = 4;
$serviceID = 31;
$transID = "user23151";
$transAmount = number_format(1000, 2, '.', '');
$cardType = "uzcard";
$returnURL = "сайт поставщика";

?>

<div id="page-content" class="meros-modern-page meros-invoice-page">
    <section class="meros-section reveal-section">
        <div class="container">
            <div class="meros-invoice-card">
               <?php if (Yii::$app->user->isGuest): ?>
                   <div class="alert alert-warning meros-invoice-alert">
                       You are not registered yet. Please
                       <a href="<?= Url::to(['site/login']) ?>">log in</a> or fill the fields to create an account.
                   </div>
               <?php endif; ?>
                <!-- Header -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 meros-invoice-header">
                    <div>
                        <span class="meros-kicker">Secure checkout</span>
                        <h1 class="h3 mb-1">Invoice</h1>
                        <small class="text-muted">
                            Invoice #<?= $billing->id ?>
                        </small>
                    </div>

                    <div class="text-end">
                        <div><strong>Date:</strong> <?= date('d.m.Y', $billing->created_at) ?></div>
                        <div><strong>Status:</strong>
                            <span class="<?= $params['billing_status_class'][$billing->status] ?>">
                            <?= $params['billing_status'][$lang][$billing->status] ?>
                        </span>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row g-4">

                    <!-- Customer Info -->
                    <div class="col-lg-7">
                        <div class="meros-invoice-panel">

                            <h5 class="mb-3">Customer Information</h5>
                           <?php if (Yii::$app->user->isGuest): ?>
                              <?php $form = ActiveForm::begin(['action' => Url::to(['guest-register'])]); ?>

                               <div class="row">
                                   <div class="col-md-12 mb-3">
                                       <div class="form-group">
                                           <label>Email</label>
                                           <input name="User[email]" type="email" class="form-control">
                                       </div>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label>First Name</label>
                                           <input name="User[first_name]" type="text" class="form-control">
                                       </div>
                                   </div>

                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label>Last Name</label>
                                           <input name="User[last_name]" type="text" class="form-control">
                                       </div>
                                   </div>

                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label>Username</label>
                                           <input name="User[username]" type="text" class="form-control">
                                       </div>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label>Phone</label>
                                           <input type="text" name="User[phone]" class="form-control">
                                       </div>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label>Password</label>
                                           <input name="User[password]" type="password" class="form-control">
                                       </div>
                                   </div>

                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label>Confirm Password</label>
                                           <input name="User[password_confirm]" type="password" class="form-control">
                                       </div>
                                   </div>
                                   <div class="col-md-12">
                                       <div class="form-group">
                                           <button type="submit" class="btn btn-primary meros-primary-btn w-100">
                                               Submit
                                           </button>
                                       </div>
                                   </div>
                               </div>
                              
                              <?php ActiveForm::end(); ?>
                           <?php else: ?>
                              <?php $form = ActiveForm::begin();
                              $user = Yii::$app->user->identity;
                              $nameParts = preg_split('/\s+/', trim((string)$user->fullname), 2);
                              $first_name = $nameParts[0] ?? '';
                              $last_name = $nameParts[1] ?? ''; ?>

                               <div class="row">
                                   <div class="col-md-12 mb-3">
                                       <div class="form-group">
                                           <label>Email</label>
                                           <input name="User[email]" type="email" class="form-control"
                                                  value="<?= Html::encode($user->email) ?>" readonly>
                                       </div>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label>First Name</label>
                                           <input name="User[first_name]" type="text" class="form-control"
                                                  value="<?= Html::encode($first_name) ?>" readonly>
                                       </div>
                                   </div>

                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label>Last Name</label>
                                           <input name="User[last_name]" type="text" class="form-control"
                                                  value="<?= Html::encode($last_name) ?>" readonly>
                                       </div>
                                   </div>
                                   <div class="col-md-12 mb-3">
                                       <div class="form-group">
                                           <label>Phone</label>
                                           <input type="text" name="User[phone]" class="form-control"
                                                  value="<?= Html::encode($user->phone) ?>" readonly>
                                       </div>
                                   </div>
                               </div>
                              
                              <?php ActiveForm::end(); ?>
                           <?php endif; ?>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-5">

                        <div class="meros-order-summary">

                            <h5 class="mb-4">
                                Order Summary
                            </h5>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Product</span>
                                <strong>
                                   <?= Html::encode($model->{"name_$lang"}) ?>
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Duration</span>
                                <strong><?php
                                   (int)$duration = $billing->subscription->duration_days / 30;
                                       echo "$duration months";
                                       ?>
                                </strong>
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
                           <?php if (!Yii::$app->user->isGuest): ?>

                               <button
                                       id="btn-confirm"
                                       class="btn w-100 mt-2 meros-primary-btn d-none"
                                       type="button"
                               >
                                   Подтвердить
                               </button>

                               <form
                                       action="<?= Url::to([
                                          'payment/click-pay',
                                          'id' => $billing->id,
                                       ]) ?>"
                                       id="click_form"
                                       method="post"
                                       target="_blank"
                               >
                                  <?= Html::hiddenInput(
                                     Yii::$app->request->csrfParam,
                                     Yii::$app->request->csrfToken
                                  ) ?>

                                   <input
                                           type="hidden"
                                           name="amount"
                                           value="<?= (int)$billing->amount ?>"
                                   >

                                   <input
                                           type="hidden"
                                           name="merchant_id"
                                           value="<?= Html::encode(
                                              $params['click']['merchant_id']
                                           ) ?>"
                                   >

                                   <input
                                           type="hidden"
                                           name="merchant_user_id"
                                           value="<?= Html::encode(
                                              $params['click']['merchant_user_id']
                                           ) ?>"
                                   >

                                   <input
                                           type="hidden"
                                           name="service_id"
                                           value="<?= Html::encode(
                                              $params['click']['service_id']
                                           ) ?>"
                                   >

                                   <input
                                           type="hidden"
                                           name="transaction_param"
                                           value="<?= (int)$billing->id ?>"
                                   >

                                   <input
                                           type="hidden"
                                           name="return_url"
                                           value="<?= Url::to(
                                              [
                                                 'payments/click-webhook',
                                                 'id' => $billing->id,
                                              ],
                                              true
                                           ) ?>"
                                   >

                                   <input
                                           type="hidden"
                                           name="card_type"
                                           value="uzcard"
                                   >

                                   <button
                                           type="submit"
                                           class="btn w-100 mt-2 meros-payment-btn meros-primary-btn"
                                   >
                                       <img
                                               src="https://click.uz/click/images/logo.svg"
                                               alt="Click"
                                               style="height: 30px;"
                                       >
                                   </button>
                               </form>

                               <!--
                                   В Payme-форму не передаём amount и merchant_id из браузера.
                                   Сервер заново загружает Billing по ID и сам формирует checkout URL.
                               -->
                              <?= Html::beginForm(
                              ['payment/payme', 'id' => $billing->id],
                              'post',
                              [
                                 'id' => 'payme-form',
                                 'target' => '_blank',
                              ]
                           ) ?>

                               <button
                                       id="btn-payme"
                                       type="submit"
                                       class="btn w-100 mt-2 meros-payment-btn meros-payment-payme"
                               >
                                   <img
                                           src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Paymeuz_logo.png"
                                           alt="Payme"
                                           style="height: 70px; object-fit: contain;"
                                   >
                               </button>
                              
                              <?= Html::endForm() ?>
                           
                           <?php endif; ?>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>
</div>
