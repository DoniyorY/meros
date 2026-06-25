<?php

/** @var yii\web\View$this  */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ResetPasswordForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$params = Yii::$app->params;
$lang = Yii::$app->language;
$t = static function ($key) use ($params, $lang) {
    return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};
$this->title = $t('resend_verification_title');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container py-5"><div class="site-resend-verification-email">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::encode($t('resend_verification_text')) ?></p>

    <div class="row g-4">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <div class="mb-3">
                <?= Html::submitButton($t('send'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</div>
