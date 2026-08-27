<?php

use kartik\editors\Summernote;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\MedicalDictionary $model */

$languages = [
    'ru' => ['label' => 'Русский', 'flag' => 'RU'],
    'en' => ['label' => 'English', 'flag' => 'EN'],
    'uz' => [ 'label' => "O‘zbekcha", 'flag' => 'UZ'],
];
?>

<div class="medical-dictionary-form">
    <?php $form = ActiveForm::begin([
        'id' => 'medical-dictionary-form',
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'validateOnBlur' => true,
        'validateOnChange' => true,
        'options' => ['novalidate' => true],
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
            <p class="text-muted mb-0 small">Choose how the dictionary entry will be grouped and displayed.</p>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-5">
                    <?= $form->field($model, 'category_id')->dropDownList(
                        Yii::$app->params['medical_dictionary_categories']['en'],
                        ['prompt' => 'Select a category…']
                    ) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'type')->dropDownList(
                        Yii::$app->params['medical_dictionary_types']['en'],
                        ['prompt' => 'Select a term type…']
                    ) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['status']) ?>
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
                        <button class="nav-link<?= $code === 'ru' ? ' active' : '' ?>" data-bs-toggle="tab"
                                data-bs-target="#dictionary-language-<?= $code ?>" type="button" role="tab">
                            <span class="badge bg-light text-dark me-1"><?= $language['flag'] ?></span>
                            <?= Html::encode($language['label']) ?>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <?php foreach ($languages as $code => $language): ?>
                    <div class="tab-pane fade<?= $code === 'ru' ? ' show active' : '' ?>"
                         id="dictionary-language-<?= $code ?>" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-lg-7">
                                <?= $form->field($model, "name_{$code}")->textInput([
                                    'maxlength' => true,
                                    'placeholder' => 'Dictionary term in ' . $language['label'],
                                ]) ?>
                            </div>
                            <div class="col-lg-5">
                                <?= $form->field($model, "slug_{$code}")->textInput([
                                    'maxlength' => true,
                                    'placeholder' => 'Generated automatically when empty',
                                ])->hint('Leave empty to generate it from the term.', ['class' => 'form-text']) ?>
                            </div>
                            <div class="col-12">
                                <?= $form->field($model, "desc_{$code}")->textarea([
                                    'rows' => 3,
                                    'maxlength' => true,
                                    'placeholder' => 'Short description (up to 255 characters)',
                                ]) ?>
                            </div>
                            <div class="col-12">
                                <?= $form->field($model, "content_{$code}")->widget(Summernote::class, [
                                    'useKrajeePresets' => true,
                                    'container' => ['class' => 'mb-0'],
                                    'pluginOptions' => [
                                        'height' => 280,
                                        'placeholder' => 'Write the full article in ' . $language['label'] . '…',
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
            <h5 class="card-title mb-1"><i class="ri-search-eye-line text-primary me-2"></i>Search engine metadata</h5>
            <p class="text-muted mb-0 small">Optional titles and descriptions used in search results.</p>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php foreach ($languages as $code => $language): ?>
                    <div class="col-lg-4">
                        <div class="border rounded p-3 h-100 bg-light bg-opacity-50">
                            <h6 class="mb-3"><span class="badge bg-primary-subtle text-primary me-1"><?= $language['flag'] ?></span><?= Html::encode($language['label']) ?></h6>
                            <?= $form->field($model, "seo_title_{$code}")->textInput(['maxlength' => true, 'placeholder' => 'SEO title']) ?>
                            <?= $form->field($model, "seo_desc_{$code}")->textarea(['rows' => 4, 'maxlength' => true, 'placeholder' => 'SEO description']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mb-4">
        <?= Html::a('<i class="ri-close-line me-1"></i>Cancel', $model->isNewRecord ? ['index'] : ['view', 'id' => $model->id], ['class' => 'btn btn-light px-4']) ?>
        <?= Html::submitButton(
            '<i class="ri-save-3-line me-1"></i>' . ($model->isNewRecord ? 'Create entry' : 'Save changes'),
            ['class' => 'btn btn-success px-4', 'name' => 'submit-button']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
