<?php

/** @var yii\web\View $this */
/** @var common\models\UserSubscriptions $subscription */

use yii\helpers\Html;

$this->title = 'Payment status';
?>
<div class="container py-5">
    <?php if ((int)$subscription->status === $subscription::STATUS_ACTIVE): ?>
        <div class="alert alert-success">
            Payment completed successfully.
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            Payment is not confirmed yet. Refresh the page in a few seconds.
        </div>
    <?php endif; ?>

    <?= Html::a('My subscriptions', ['/site/profile'], ['class' => 'btn btn-primary']) ?>
</div>
