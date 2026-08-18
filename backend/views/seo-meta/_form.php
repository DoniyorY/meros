<?php

use common\models\SeoMeta;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\SeoMeta $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="seo-meta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'entity_type')->dropDownList([
       SeoMeta::TYPE_COURSE => 'Courses',
       SeoMeta::TYPE_COURSE_CATEGORY => 'Course Categories',
       SeoMeta::TYPE_POST => 'Posts',
       SeoMeta::TYPE_EVENT => 'Events',
    ]) ?>

    <?= $form->field($model, 'entity_id')->textInput() ?>

    <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_uz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description_ru')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description_en')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description_uz')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'h1_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'h1_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'h1_uz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text_ru')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'text_en')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'text_uz')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'canonical')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'og_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'robots')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
