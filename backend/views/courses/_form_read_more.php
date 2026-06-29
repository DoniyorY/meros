<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ReadMore[] $models */
/** @var string $url */
/** @var bool $isUpdate */

$models = $models ?? [$model ?? new \common\models\ReadMore()];
$isUpdate = $isUpdate ?? false;
$wrapperId = 'read-more-form-' . uniqid();
?>

<div class="read-more-form" id="<?= $wrapperId ?>">
   <?php $form = ActiveForm::begin(['action' => $url]); ?>
      <div class="read-more-items">
         <?php foreach ($models as $index => $readMore): ?>
            <div class="read-more-item border rounded p-3 mb-3" data-index="<?= $index ?>">
               <div class="d-flex justify-content-between align-items-center mb-2">
                  <h5 class="mb-0">Info #<span class="read-more-number"><?= $index + 1 ?></span></h5>
                  <?php if (!$isUpdate): ?>
                     <button type="button" class="btn btn-outline-danger btn-sm remove-read-more <?= $index === 0 ? 'd-none' : '' ?>">Remove</button>
                  <?php endif; ?>
               </div>
               <div class="row">
                  <div class="col-md-4">
                     <?= $form->field($readMore, "[$index]title_ru")->textInput(['maxlength' => true]) ?>
                     <?= $form->field($readMore, "[$index]content_ru")->textarea(['rows' => 6]) ?>
                  </div>
                  <div class="col-md-4">
                     <?= $form->field($readMore, "[$index]title_en")->textInput(['maxlength' => true]) ?>
                     <?= $form->field($readMore, "[$index]content_en")->textarea(['rows' => 6]) ?>
                  </div>
                  <div class="col-md-4">
                     <?= $form->field($readMore, "[$index]title_uz")->textInput(['maxlength' => true]) ?>
                     <?= $form->field($readMore, "[$index]content_uz")->textarea(['rows' => 6]) ?>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>
      </div>

      <?php if (!$isUpdate): ?>
         <button type="button" class="btn btn-outline-primary add-read-more mb-3">Add another info</button>
      <?php endif; ?>

      <div class="form-group">
         <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
      </div>
   <?php ActiveForm::end(); ?>
</div>

<?php if (!$isUpdate): ?>
<?php
$js = <<<JS
(function () {
    const wrapper = document.getElementById('$wrapperId');
    if (!wrapper || wrapper.dataset.initialized) return;
    wrapper.dataset.initialized = '1';

    const items = wrapper.querySelector('.read-more-items');
    const addBtn = wrapper.querySelector('.add-read-more');

    function refresh() {
        items.querySelectorAll('.read-more-item').forEach((item, index) => {
            item.dataset.index = index;
            item.querySelector('.read-more-number').textContent = index + 1;
            item.querySelectorAll('input, textarea').forEach((input) => {
                input.name = input.name.replace(/ReadMore\[\d+\]/, 'ReadMore[' + index + ']');
                input.id = input.id.replace(/readmore-\d+-/, 'readmore-' + index + '-');
            });
            item.querySelectorAll('label').forEach((label) => {
                if (label.htmlFor) {
                    label.htmlFor = label.htmlFor.replace(/readmore-\d+-/, 'readmore-' + index + '-');
                }
            });
            const removeBtn = item.querySelector('.remove-read-more');
            if (removeBtn) {
                removeBtn.classList.toggle('d-none', index === 0);
            }
        });
    }

    addBtn.addEventListener('click', function () {
        const first = items.querySelector('.read-more-item');
        const clone = first.cloneNode(true);
        clone.querySelectorAll('input, textarea').forEach((input) => {
            input.value = '';
        });
        clone.querySelectorAll('.is-invalid, .is-valid').forEach((el) => {
            el.classList.remove('is-invalid', 'is-valid');
        });
        clone.querySelectorAll('.invalid-feedback').forEach((el) => {
            el.textContent = '';
        });
        items.appendChild(clone);
        refresh();
    });

    wrapper.addEventListener('click', function (event) {
        const removeBtn = event.target.closest('.remove-read-more');
        if (!removeBtn) return;
        const item = removeBtn.closest('.read-more-item');
        if (items.querySelectorAll('.read-more-item').length > 1) {
            item.remove();
            refresh();
        }
    });
})();
JS;
$this->registerJs($js);
?>
<?php endif; ?>
