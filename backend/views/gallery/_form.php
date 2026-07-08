<?php

use common\models\Gallery;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Gallery $model */
/** @var yii\widgets\ActiveForm $form */

$isNewRecord = $model->isNewRecord;
$currentImageUrl = !$isNewRecord && $model->image
    ? Yii::getAlias('@web') . '/uploads/gallery/' . $model->image
    : null;

$this->registerCss(<<<CSS
.gallery-editor-card {
    max-width: 920px;
    border: 0;
    border-radius: 18px;
    box-shadow: 0 16px 45px rgba(15, 23, 42, 0.08);
    overflow: hidden;
}
.gallery-editor-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 24px 28px;
    background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 100%);
    border-bottom: 1px solid #e8eef7;
}
.gallery-editor-title {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #172033;
}
.gallery-editor-subtitle {
    margin: 6px 0 0;
    color: #667085;
    font-size: 14px;
}
.gallery-editor-body {
    padding: 28px;
    background: #fff;
}
.gallery-upload-box {
    position: relative;
    padding: 28px;
    border: 2px dashed #b9d5f5;
    border-radius: 16px;
    background: #f8fbff;
    text-align: center;
    transition: border-color .2s ease, background-color .2s ease;
}
.gallery-upload-box:hover {
    border-color: #4f9bea;
    background: #f2f8ff;
}
.gallery-upload-icon {
    width: 58px;
    height: 58px;
    margin: 0 auto 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #2f80ed;
    background: #e8f3ff;
    font-size: 24px;
}
.gallery-upload-title {
    margin: 0 0 8px;
    font-weight: 700;
    color: #172033;
}
.gallery-upload-hint {
    margin: 0 0 18px;
    color: #667085;
    font-size: 13px;
}
.gallery-upload-box .form-group {
    margin-bottom: 0;
}
.gallery-current-image {
    display: grid;
    grid-template-columns: minmax(180px, 260px) 1fr;
    gap: 22px;
    align-items: center;
    padding: 18px;
    border: 1px solid #e8eef7;
    border-radius: 16px;
    background: #fbfdff;
    margin-bottom: 22px;
}
.gallery-current-image img {
    width: 100%;
    aspect-ratio: 4 / 3;
    object-fit: cover;
    border-radius: 14px;
    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12);
}
.gallery-current-image h3 {
    margin: 0 0 8px;
    font-size: 18px;
    font-weight: 700;
    color: #172033;
}
.gallery-current-image p {
    margin: 0;
    color: #667085;
}
.gallery-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    margin-top: 22px;
}
.gallery-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 24px;
    margin-top: 24px;
    border-top: 1px solid #eef2f7;
}
@media (max-width: 767px) {
    .gallery-editor-header,
    .gallery-editor-body {
        padding: 20px;
    }
    .gallery-current-image,
    .gallery-form-grid {
        grid-template-columns: 1fr;
    }
    .gallery-form-actions {
        flex-direction: column-reverse;
    }
    .gallery-form-actions .btn {
        width: 100%;
    }
}
CSS);
?>

<div class="gallery-form">
    <div class="panel panel-default gallery-editor-card">
        <div class="gallery-editor-header">
            <div>
                <h2 class="gallery-editor-title">
                    <?= Html::encode($isNewRecord ? 'Upload gallery photos' : 'Edit gallery photo') ?>
                </h2>
                <p class="gallery-editor-subtitle">
                    <?= Html::encode($isNewRecord
                        ? 'Select one or more photos for the About page gallery.'
                        : 'Update visibility, page binding, or replace the current image.') ?>
                </p>
            </div>
            <span class="label label-primary">
                <?= Html::encode($isNewRecord ? 'Multi upload' : 'Single photo') ?>
            </span>
        </div>

        <div class="gallery-editor-body">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

            <?php if ($isNewRecord): ?>
                <div class="gallery-upload-box">
                    <div class="gallery-upload-icon" aria-hidden="true">☁</div>
                    <p class="gallery-upload-title">Choose gallery images</p>
                    <p class="gallery-upload-hint">You can upload up to 20 JPG, PNG, GIF or WEBP files at once.</p>
                    <?= $form->field($model, 'imageFiles[]')->fileInput([
                        'multiple' => true,
                        'accept' => 'image/*',
                    ])->label(false) ?>
                </div>
            <?php else: ?>
                <?php if ($currentImageUrl): ?>
                    <div class="gallery-current-image">
                        <?= Html::img($currentImageUrl, [
                            'alt' => $model->image,
                        ]) ?>
                        <div>
                            <h3>Current image</h3>
                            <p><?= Html::encode($model->image) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="gallery-upload-box">
                    <div class="gallery-upload-icon" aria-hidden="true">↻</div>
                    <p class="gallery-upload-title">Replace image</p>
                    <p class="gallery-upload-hint">Leave this field empty if you want to keep the current photo.</p>
                    <?= $form->field($model, 'imageFile')->fileInput(['accept' => 'image/*'])->label(false) ?>
                </div>
            <?php endif; ?>

            <div class="gallery-form-grid">
                <?= $form->field($model, 'page_id')->textInput([
                    'placeholder' => 'Optional page ID',
                ]) ?>

                <?= $form->field($model, 'status')->dropDownList([
                    Gallery::STATUS_ACTIVE => 'Active',
                    Gallery::STATUS_INACTIVE => 'Inactive',
                ]) ?>
            </div>

            <div class="gallery-form-actions">
                <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton($isNewRecord ? 'Upload photos' : 'Save changes', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
