<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Mentors $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="mentors-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>Main information</strong>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>Position</strong>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <?= $form->field($model, 'position_ru')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'position_en')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'position_uz')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>Descriptions</strong>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <?= $form->field($model, 'desc_ru')->textarea(['rows' => 5, 'maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'desc_en')->textarea(['rows' => 5, 'maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'desc_uz')->textarea(['rows' => 5, 'maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>Media and social links</strong>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <?= $form->field($model, 'imageFile')->fileInput() ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'avatarFile')->fileInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'instagram_link')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'linked_in_link')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'facebook_link')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-end">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success px-5']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
