<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Faq $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="faq-form">
   
   <?php $form = ActiveForm::begin(['action'=>$url]); ?>
    <div class="row">
        <div class="col-md-4">
           <?= $form->field($model, 'question_ru')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'answer_ru')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'question_en')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'answer_en')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'question_uz')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'answer_uz')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-12 mt-4">
            <div class="form-group">
               <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
            </div>
        </div>
    </div>

    
   
   <?php ActiveForm::end(); ?>

</div>
