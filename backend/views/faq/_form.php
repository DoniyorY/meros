<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Faq $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="faq-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'course_id')->textInput() ?>

    <?= $form->field($model, 'page_id')->textInput() ?>

    <?= $form->field($model, 'question_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'question_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'question_uz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'answer_ru')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'answer_en')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'answer_uz')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
