<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Faq $model */
/** @var common\models\Faq[] $models */
/** @var yii\widgets\ActiveForm $form */

$models = $models ?? [$model];
$isCreate = $model->isNewRecord;
?>

<div class="faq-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->errorSummary($model) ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'course_id')->widget(\kartik\select2\Select2::class, [
                'data' => ArrayHelper::map(\common\models\Courses::find()->all(), 'id', 'name_en'),
                'language' => 'en',
                'options' => ['placeholder' => 'Select course'],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'page_id')->dropDownList(Yii::$app->params['faq_page_id'],['prompt'=>'Select the page']) ?>
        </div>

        <?php if ($isCreate): ?>
            <div class="col-md-12">
                <div id="faq-items">
                    <?php foreach ($models as $index => $faqModel): ?>
                        <div class="faq-item border rounded p-3 mb-3" data-index="<?= $index ?>">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">FAQ #<span class="faq-item-number"><?= $index + 1 ?></span></h5>
                                <button type="button" class="btn btn-outline-danger btn-sm remove-faq-item<?= count($models) === 1 ? ' d-none' : '' ?>">Remove</button>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <?= Html::label($model->getAttributeLabel('question_ru'), "faq-question_ru-{$index}", ['class' => 'form-label']) ?>
                                    <?= Html::textInput('Faq[question_ru][]', $faqModel->question_ru, ['id' => "faq-question_ru-{$index}", 'class' => 'form-control', 'maxlength' => true]) ?>
                                    <?= Html::label($model->getAttributeLabel('answer_ru'), "faq-answer_ru-{$index}", ['class' => 'form-label mt-3']) ?>
                                    <?= Html::textarea('Faq[answer_ru][]', $faqModel->answer_ru, ['id' => "faq-answer_ru-{$index}", 'class' => 'form-control', 'rows' => 6]) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= Html::label($model->getAttributeLabel('question_en'), "faq-question_en-{$index}", ['class' => 'form-label']) ?>
                                    <?= Html::textInput('Faq[question_en][]', $faqModel->question_en, ['id' => "faq-question_en-{$index}", 'class' => 'form-control', 'maxlength' => true]) ?>
                                    <?= Html::label($model->getAttributeLabel('answer_en'), "faq-answer_en-{$index}", ['class' => 'form-label mt-3']) ?>
                                    <?= Html::textarea('Faq[answer_en][]', $faqModel->answer_en, ['id' => "faq-answer_en-{$index}", 'class' => 'form-control', 'rows' => 6]) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= Html::label($model->getAttributeLabel('question_uz'), "faq-question_uz-{$index}", ['class' => 'form-label']) ?>
                                    <?= Html::textInput('Faq[question_uz][]', $faqModel->question_uz, ['id' => "faq-question_uz-{$index}", 'class' => 'form-control', 'maxlength' => true]) ?>
                                    <?= Html::label($model->getAttributeLabel('answer_uz'), "faq-answer_uz-{$index}", ['class' => 'form-label mt-3']) ?>
                                    <?= Html::textarea('Faq[answer_uz][]', $faqModel->answer_uz, ['id' => "faq-answer_uz-{$index}", 'class' => 'form-control', 'rows' => 6]) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="add-faq-item" class="btn btn-primary mb-3">Add FAQ</button>
            </div>
        <?php else: ?>
            <div class="col-md-4">
                <?= $form->field($model, 'question_ru')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'answer_ru')->textarea(['rows' => 6]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'question_en')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'answer_en')->textarea(['rows' => 6]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'question_uz')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'answer_uz')->textarea(['rows' => 6]) ?>
            </div>
        <?php endif; ?>

        <div class="col-md-12 mt-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php if ($isCreate): ?>
<?php
$js = <<<'JS'
(function () {
    const container = document.getElementById('faq-items');
    const addButton = document.getElementById('add-faq-item');

    function refreshFaqItems() {
        container.querySelectorAll('.faq-item').forEach(function (item, index) {
            item.dataset.index = index;
            item.querySelector('.faq-item-number').textContent = index + 1;
            item.querySelectorAll('input, textarea').forEach(function (input) {
                const attribute = input.name.match(/Faq\[(.+)]\[]/)[1];
                input.id = 'faq-' + attribute + '-' + index;
                const label = item.querySelector('label[for^="faq-' + attribute + '-"]');
                if (label) {
                    label.setAttribute('for', input.id);
                }
            });
        });

        const items = container.querySelectorAll('.faq-item');
        items.forEach(function (item) {
            item.querySelector('.remove-faq-item').classList.toggle('d-none', items.length === 1);
        });
    }

    addButton.addEventListener('click', function () {
        const clone = container.querySelector('.faq-item').cloneNode(true);
        clone.querySelectorAll('input, textarea').forEach(function (input) {
            input.value = '';
        });
        container.appendChild(clone);
        refreshFaqItems();
    });

    container.addEventListener('click', function (event) {
        if (!event.target.classList.contains('remove-faq-item')) {
            return;
        }

        event.target.closest('.faq-item').remove();
        refreshFaqItems();
    });
}());
JS;
$this->registerJs($js);
?>
<?php endif; ?>
