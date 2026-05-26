<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Mentors $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="mentors-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'instagram_link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'linked_in_link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'facebook_link')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6 mt-4">
            <?= $form->field($model, 'imageFile')->fileInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6 mt-4">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
