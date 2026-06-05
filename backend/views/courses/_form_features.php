<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CourseFeatures $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="course-features-form">
   
   <?php $form = ActiveForm::begin(['action' => $url]); ?>
    <div class="row">
        <div class="col-md-4">
           <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'desc_en')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'desc_ru')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'desc_uz')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-12 mt-2">
            <div class="form-group">
               <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
            </div>
        </div>
    </div>
   
   <?php ActiveForm::end(); ?>

</div>
