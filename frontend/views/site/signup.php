<?php

/** @var yii\web\View $this */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$params = Yii::$app->params;
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
$t = static function ($key) use ($params, $lang) {
    return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};

$this->title = $t('signup_title');
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
                    <h1><?= Html::encode($t('signup_title')) ?></h1>
                    <p><?= Html::encode($t('signup_intro')) ?></p>
                    <ul>
                        <li><i class="fa fa-user-plus"></i><?= Html::encode($t('login_feature_profile')) ?></li>
                        <li><i class="fa fa-credit-card"></i><?= Html::encode($t('login_feature_subscription')) ?></li>
                        <li><i class="fa fa-lock"></i><?= Html::encode($t('login_feature_security')) ?></li>
                    </ul>
                </div>
                <div class="auth-form-panel meros-auth-panel">
                    <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['class' => 'auth-form']]); ?>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <?= $form->field($model, 'email')->textInput([
                                'autofocus' => true,
                                'type' => 'email',
                                'placeholder' => $t('profile_email'),
                            ]) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'first_name')->textInput([
                                'placeholder' => $t('first_name'),
                            ]) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'last_name')->textInput([
                                'placeholder' => $t('last_name'),
                            ]) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'username')->textInput([
                                'placeholder' => $t('profile_username'),
                            ]) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'phone')->textInput([
                                'placeholder' => $t('profile_phone'),
                            ]) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'password')->passwordInput([
                                'placeholder' => $t('login_password'),
                            ]) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'password_confirm')->passwordInput([
                                'placeholder' => $t('confirm_password'),
                            ]) ?>
                        </div>
                    </div>

                    <div class="auth-links">
                        <span><?= Html::encode($t('signup_login_prompt')) ?></span>
                        <?= Html::a($t('login'), ['site/login']) ?>
                    </div>

                    <?= Html::submitButton($t('signup_button'), [
                        'class' => 'btn btn-primary meros-primary-btn auth-submit',
                        'name' => 'signup-button',
                    ]) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </section>
</div>
