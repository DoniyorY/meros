<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var frontend\models\ProfileForm $profileModel */
/** @var frontend\models\ChangePasswordForm $passwordModel */
/** @var common\models\UserSubscriptions|null $currentSubscription */
/** @var common\models\UserSubscriptions[] $subscriptionHistory */

use common\models\User;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$params = Yii::$app->params;
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
$formatter = Yii::$app->formatter;
$t = static function ($key) use ($params, $lang) {
    return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};
$formatDate = static function ($value) use ($formatter) {
    return $value ? Html::encode($formatter->asDate($value, 'php:d.m.Y')) : '-';
};
$formatAmount = static function ($amount, $currencyCode = null) use ($formatter) {
    $currency = $currencyCode == 860 || $currencyCode === null ? 'UZS' : $currencyCode;
    return Html::encode($formatter->asDecimal($amount ?: 0)) . ' ' . Html::encode($currency);
};
$planName = static function ($subscription) use ($lang) {
    if (!$subscription || !$subscription->plan) {
        return '-';
    }

    return $subscription->plan->{"name_$lang"} ?: $subscription->plan->name_en;
};
$subscriptionStatus = static function ($status) use ($t) {
    return (int)$status === 1 ? $t('profile_active') : $t('profile_inactive');
};

$this->title = $t('profile_page_title');
$statusText = $user->status == User::STATUS_ACTIVE ? $t('profile_active') : $t('profile_inactive');
$avatar = $user->image ? "$base/uploads/users/$user->image" : "$base/img/profile-avatar.jpg";
$passwordHasErrors = $passwordModel->hasErrors() ? 'true' : 'false';
?>

<div class="container profile-breadcrumb">
    <ol class="breadcrumb flex-wrap">
        <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode($t('home')) ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
    </ol>
</div>

