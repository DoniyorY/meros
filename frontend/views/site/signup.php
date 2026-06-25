<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$params = Yii::$app->params;
$lang = Yii::$app->language;
$t = static function ($key) use ($params, $lang) {
    return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};
$this->title = $t('signup_title');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container py-5">
    <div class="site-signup">
        <h1><?= Html::encode($this->title) ?></h1>

        <p><?= Html::encode($t('signup_intro')) ?></p>

        <div class="row g-4">
            <div class="col-lg-5">
               <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
               
               <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
               
               <?= $form->field($model, 'email') ?>
               
               <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="mb-3">
                   <?= Html::submitButton($t('signup_button'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
               
               <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
