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
$course = $model->course;
$courseName = $course ? ($course->{"name_$lang"} ?: $course->name_en) : null;
if ($courseName) {
   $courseBreadcrumb = ['label' => $courseName];
   if ($course->category) {
      $courseBreadcrumb['url'] = [
         'courses/index',
         'category' => $course->category->slug,
         'slug' => $course->slug,
      ];
   }
   $this->params['breadcrumbs'][] = $courseBreadcrumb;
}
$this->params['breadcrumbs'][] = $this->title;

$canApplyPromocode = !Yii::$app->user->isGuest
   && (int) $billing->status !== Billing::STATUS_SUCCESS
   && (int) $billing->payment_status !== Billing::STATUS_SUCCESS
   && trim((string) $billing->payment_transaction_id) === '';
$promoLabels = [
   'ru' => ['Промокод', 'Применить', 'Убрать', 'Не удалось проверить промокод. Попробуйте ещё раз.'],
   'en' => ['Promo code', 'Apply', 'Remove', 'Unable to check the promo code. Please try again.'],
   'uz' => ['Promokod', 'Qo‘llash', 'Olib tashlash', 'Promokodni tekshirib bo‘lmadi. Qayta urinib ko‘ring.'],
];
$promoText = $promoLabels[$lang] ?? $promoLabels['en'];

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
                                <strong>
                                   <?php
                                   (int)$duration = Yii::$app->formatter->asInteger($billing->subscription->duration_days / 30);
                                   echo Html::encode(strtr($t('duration_months'), ['{count}' => $duration]));
                                   ?>
                                </strong>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <span><?= Html::encode($t('price')) ?></span>
                                <strong id="invoice-original-amount">
                                   <?= Yii::$app->formatter->asDecimal($billing->no_discount_amount ?? $billing->amount) ?> UZS
                                </strong>
                            </div>
                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0"><?= Html::encode($t('total')) ?></span>
                                <span id="invoice-total-amount" class="h4 text-primary mb-0">
                                    <?= Yii::$app->formatter->asDecimal($billing->amount) ?>
                                    UZS
                                </span>
                            </div>
                           <?php if ($canApplyPromocode): ?>
                               <?= Html::beginForm(['courses/apply-promocode', 'id' => $billing->id], 'post', ['id' => 'promocode-form', 'class' => 'meros-promocode-form mt-4']) ?>
                                   <label for="invoice-promocode" class="form-label"><?= Html::encode($promoText[0]) ?></label>
                                   <div class="meros-promocode-controls">
                                       <?= Html::textInput('code', $billing->promocode ? $billing->promocode->code : '', [
                                          'id' => 'invoice-promocode', 'class' => 'form-control',
                                          'maxlength' => 50, 'autocomplete' => 'off',
                                          'aria-describedby' => 'promocode-message',
                                       ]) ?>
                                       <div class="meros-promocode-actions">
                                           <button type="submit" class="btn btn-primary meros-primary-btn"><?= Html::encode($promoText[1]) ?></button>
                                           <button type="button" id="remove-promocode" class="btn meros-secondary-btn"><?= Html::encode($promoText[2]) ?></button>
                                       </div>
                                   </div>
                                   <div id="promocode-message" class="small mt-2" role="status" aria-live="polite"></div>
                               <?= Html::endForm() ?>
                           <?php elseif ($billing->promocode): ?>
                               <p class="mt-3"><?= Html::encode($promoText[0] . ': ' . $billing->promocode->code) ?></p>
                           <?php endif; ?>
                           <?php if (!Yii::$app->user->isGuest): ?>
                              <?php if ((int) $billing->status === Billing::STATUS_PENDING): ?>

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

                                   <button id="btn-payme" type="submit"
                                           class="btn w-100 mt-2 meros-payment-btn meros-payment-payme">
                                       <img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Paymeuz_logo.png"
                                            alt="Payme"
                                            style="height: 70px; object-fit: contain;">
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

<?php
$promoError = \yii\helpers\Json::htmlEncode($promoText[3]);
$this->registerJs(<<<JS
(function () {
    const form = document.getElementById('promocode-form');
    if (!form) return;
    const input = document.getElementById('invoice-promocode');
    const message = document.getElementById('promocode-message');
    let busy = false;
    let timer;
    async function applyCode() {
        if (busy) return;
        clearTimeout(timer);
        timer = null;
        busy = true;
        const controls = Array.from(document.querySelectorAll('#promocode-form button, #click_form button, #payme-form button'));
        controls.forEach(button => button.disabled = true);
        input.readOnly = true;
        message.textContent = '';
        try {
            const response = await fetch(form.action, {
                method: 'POST', body: new FormData(form), credentials: 'same-origin',
                headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
            });
            if (!response.ok) throw new Error();
            const result = await response.json();
            message.textContent = result.message;
            message.className = 'small mt-2 ' + (result.success ? 'text-success' : 'text-danger');
            if (result.success) {
                document.getElementById('invoice-total-amount').textContent = result.amountFormatted;
                document.getElementById('invoice-original-amount').textContent = result.originalFormatted;
                const amount = document.querySelector('#click_form input[name="amount"]');
                if (amount) amount.value = result.amount;
                input.value = result.code;
            }
        } catch (error) {
            message.textContent = $promoError;
            message.className = 'small mt-2 text-danger';
        } finally {
            busy = false;
            input.readOnly = false;
            controls.forEach(button => button.disabled = false);
        }
    }
    form.addEventListener('submit', event => { event.preventDefault(); applyCode(); });
    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(applyCode, 600);
    });
    document.getElementById('remove-promocode').addEventListener('click', () => {
        input.value = '';
        applyCode();
    });
    // Never launch checkout before an outstanding discount request finishes.
    ['click_form', 'payme-form'].forEach(id => {
        const paymentForm = document.getElementById(id);
        if (paymentForm) paymentForm.addEventListener('submit', event => {
            if (busy || timer) {
                event.preventDefault();
                if (!busy) applyCode();
            }
        });
    });
})();
JS);
?>
