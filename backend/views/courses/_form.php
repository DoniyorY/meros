<?php

use common\models\CourseCategory;
use common\models\Mentors;
use kartik\editors\Summernote;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Courses $model */

$languages = [
    'ru' => ['label' => 'Русский', 'flag' => 'RU'],
    'en' => ['label' => 'English', 'flag' => 'EN'],
    'uz' => ['label' => "O‘zbekcha", 'flag' => 'UZ'],
];
$categories = ArrayHelper::map(CourseCategory::find()->orderBy(['name_en' => SORT_ASC])->all(), 'id', 'name_en');
$mentors = ArrayHelper::map(Mentors::find()->where(['status' => 1])->orderBy(['fullname' => SORT_ASC])->all(), 'id', 'fullname');
$courseImageUrl = $model->image ? Yii::getAlias('@web') . '/../uploads/courses/' . $model->image : null;
$coverImageUrl = $model->course_image ? Yii::getAlias('@web') . '/../uploads/courses/courseImage/' . $model->course_image : null;
$iconUrl = $model->course_icons ? Yii::getAlias('@web') . '/../uploads/course_icons/' . $model->course_icons : null;
?>

<div class="courses-form">
    <?php $form = ActiveForm::begin([
        'id' => 'course-form',
        'enableClientValidation' => true,
        'validateOnBlur' => true,
        'validateOnChange' => true,
        'options' => ['enctype' => 'multipart/form-data', 'novalidate' => true],
        'fieldConfig' => [
            'options' => ['class' => 'mb-3'],
            'labelOptions' => ['class' => 'form-label fw-medium'],
            'inputOptions' => ['class' => 'form-control'],
            'errorOptions' => ['class' => 'invalid-feedback d-block'],
        ],
    ]); ?>

    <?= $form->errorSummary($model, [
        'class' => 'alert alert-danger shadow-sm',
        'header' => '<div class="fw-semibold mb-1"><i class="ri-error-warning-line me-1"></i>Please check the highlighted fields:</div>',
    ]) ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="card-title mb-1"><i class="ri-settings-3-line text-primary me-2"></i>General settings</h5>
            <p class="text-muted mb-0 small">Set the audience, category and people responsible for the course.</p>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-4">
                    <?= $form->field($model, 'page_type')->dropDownList(Yii::$app->params['page_type'], ['prompt' => 'Select an audience…']) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'category_id')->dropDownList($categories, ['prompt' => 'Select a category…']) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'mentor_id')->dropDownList($mentors, ['prompt' => 'Select a mentor…']) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'lvl')->textInput(['maxlength' => true, 'placeholder' => 'For example: Beginner (A1)']) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'preview_video_link')->textInput(['maxlength' => true, 'type' => 'url', 'placeholder' => 'https://www.youtube.com/watch?v=…']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom pt-3 px-3 pb-0">
            <h5 class="card-title mb-3"><i class="ri-translate-2 text-primary me-2"></i>Localized content</h5>
            <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                <?php foreach ($languages as $code => $language): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link<?= $code === 'en' ? ' active' : '' ?>" data-bs-toggle="tab"
                                data-bs-target="#course-language-<?= $code ?>" type="button" role="tab">
                            <span class="badge bg-light text-dark me-1"><?= $language['flag'] ?></span><?= Html::encode($language['label']) ?>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <?php foreach ($languages as $code => $language): ?>
                    <div class="tab-pane fade<?= $code === 'en' ? ' show active' : '' ?>" id="course-language-<?= $code ?>" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <?= $form->field($model, "name_{$code}")->textInput(['maxlength' => true, 'placeholder' => 'Course name in ' . $language['label']]) ?>
                            </div>
                            <div class="col-lg-6">
                                <?= $form->field($model, "title_{$code}")->textInput(['maxlength' => true, 'placeholder' => 'Short headline in ' . $language['label']]) ?>
                            </div>
                            <div class="col-12">
                                <?= $form->field($model, "desc_{$code}")->widget(Summernote::class, [
                                    'useKrajeePresets' => true,
                                    'container' => ['class' => 'mb-0'],
                                    'pluginOptions' => [
                                        'height' => 260,
                                        'placeholder' => 'Describe the course in ' . $language['label'] . '…',
                                        'toolbar' => [
                                            ['style', ['style']],
                                            ['font', ['bold', 'italic', 'underline', 'clear']],
                                            ['para', ['ul', 'ol', 'paragraph']],
                                            ['insert', ['link', 'table']],
                                            ['view', ['fullscreen', 'codeview']],
                                        ],
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="card-title mb-1"><i class="ri-image-line text-primary me-2"></i>Images</h5>
            <p class="text-muted mb-0 small">JPG, PNG or GIF, up to 5 MB per file. Existing files remain unchanged when no replacement is selected.</p>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php foreach ([
                    ['attribute' => 'icon', 'title' => 'Course icon', 'url' => $iconUrl],
                    ['attribute' => 'imageFile', 'title' => 'Listing image', 'url' => $courseImageUrl],
                    ['attribute' => 'courseImage', 'title' => 'Course cover', 'url' => $coverImageUrl],
                ] as $image): ?>
                    <div class="col-lg-4">
                        <div class="border rounded p-3 h-100">
                            <h6 class="mb-3"><?= Html::encode($image['title']) ?></h6>
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3 overflow-hidden" style="height: 150px">
                                <?php if ($image['url']): ?>
                                    <?= Html::img($image['url'], ['class' => 'img-fluid', 'style' => 'max-height: 150px', 'alt' => $image['title']]) ?>
                                <?php else: ?>
                                    <div class="text-center text-muted"><i class="ri-image-add-line fs-32 d-block"></i><span class="small">No image uploaded</span></div>
                                <?php endif; ?>
                            </div>
                            <?= $form->field($model, $image['attribute'])->fileInput(['accept' => 'image/png,image/jpeg,image/gif'])->label(false) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="card-title mb-1"><i class="ri-file-download-line text-primary me-2"></i>Downloadable materials</h5>
            <p class="text-muted mb-0 small">Upload PDF or Word documents for prospective students.</p>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="border rounded p-3 h-100">
                        <?php if ($model->syllabus_file): ?><div class="small text-success mb-2"><i class="ri-check-line me-1"></i>Current file: <?= Html::encode($model->syllabus_file) ?></div><?php endif; ?>
                        <?= $form->field($model, 'syllabus')->fileInput(['accept' => '.pdf,.doc,.docx']) ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="border rounded p-3 h-100">
                        <?php if ($model->flyer_file): ?><div class="small text-success mb-2"><i class="ri-check-line me-1"></i>Current file: <?= Html::encode($model->flyer_file) ?></div><?php endif; ?>
                        <?= $form->field($model, 'flyer')->fileInput(['accept' => '.pdf,.doc,.docx']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mb-4">
        <?= Html::a('<i class="ri-close-line me-1"></i>Cancel', $model->isNewRecord ? ['index'] : ['view', 'id' => $model->id], ['class' => 'btn btn-light px-4']) ?>
        <?= Html::submitButton('<i class="ri-save-3-line me-1"></i>' . ($model->isNewRecord ? 'Create course' : 'Save changes'), ['class' => 'btn btn-success px-4']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
