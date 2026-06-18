<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Events $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="events-form">

   <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-md-4">
           <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'desc_ru')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'content_ru')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'desc_en')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'content_en')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'desc_uz')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'content_uz')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-6 mt-4">
            <?php if (!$model->isNewRecord && $model->image): ?>
                <div class="mb-3">
                    <?= Html::img(Yii::getAlias('@web') . '/../uploads/events/' . $model->image, [
                        'class' => 'img-thumbnail',
                        'style' => 'max-width: 220px; max-height: 160px;',
                        'alt' => $model->name_en,
                    ]) ?>
                </div>
            <?php endif; ?>
            <?= $form->field($model, 'imageFile')->fileInput() ?>
        </div>
        <div class="col-md-6 mt-4">
            <?= $form->field($model, 'video_link')->textInput([
                'maxlength' => true,
                'placeholder' => 'https://www.youtube.com/watch?v=...',
            ]) ?>
        </div>
        <div class="col-md-12 mt-3">
            <div class="form-group">
               <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
            </div>
        </div>
    </div>

   <?php ActiveForm::end(); ?>

</div>
