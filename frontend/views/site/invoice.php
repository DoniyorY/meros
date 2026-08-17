<?php

/** @var yii\web\View $this */
/** @var common\models\Billing $billing */
/** @var common\models\User $user */

use common\models\Billing;
use yii\helpers\Html;
use yii\helpers\Url;

$params = Yii::$app->params;
$lang = Yii::$app->language;
$formatter = Yii::$app->formatter;
$t = static function ($key) use ($params, $lang) {
   return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};
$statusText = match ((int)$billing->payment_status) {
   Billing::STATUS_SUCCESS => $t('profile_billing_paid'),
   Billing::STATUS_FAILED => $t('profile_billing_failed'),
   Billing::STATUS_CANCELLED => $t('profile_billing_cancelled'),
   default => $t('profile_billing_pending'),
};
$statusClass = match ((int)$billing->payment_status) {
   Billing::STATUS_SUCCESS => 'bg-success',
   Billing::STATUS_FAILED => 'bg-danger',
   Billing::STATUS_CANCELLED => 'bg-secondary',
   default => 'bg-warning text-dark',
};
$plan = $billing->subscription;
$planName = $plan ? ($plan->{"name_$lang"} ?: $plan->name_en) : '-';
$course = $plan && $plan->course ? ($plan->course->{"name_$lang"} ?: $plan->course->name_en) : '-';

$this->title = $t('profile_billing_receipt') . ' #' . (int)$billing->id;
$this->params['breadcrumbs'][] = [
   'label' => $t('profile_page_title'),
   'url' => ['site/profile', '#' => 'tab-billing'],
];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<CSS
.meros-receipt { margin: 32px auto 64px; max-width: 860px; }
.meros-receipt-card { background: #fff; border: 1px solid #dfe9e9; border-radius: 28px; box-shadow: 0 18px 55px rgba(4,54,63,.11); overflow: hidden; }
.meros-receipt-header { background: linear-gradient(135deg, #064f58, #07838d); color: #fff; padding: 32px; }
.meros-receipt-body { padding: 32px; }
.meros-receipt-row { border-bottom: 1px dashed #dfe9e9; display: flex; gap: 24px; justify-content: space-between; padding: 15px 0; }
.meros-receipt-row span { color: #657577; }
.meros-receipt-row strong { color: #04363f; text-align: right; }
.meros-receipt-total { border-bottom: 0; font-size: 1.2rem; padding-top: 24px; }
@media print {
  footer, .breadcrumbs, .meros-receipt-actions, .navbar { display: none !important; }
  .meros-receipt { margin: 0; max-width: none; }
  .meros-receipt-card { border: 0; box-shadow: none; }
}
CSS
);
?>

<div id="page-content" class="meros-modern-page">
    <div class="container">
        <article class="meros-receipt">
            <div class="meros-receipt-actions d-flex flex-wrap justify-content-between gap-2 mb-3">
                <a class="btn btn-outline-primary" href="<?= Url::to(['site/profile', '#' => 'tab-billing']) ?>">
                    <i class="fa fa-arrow-left me-2"></i><?= Html::encode($t('profile_billing_back')) ?>
                </a>
                <button class="btn btn-primary" type="button" onclick="window.print()">
                    <i class="fa fa-download me-2"></i><?= Html::encode($t('profile_billing_print')) ?>
                </button>
            </div>

            <div class="meros-receipt-card">
                <header class="meros-receipt-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <div class="text-uppercase small opacity-75"><?= Html::encode($t('profile_billing_receipt')) ?></div>
                        <h1 class="h2 mb-0">#<?= (int)$billing->id ?></h1>
                    </div>
                    <span class="badge <?= $statusClass ?> fs-6"><?= Html::encode($statusText) ?></span>
                </header>
                <div class="meros-receipt-body">
                    <div class="meros-receipt-row">
                        <span><?= Html::encode($t('profile_billing_date')) ?></span>
                        <strong><?= Html::encode($formatter->asDatetime($billing->created_at, 'php:d.m.Y H:i')) ?></strong>
                    </div>
                    <div class="meros-receipt-row">
                        <span><?= Html::encode($t('profile_billing_customer')) ?></span>
                        <strong><?= Html::encode($user->fullname ?: $user->username) ?><br><small><?= Html::encode($user->email) ?></small></strong>
                    </div>
                    <div class="meros-receipt-row">
                        <span><?= Html::encode($t('profile_billing_course')) ?></span>
                        <strong><?= Html::encode($course) ?></strong>
                    </div>
                    <div class="meros-receipt-row">
                        <span><?= Html::encode($t('profile_plan')) ?></span>
                        <strong><?= Html::encode($planName) ?></strong>
                    </div>
                    <div class="meros-receipt-row">
                        <span><?= Html::encode($t('profile_payment_provider')) ?></span>
                        <strong><?= Html::encode($billing->payment_provider ?? '-') ?></strong>
                    </div>
                    <div class="meros-receipt-row">
                        <span><?= Html::encode($t('profile_transaction')) ?></span>
                        <strong><?= Html::encode($billing->payment_transaction_id ?: '-') ?></strong>
                    </div>
                    <div class="meros-receipt-row meros-receipt-total">
                        <span><?= Html::encode($t('profile_amount')) ?></span>
                        <strong><?= Html::encode($formatter->asDecimal($billing->amount ?: 0)) ?> UZS</strong>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>
