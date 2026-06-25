<?php

use common\models\Billing;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var Billing $billing */

$params = Yii::$app->params;
$lang = Yii::$app->language;
$t = static function ($key) use ($params, $lang) { return $params[$key][$lang] ?? $params[$key]['en'] ?? $key; };
$this->title = $t('payment_result_title');

$statusText = match ((int) $billing->payment_status) {
    Billing::STATUS_SUCCESS => $t('payment_success'),
    Billing::STATUS_FAILED => $t('payment_failed'),
    Billing::STATUS_CANCELLED => $t('payment_cancelled'),
    default => $t('payment_pending'),
};

$statusClass = match ((int) $billing->payment_status) {
    Billing::STATUS_SUCCESS => 'alert-success',
    Billing::STATUS_FAILED => 'alert-danger',
    Billing::STATUS_CANCELLED => 'alert-warning',
    default => 'alert-info',
};

?>

<div class="container py-5">
    <div class="alert <?= $statusClass ?>">
        <h4 class="mb-2">
            <?= Html::encode($statusText) ?>
        </h4>

        <div>
            <?= Html::encode($t('invoice_number')) ?>:
            <strong>#<?= (int) $billing->id ?></strong>
        </div>

        <div>
            <?= Html::encode($t('amount')) ?>:
            <strong>
                <?= Yii::$app->formatter->asDecimal(
                    $billing->amount
                ) ?>
                UZS
            </strong>
        </div>
    </div>

    <?php if (
        (int) $billing->payment_status
        === Billing::STATUS_PENDING
    ): ?>
        <p class="text-muted">
            <?= Html::encode($t('payment_refresh_hint')) ?>
        </p>

        <?= Html::a(
            $t('refresh_status'),
            ['payment/payme-result', 'token' => $billing->billing_token],
            ['class' => 'btn btn-primary']
        ) ?>
    <?php endif; ?>
</div>
