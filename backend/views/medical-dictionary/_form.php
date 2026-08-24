<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\MedicalDictionary $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="medical-dictionary-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_id')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug_uz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc_uz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content_ru')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content_en')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content_uz')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'seo_title_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_title_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_title_uz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_desc_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_desc_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_desc_uz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
