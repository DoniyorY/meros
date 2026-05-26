<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CoursePackItems $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="course-pack-items-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pack_id')->textInput() ?>

    <?= $form->field($model, 'course_category_id')->textInput() ?>

    <?= $form->field($model, 'course_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
