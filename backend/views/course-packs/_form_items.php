<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CourseCategory;
/** @var yii\web\View $this */
/** @var common\models\CoursePackItems $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="course-pack-items-form">

    <?php $form = ActiveForm::begin(['action' => $url]); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'course_category_id')->dropDownList(\yii\helpers\ArrayHelper::map(CourseCategory::find()->all(),'id','name_en'),['prompt'=>'Select The Category!!!']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'course_id')->textInput() ?>
        </div>
        <div class="col-md-4 mt-4">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
