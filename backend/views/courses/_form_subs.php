<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\SubscriptionPlans $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="subscription-plans-form">
   
   <?php $form = ActiveForm::begin(['action' => $url]); ?>
   <?= $form->field($model, 'course_id')->hiddenInput(['value' => $course_id])->label(false) ?>
    <div class="row">
        <div class="col-md-4">
           <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
           <?= $form->field($model, 'price')->textInput() ?>
        </div>
        <div class="col-md-6">
           <?= $form->field($model, 'duration_days')->textInput() ?>
        </div>
        <div class="col-md-12 mt-3">
            <div class="form-group">
               <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
   <?php ActiveForm::end(); ?>

</div>
