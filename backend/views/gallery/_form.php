<?php

use common\models\Gallery;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Gallery $model */
/** @var yii\widgets\ActiveForm $form */

$isNewRecord = $model->isNewRecord;
?>

<div class="gallery-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'page_id')->textInput() ?>

    <?php if ($isNewRecord): ?>
        <?= $form->field($model, 'imageFiles[]')->fileInput([
            'multiple' => true,
            'accept' => 'image/*',
        ])->label('Images') ?>
    <?php else: ?>
        <?php if ($model->image): ?>
            <div class="form-group">
                <?= Html::img(Yii::getAlias('@web') . '/uploads/gallery/' . $model->image, [
                    'class' => 'img-thumbnail',
                    'style' => 'max-width: 240px; margin-bottom: 10px;',
                    'alt' => $model->image,
                ]) ?>
            </div>
        <?php endif; ?>
        <?= $form->field($model, 'imageFile')->fileInput(['accept' => 'image/*']) ?>
    <?php endif; ?>

    <?= $form->field($model, 'status')->dropDownList([
        Gallery::STATUS_ACTIVE => 'Active',
        Gallery::STATUS_INACTIVE => 'Inactive',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
