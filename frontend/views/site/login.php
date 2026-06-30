<?php

/** @var yii\web\View $this */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$params = Yii::$app->params;
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
$t = static function ($key) use ($params, $lang) {
    return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};

$this->title = $t('login_title');
?>

<div class="container">
    <ol class="breadcrumb flex-wrap">
        <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode($t('home')) ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
    </ol>
</div>

<div id="page-content" class="meros-modern-page meros-auth-page">
    <section class="auth-page meros-section reveal-section">
        <div class="container">
            <div class="auth-shell meros-auth-shell">
                <div class="auth-brand meros-auth-brand">
                    <img src="<?= Html::encode("$base/logo-white.png") ?>" alt="<?= Html::encode(Yii::$app->name) ?>">
                    <h1><?= Html::encode($t('login_title')) ?></h1>
                    <p><?= Html::encode($t('login_intro')) ?></p>
                    <ul>
                        <li><i class="fa fa-user"></i><?= Html::encode($t('login_feature_profile')) ?></li>
                        <li><i class="fa fa-credit-card"></i><?= Html::encode($t('login_feature_subscription')) ?></li>
                        <li><i class="fa fa-lock"></i><?= Html::encode($t('login_feature_security')) ?></li>
                    </ul>
                </div>
                <div class="auth-form-panel meros-auth-panel">
                    <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'auth-form']]); ?>

                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'placeholder' => $t('login_username'),
                    ])->label($t('login_username')) ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'placeholder' => $t('login_password'),
                    ])->label($t('login_password')) ?>

                    <?= $form->field($model, 'rememberMe')->checkbox()->label($t('login_remember_me')) ?>

                    <div class="auth-links">
                        <span><?= Html::encode($t('login_forgot_prefix')) ?></span>
                        <?= Html::a($t('login_reset_link'), ['site/request-password-reset']) ?>
                        <span><?= Html::encode($t('login_resend_prefix')) ?></span>
                        <?= Html::a($t('login_resend_link'), ['site/resend-verification-email']) ?>
                    </div>

                    <?= Html::submitButton($t('login'), [
                        'class' => 'btn btn-primary meros-primary-btn auth-submit',
                        'name' => 'login-button',
                    ]) ?>

                    <?= Html::a($t('signup_button'), ['site/signup'], [
                        'class' => 'btn btn-outline-primary auth-submit mt-3 w-100',
                    ]) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </section>
</div>
