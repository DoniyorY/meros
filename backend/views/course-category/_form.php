<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CourseCategory $model */
/** @var yii\widgets\ActiveForm $form */
if (Yii::$app->controller->action->id == 'index') {
    $url = \yii\helpers\Url::to(['create']);
}else{
    $url = \yii\helpers\Url::to(['update','id'=>$model->id]);
}
?>

<div class="course-category-form">

    <?php $form = ActiveForm::begin(['action' => $url]); ?>

    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>

    <div class="form-group mt-2">
        <?= Html::submitButton('Save', ['class' => 'w-100 btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
