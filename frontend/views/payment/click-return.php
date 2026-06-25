<?php

use common\models\Billing;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var Billing $billing */

$this->title = 'Результат оплаты CLICK';

$statusText = match ((int) $billing->payment_status) {
   Billing::STATUS_SUCCESS => 'Оплата успешно выполнена.',
   Billing::STATUS_FAILED => 'Оплата завершилась ошибкой.',
   Billing::STATUS_CANCELLED => 'Оплата отменена.',
   default => 'Платёж ещё обрабатывается.',
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
            Счёт:
            <strong>#<?= (int) $billing->id ?></strong>
        </div>

        <div>
            Сумма:
            <strong>
               <?= Yii::$app->formatter->asDecimal($billing->amount) ?>
                UZS
            </strong>
        </div>
    </div>
   
   <?php if (
      (int) $billing->payment_status
      === Billing::STATUS_PENDING
   ): ?>
       <p class="text-muted">
           Если оплата только что завершилась, обновите страницу
           через несколько секунд.
       </p>
      
      <?= Html::a(
         'Обновить статус',
         ['payment/click-return', 'id' => $billing->id],
         ['class' => 'btn btn-primary']
      ) ?>
   <?php endif; ?>
</div>
