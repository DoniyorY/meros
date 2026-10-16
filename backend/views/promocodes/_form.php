<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PromocodesCreateForm $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="promocodes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'quantity')->input('number', [
        'min' => 1, 'max' => $model::MAX_QUANTITY, 'step' => 1,
    ])->hint('От 1 до ' . $model::MAX_QUANTITY . ' промокодов за один раз.') ?>

    <?= $form->field($model, 'discount')->input('number', [
        'min' => 1, 'max' => 99, 'step' => 1,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-light']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
