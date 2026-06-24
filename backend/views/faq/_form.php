<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Faq $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="faq-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'course_id')->widget(\kartik\select2\Select2::class, [
                'data' => ArrayHelper::map(\common\models\Courses::find()->all(), 'id', 'name_en'),
                'language' => 'en',
                'options' => ['placeholder' => 'Select course'],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'page_id')->textInput() ?>
        </div>
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
        <div class="col-md-12 mt-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
