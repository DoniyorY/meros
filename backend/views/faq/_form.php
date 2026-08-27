<?php

use common\models\Courses;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Faq $model */
/** @var common\models\Faq[] $models */

$models = $models ?? [$model];
$isCreate = $model->isNewRecord;
$languages = [
    'en' => ['label' => 'English', 'required' => true],
    'ru' => ['label' => 'Русский', 'required' => false],
    'uz' => ['label' => "O‘zbekcha", 'required' => false],
];
$courses = ArrayHelper::map(Courses::find()->orderBy(['name_en' => SORT_ASC])->all(), 'id', 'name_en');
?>
<div class="faq-form">
    <?php $form = ActiveForm::begin([
        'id' => 'faq-form',
        'enableClientValidation' => true,
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
        'header' => '<div class="fw-semibold mb-1"><i class="ri-error-warning-line me-1"></i>Please check the FAQ data:</div>',
    ]) ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="card-title mb-1"><i class="ri-links-line text-primary me-2"></i>Placement</h5>
            <p class="text-muted small mb-0">Choose the course and website section where this FAQ will be displayed.</p>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-7">
                    <?= $form->field($model, 'course_id')->widget(Select2::class, [
                        'data' => $courses,
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select a course…'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'page_id')->dropDownList(Yii::$app->params['faq_page_id'], ['prompt' => 'Select a page…']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($isCreate): ?>
        <div id="faq-items">
            <?php foreach ($models as $index => $faqModel): ?>
                <div class="faq-item card border-0 shadow-sm mb-4" data-index="<?= $index ?>">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <h5 class="card-title mb-1"><i class="ri-question-answer-line text-primary me-2"></i>FAQ <span class="faq-item-number">#<?= $index + 1 ?></span></h5>
                            <p class="text-muted small mb-0">English is required; Russian and Uzbek translations are optional.</p>
                        </div>
                        <button type="button" class="btn btn-soft-danger btn-sm remove-faq-item<?= count($models) === 1 ? ' d-none' : '' ?>">
                            <i class="ri-delete-bin-line me-1"></i>Remove
                        </button>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
                            <?php foreach ($languages as $code => $language): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link<?= $code === 'en' ? ' active' : '' ?>" data-bs-toggle="tab"
                                            data-bs-target="#faq-<?= $index ?>-<?= $code ?>" type="button" role="tab">
                                        <?= Html::encode($language['label']) ?><?= $language['required'] ? ' *' : '' ?>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="tab-content">
                            <?php foreach ($languages as $code => $language): ?>
                                <div class="tab-pane fade<?= $code === 'en' ? ' show active' : '' ?>" id="faq-<?= $index ?>-<?= $code ?>" role="tabpanel">
                                    <div class="mb-3">
                                        <?= Html::label('Question', "faq-question_{$code}-{$index}", ['class' => 'form-label fw-medium']) ?>
                                        <?= Html::textInput("Faq[question_{$code}][]", $faqModel->{"question_{$code}"}, [
                                            'id' => "faq-question_{$code}-{$index}", 'class' => 'form-control', 'maxlength' => true,
                                            'placeholder' => 'Enter the question in ' . $language['label'],
                                            'data-required' => $language['required'] ? '1' : '0',
                                        ]) ?>
                                        <div class="invalid-feedback">Question in English is required.</div>
                                    </div>
                                    <div>
                                        <?= Html::label('Answer', "faq-answer_{$code}-{$index}", ['class' => 'form-label fw-medium']) ?>
                                        <?= Html::textarea("Faq[answer_{$code}][]", $faqModel->{"answer_{$code}"}, [
                                            'id' => "faq-answer_{$code}-{$index}", 'class' => 'form-control', 'rows' => 6,
                                            'placeholder' => 'Write a clear and concise answer in ' . $language['label'],
                                            'data-required' => $language['required'] ? '1' : '0',
                                        ]) ?>
                                        <div class="invalid-feedback">Answer in English is required.</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-faq-item" class="btn btn-soft-primary mb-4"><i class="ri-add-line me-1"></i>Add another FAQ</button>
    <?php else: ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom pt-3 px-3 pb-0">
                <h5 class="card-title mb-3"><i class="ri-translate-2 text-primary me-2"></i>Question and answer</h5>
                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                    <?php foreach ($languages as $code => $language): ?>
                        <li class="nav-item"><button class="nav-link<?= $code === 'en' ? ' active' : '' ?>" data-bs-toggle="tab" data-bs-target="#edit-faq-<?= $code ?>" type="button"><?= Html::encode($language['label']) ?><?= $language['required'] ? ' *' : '' ?></button></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="card-body"><div class="tab-content">
                <?php foreach ($languages as $code => $language): ?>
                    <div class="tab-pane fade<?= $code === 'en' ? ' show active' : '' ?>" id="edit-faq-<?= $code ?>">
                        <?= $form->field($model, "question_{$code}")->textInput(['maxlength' => true, 'placeholder' => 'Enter the question in ' . $language['label']]) ?>
                        <?= $form->field($model, "answer_{$code}")->textarea(['rows' => 7, 'placeholder' => 'Write the answer in ' . $language['label']]) ?>
                    </div>
                <?php endforeach; ?>
            </div></div>
        </div>
    <?php endif; ?>

    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mb-4">
        <?= Html::a('<i class="ri-close-line me-1"></i>Cancel', $model->isNewRecord ? ['index'] : ['view', 'id' => $model->id], ['class' => 'btn btn-light px-4']) ?>
        <?= Html::submitButton('<i class="ri-save-3-line me-1"></i>' . ($model->isNewRecord ? 'Create FAQ' : 'Save changes'), ['class' => 'btn btn-success px-4']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php if ($isCreate): ?>
<?php
$js = <<<'JS'
(function () {
    const form = document.getElementById('faq-form');
    const container = document.getElementById('faq-items');
    const addButton = document.getElementById('add-faq-item');

    function refreshItems() {
        const items = container.querySelectorAll('.faq-item');
        items.forEach(function (item, index) {
            item.dataset.index = index;
            item.querySelector('.faq-item-number').textContent = '#' + (index + 1);
            item.querySelectorAll('.tab-pane').forEach(function (pane) {
                const code = pane.querySelector('input').name.match(/question_(\w+)/)[1];
                pane.id = 'faq-' + index + '-' + code;
            });
            item.querySelectorAll('[data-bs-toggle="tab"]').forEach(function (tab) {
                const code = tab.dataset.bsTarget.split('-').pop();
                tab.dataset.bsTarget = '#faq-' + index + '-' + code;
            });
            item.querySelectorAll('input, textarea').forEach(function (input) {
                const attribute = input.name.match(/Faq\[(.+)]\[]/)[1];
                input.id = 'faq-' + attribute + '-' + index;
                const label = input.parentElement.querySelector('label');
                if (label) label.htmlFor = input.id;
            });
            item.querySelector('.remove-faq-item').classList.toggle('d-none', items.length === 1);
        });
    }

    addButton.addEventListener('click', function () {
        const clone = container.querySelector('.faq-item').cloneNode(true);
        clone.querySelectorAll('input, textarea').forEach(function (input) { input.value = ''; input.classList.remove('is-invalid'); });
        clone.querySelectorAll('.nav-link, .tab-pane').forEach(function (element) {
            element.classList.toggle('active', element.dataset.bsTarget ? element.dataset.bsTarget.endsWith('-en') : element.id.endsWith('-en'));
            if (element.classList.contains('tab-pane')) element.classList.toggle('show', element.id.endsWith('-en'));
        });
        container.appendChild(clone);
        refreshItems();
    });

    container.addEventListener('click', function (event) {
        const button = event.target.closest('.remove-faq-item');
        if (!button) return;
        button.closest('.faq-item').remove();
        refreshItems();
    });

    form.addEventListener('submit', function (event) {
        let firstInvalid = null;
        container.querySelectorAll('[data-required="1"]').forEach(function (input) {
            const invalid = input.value.trim() === '';
            input.classList.toggle('is-invalid', invalid);
            if (invalid && !firstInvalid) firstInvalid = input;
        });
        if (!firstInvalid) return true;
        event.preventDefault();
        const pane = firstInvalid.closest('.tab-pane');
        const tab = firstInvalid.closest('.faq-item').querySelector('[data-bs-target="#' + pane.id + '"]');
        if (tab) bootstrap.Tab.getOrCreateInstance(tab).show();
        firstInvalid.focus();
        return false;
    });
}());
JS;
$this->registerJs($js);
?>
<?php endif; ?>
