<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CourseCategory;
use common\models\Mentors;

/** @var yii\web\View $this */
/** @var common\models\Courses $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="courses-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <?=$form->field($model,'page_type')->dropDownList(Yii::$app->params['page_type'],['prompt'=>'Select the type'])?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(CourseCategory::find()->all(), 'id', 'name_en'), ['prompt' => 'Select the Category']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'mentor_id')->dropDownList(ArrayHelper::map(Mentors::findAll(['status' => 1]), 'id', 'fullname'), ['prompt' => 'Select the Category']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'title_ru')->textInput() ?>
            <?= $form->field($model, 'desc_ru')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'title_en')->textInput() ?>
            <?= $form->field($model, 'desc_en')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'title_uz')->textInput() ?>
            <?= $form->field($model, 'desc_uz')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-12">
            <?=$form->field($model,'lvl')->textInput(['maxlength' => true,'placeholder' => 'Enter the recommended level'])?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'preview_video_link')->textInput(['maxlength' => true]) ?>
        </div>
        <hr>
        <div class="col-md-6 mt-4">
            <?=$form->field($model,'icon')->fileInput()?>
        </div>
        <div class="col-md-6 mt-4">
            <?= $form->field($model, 'imageFile')->fileInput() ?>
        </div>
        <hr>
        <div class="col-md-6 mt-4">
           <?= $form->field($model, 'syllabus')->fileInput() ?>
        </div>
        <div class="col-md-6 mt-4">
           <?=$form->field($model,'flyer')->fileInput()?>
        </div>
        <div class="col-md-12 mt-2">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
