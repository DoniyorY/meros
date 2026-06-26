<?php

use common\models\Billing;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\SubscriptionPlans $model
 */

$this->title = Yii::$app->params['invoice_title'][Yii::$app->language] ?? 'Invoice';
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
$params = Yii::$app->params;
$t = static function ($key) use ($params, $lang) {
   return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};

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
                      <?= Html::encode($t('invoice_guest_warning_prefix')) ?>
                       <a href="<?= Url::to(['site/login']) ?>"><?= Html::encode($t('login')) ?></a> <?= Html::encode($t('invoice_guest_warning_suffix')) ?>
                   </div>
               <?php endif; ?>
                <!-- Header -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 meros-invoice-header">
                    <div>
                        <span class="meros-kicker"><?= Html::encode($t('secure_checkout')) ?></span>
                        <h1 class="h3 mb-1"><?= Html::encode($t('invoice_title')) ?></h1>
                        <small class="text-muted">
                           <?= Html::encode($t('invoice_number')) ?> #<?= $billing->id ?>
                        </small>
                    </div>

                    <div class="text-end">
                        <div><strong><?= Html::encode($t('date')) ?>
                                :</strong> <?= date('d.m.Y', $billing->created_at) ?></div>
                        <div><strong><?= Html::encode($t('status')) ?>:</strong>
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

                            <h5 class="mb-3"><?= Html::encode($t('customer_information')) ?></h5>
                           <?php if (Yii::$app->user->isGuest): ?>
                              <?php $form = ActiveForm::begin(['action' => Url::to(['guest-register'])]); ?>

                               <div class="row">
                                   <div class="col-md-12 mb-3">
                                       <div class="form-group">
                                           <label><?= Html::encode($t('profile_email')) ?></label>
                                           <input name="User[email]" type="email" class="form-control">
                                       </div>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label><?= Html::encode($t('first_name')) ?></label>
                                           <input name="User[first_name]" type="text" class="form-control">
                                       </div>
                                   </div>

                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label><?= Html::encode($t('last_name')) ?></label>
                                           <input name="User[last_name]" type="text" class="form-control">
                                       </div>
                                   </div>

                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label><?= Html::encode($t('profile_username')) ?></label>
                                           <input name="User[username]" type="text" class="form-control">
                                       </div>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label><?= Html::encode($t('profile_phone')) ?></label>
                                           <input type="text" name="User[phone]" class="form-control">
                                       </div>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label><?= Html::encode($t('login_password')) ?></label>
                                           <input name="User[password]" type="password" class="form-control">
                                       </div>
                                   </div>

                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label><?= Html::encode($t('confirm_password')) ?></label>
                                           <input name="User[password_confirm]" type="password" class="form-control">
                                       </div>
                                   </div>
                                   <div class="col-md-12">
                                       <div class="form-group">
                                           <button type="submit" class="btn btn-primary meros-primary-btn w-100">
                                              <?= Html::encode($t('submit')) ?>
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
                                           <label><?= Html::encode($t('profile_email')) ?></label>
                                           <input name="User[email]" type="email" class="form-control"
                                                  value="<?= Html::encode($user->email) ?>" readonly>
                                       </div>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label><?= Html::encode($t('first_name')) ?></label>
                                           <input name="User[first_name]" type="text" class="form-control"
                                                  value="<?= Html::encode($first_name) ?>" readonly>
                                       </div>
                                   </div>

                                   <div class="col-md-6 mb-3">
                                       <div class="form-group">
                                           <label><?= Html::encode($t('last_name')) ?></label>
                                           <input name="User[last_name]" type="text" class="form-control"
                                                  value="<?= Html::encode($last_name) ?>" readonly>
                                       </div>
                                   </div>
                                   <div class="col-md-12 mb-3">
                                       <div class="form-group">
                                           <label><?= Html::encode($t('profile_phone')) ?></label>
                                           <input type="text" name="User[phone]" class="form-control"
                                                  value="<?= Html::encode($user->phone) ?>" readonly>
                                       </div>
                                   </div>
                               </div>
                              
                              <?php ActiveForm::end(); ?>
                           <?php endif; ?>
                        </div>
                    </div>

                    <!-- <?= Html::encode($t('order_summary')) ?> -->
                    <div class="col-lg-5">

                        <div class="meros-order-summary">

                            <h5 class="mb-4">
                               <?= Html::encode($t('order_summary')) ?>
                            </h5>

                            <div class="d-flex justify-content-between mb-3">
                                <span><?= Html::encode($t('product')) ?></span>
                                <strong>
                                   <?= Html::encode($model->{"name_$lang"}) ?>
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span><?= Html::encode($t('duration')) ?></span>
                                <strong><?php
                                   (int)$duration = $billing->subscription->duration_days / 30;
                                   echo Html::encode(strtr($t('duration_months'), ['{count}' => $duration]));
                                   ?>
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span><?= Html::encode($t('price')) ?></span>
                                <strong>
                                   <?= Yii::$app->formatter->asDecimal($model->price) ?>
                                    UZS
                                </strong>
                            </div>
                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0"><?= Html::encode($t('total')) ?></span>
                                <span class="h4 text-primary mb-0">
                                    <?= Yii::$app->formatter->asDecimal($model->price) ?>
                                    UZS
                                </span>
                            </div>
                           <?php if (!Yii::$app->user->isGuest): ?>
                              <?php if ($billing->status === Billing::STATUS_PENDING): ?>

                                   <button
                                           id="btn-confirm"
                                           class="btn w-100 mt-2 meros-primary-btn d-none"
                                           type="button"
                                   >
                                      <?= Html::encode($t('confirm')) ?>
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
                              
                              <?php endif;
                           endif; ?>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>
</div>
