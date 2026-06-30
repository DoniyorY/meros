<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\PasswordResetRequestForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$params = Yii::$app->params;
$lang = Yii::$app->language;
$t = static function ($key) use ($params, $lang) {
    return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};
$this->title = $t('password_reset_title');
?>

<div id="page-content">
<div class="container py-5"><div class="site-request-password-reset auth-narrow">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Html::encode($t('password_reset_request_text')) ?></p>

    <div class="row g-4">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="mb-3">
                    <?= Html::submitButton($t('password_reset_send_button'), ['class' => 'btn btn-color-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</div>
</div>
