<?php

use common\models\CourseLessons;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CourseLessons $model */
/** @var yii\widgets\ActiveForm $form */
$lesson = CourseLessons::find()->where(['course_id' => $course_id])->orderBy(['id' => SORT_DESC])->one();
if ($lesson) {
   $sort = $lesson->sort + 100;
} else {
   $sort = 100;
}
?>

<div class="course-lessons-form">
   
   <?php $form = ActiveForm::begin(['action' => $url,'options' => ['enctype' => 'multipart/form-data']]); ?>
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
        <div class="col-md-4">
           <?= $form->field($model, 'desc_ru')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'desc_en')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'desc_uz')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'video')->fileInput() ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'sort')->textInput(['value' => $sort]) ?>
        </div>
        <div class="col-md-4 mt-4">
            <div class="form-group">
               <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
            </div>
        </div>
    </div>
   
   <?php ActiveForm::end(); ?>

</div>
