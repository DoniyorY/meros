<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\PostCategory;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Posts $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="posts-form">
    
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'category_id')
                ->dropDownList(ArrayHelper::map(PostCategory::findAll(['status' => 1]), 'id', 'name_en'),
                    [
                        'prompt' => 'Select Category',
                    ]
                ) ?>
        </div>
        <hr style="margin-top: 10px;">
        <div class="col-md-4">
            <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'desc_ru')->textarea(['rows' => 6]) ?>
            <?= $form->field($model, 'content_ru')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'desc_en')->textarea(['rows' => 6]) ?>
            <?= $form->field($model, 'content_en')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'desc_uz')->textarea(['rows' => 6]) ?>
            <?= $form->field($model, 'content_uz')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-6 mt-4">
            <?= $form->field($model, 'imageFile')->fileInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6 mt-4">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