<div id="page-content" class="meros-modern-page meros-profile-page">
    <section class="meros-profile-hero">
        <div class="container">
            <div class="meros-profile-hero-card reveal-section">
                <div class="meros-profile-hero-copy">
                    <span class="meros-kicker"><?= Html::encode($t('profile_tab_profile')) ?></span>
                    <h1><?= Html::encode($this->title) ?></h1>
                    <p><?= Html::encode($profileModel->email) ?></p>
                </div>
                <div class="meros-profile-user-card">
                    <img class="meros-profile-avatar" src="<?= Html::encode($avatar) ?>" alt="<?= Html::encode($profileModel->fullname) ?>">
                    <div>
                        <strong><?= Html::encode($profileModel->fullname) ?></strong>
                        <span>@<?= Html::encode($profileModel->username) ?></span>
                    </div>
                    <span class="meros-status-pill">
                        <i class="fa fa-check-circle"></i><?= Html::encode($statusText) ?>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <div class="container profile-page pb-5">

        <ul class="nav profile-tabs meros-profile-tabs" id="profile-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-profile-link" data-bs-toggle="tab" data-bs-target="#tab-profile" type="button" role="tab">
                    <?= Html::encode($t('profile_tab_profile')) ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-subscription-link" data-bs-toggle="tab" data-bs-target="#tab-subscription" type="button" role="tab">
                    <?= Html::encode($t('profile_tab_subscription')) ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-change-password-link" data-bs-toggle="tab" data-bs-target="#tab-change-password" type="button" role="tab">
                    <?= Html::encode($t('profile_tab_password')) ?>
                </button>
            </li>
        </ul>

        <div class="tab-content profile-tab-content">
            <div class="tab-pane fade show active" id="tab-profile" role="tabpanel">
                <div class="profile-card meros-profile-card">
                    <div class="profile-card-body p-4 p-lg-5">
                        <div class="row g-4">
                            <div class="col-lg-3">
                                <div class="meros-profile-side">
                                    <img class="profile-avatar-fixed meros-profile-avatar-lg mb-3" src="<?= Html::encode($avatar) ?>" alt="<?= Html::encode($profileModel->fullname) ?>">
                                    <h3 class="h5 mb-1"><?= Html::encode($profileModel->fullname) ?></h3>
                                    <div class="text-muted">@<?= Html::encode($profileModel->username) ?></div>
                                    <div class="small text-muted mt-3">
                                        <?= Html::encode($t('profile_member_since')) ?>:
                                        <?= $formatDate($user->created_at) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <span class="meros-kicker"><?= Html::encode($t('profile_settings_title')) ?></span>
                                <h2 class="h4 mb-4"><?= Html::encode($t('profile_settings_title')) ?></h2>
                                <?php $form = ActiveForm::begin(['id' => 'profile-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <?= $form->field($profileModel, 'fullname')->textInput(['class' => 'form-control form-control-lg']) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($profileModel, 'username')->textInput(['class' => 'form-control form-control-lg']) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($profileModel, 'email')->input('email', ['class' => 'form-control form-control-lg']) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($profileModel, 'phone')->textInput(['class' => 'form-control form-control-lg']) ?>
                                    </div>
                                    <div class="col-12">
                                        <?= $form->field($profileModel, 'address')->textInput(['class' => 'form-control form-control-lg']) ?>
                                    </div>
                                    <div class="col-12">
                                        <div class="meros-profile-upload">
                                            <img src="<?= Html::encode($avatar) ?>" alt="<?= Html::encode($profileModel->fullname) ?>">
                                            <div class="flex-grow-1">
                                                <?= $form->field($profileModel, 'imageFile')->fileInput([
                                                    'class' => 'form-control form-control-lg',
                                                    'accept' => 'image/jpeg,image/png,image/webp',
                                                ]) ?>
                                                <p class="mb-0"><?= Html::encode($t('profile_photo_hint')) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <?= Html::submitButton($t('profile_save_button'), ['class' => 'btn btn-primary meros-primary-btn']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-subscription" role="tabpanel">
                <div class="profile-card meros-profile-card mb-4">
                    <div class="profile-card-body p-4 p-lg-5">
                        <span class="meros-kicker"><?= Html::encode($t('profile_tab_subscription')) ?></span>
                        <h2 class="h4 mb-4"><?= Html::encode($t('profile_subscription_title')) ?></h2>

                        <?php if ($currentSubscription): ?>
                            <div class="row g-3">
                                <div class="col-md-6 col-xl-4">
                                    <div class="profile-info-tile">
                                        <span><?= Html::encode($t('profile_plan')) ?></span>
                                        <strong><?= Html::encode($planName($currentSubscription)) ?></strong>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="profile-info-tile">
                                        <span><?= Html::encode($t('profile_status')) ?></span>
                                        <strong><?= Html::encode($subscriptionStatus($currentSubscription->status)) ?></strong>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="profile-info-tile">
                                        <span><?= Html::encode($t('profile_amount')) ?></span>
                                        <strong><?= $formatAmount($currentSubscription->amount, $currentSubscription->currency_code) ?></strong>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="profile-info-tile">
                                        <span><?= Html::encode($t('profile_start_date')) ?></span>
                                        <strong><?= $formatDate($currentSubscription->start_date) ?></strong>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="profile-info-tile">
                                        <span><?= Html::encode($t('profile_expires_date')) ?></span>
                                        <strong><?= $formatDate($currentSubscription->expires_date) ?></strong>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="profile-info-tile">
                                        <span><?= Html::encode($t('profile_subscription_key')) ?></span>
                                        <strong><?= Html::encode($currentSubscription->subscription_key ?: '-') ?></strong>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="profile-empty-state meros-profile-empty mb-0">
                                <i class="fa fa-credit-card text-primary"></i>
                                <span><?= Html::encode($t('profile_no_subscription')) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="profile-card meros-profile-card">
                    <div class="profile-card-body p-4 p-lg-5">
                        <span class="meros-kicker"><?= Html::encode($t('profile_subscription_history')) ?></span>
                        <h2 class="h4 mb-4"><?= Html::encode($t('profile_subscription_history')) ?></h2>

                        <?php if ($subscriptionHistory): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle profile-history-table mb-0">
                                    <thead>
                                    <tr>
                                        <th><?= Html::encode($t('profile_plan')) ?></th>
                                        <th><?= Html::encode($t('profile_status')) ?></th>
                                        <th><?= Html::encode($t('profile_start_date')) ?></th>
                                        <th><?= Html::encode($t('profile_expires_date')) ?></th>
                                        <th><?= Html::encode($t('profile_amount')) ?></th>
                                        <th><?= Html::encode($t('profile_payment_provider')) ?></th>
                                        <th><?= Html::encode($t('profile_transaction')) ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($subscriptionHistory as $historyItem): ?>
                                        <tr>
                                            <td><?= Html::encode($planName($historyItem)) ?></td>
                                            <td>
                                                <span class="badge <?= (int)$historyItem->status === 1 ? 'bg-success' : 'bg-secondary' ?>">
                                                    <?= Html::encode($subscriptionStatus($historyItem->status)) ?>
                                                </span>
                                            </td>
                                            <td><?= $formatDate($historyItem->start_date) ?></td>
                                            <td><?= $formatDate($historyItem->expires_date) ?></td>
                                            <td><?= $formatAmount($historyItem->amount, $historyItem->currency_code) ?></td>
                                            <td><?= Html::encode($historyItem->payment_provider ?: '-') ?></td>
                                            <td><?= Html::encode($historyItem->payment_transaction_id ?: '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="profile-empty-state meros-profile-empty mb-0"><i class="fa fa-list-alt"></i><span><?= Html::encode($t('profile_no_history')) ?></span></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-change-password" role="tabpanel">
                <div class="profile-card meros-profile-card">
                    <div class="profile-card-body p-4 p-lg-5">
                        <span class="meros-kicker"><?= Html::encode($t('profile_tab_password')) ?></span>
                        <h2 class="h4 mb-4"><?= Html::encode($t('profile_security_title')) ?></h2>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <?php $form = ActiveForm::begin(['id' => 'change-password-form']); ?>
                                <?= $form->field($passwordModel, 'currentPassword')->passwordInput(['class' => 'form-control form-control-lg']) ?>
                                <?= $form->field($passwordModel, 'newPassword')->passwordInput(['class' => 'form-control form-control-lg']) ?>
                                <?= $form->field($passwordModel, 'repeatPassword')->passwordInput(['class' => 'form-control form-control-lg']) ?>
                                <?= Html::submitButton($t('profile_change_password_button'), ['class' => 'btn btn-primary meros-primary-btn']) ?>
                                <?php ActiveForm::end(); ?>
                            </div>
                            <div class="col-lg-6">
                                <div class="profile-reset-box meros-profile-reset h-100">
                                    <h3 class="h5"><?= Html::encode($t('profile_reset_password_button')) ?></h3>
                                    <p><?= Html::encode($t('profile_reset_password_text')) ?></p>
                                    <a class="btn btn-outline-primary meros-secondary-btn" href="<?= Url::to(['site/request-password-reset']) ?>">
                                        <?= Html::encode($t('profile_reset_password_button')) ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
(function () {
    var hash = $passwordHasErrors ? '#tab-change-password' : window.location.hash;
    if (!hash) {
        return;
    }
    var trigger = document.querySelector('[data-bs-target="' + hash + '"]');
    if (trigger && window.bootstrap) {
        new bootstrap.Tab(trigger).show();
    }
}());
JS);
?>
