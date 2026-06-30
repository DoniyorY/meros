<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ResetPasswordForm $model */

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
<div class="container py-5"><div class="site-reset-password auth-narrow">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::encode($t('password_reset_new_text')) ?></p>

    <div class="row g-4">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                <div class="mb-3">
                    <?= Html::submitButton($t('password_reset_save_button'), ['class' => 'btn btn-color-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</div>
</div>
