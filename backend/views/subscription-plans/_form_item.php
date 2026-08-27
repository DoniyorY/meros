<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\SubscriptionPlanItems $model */
/** @var string $url */

$languages = ['en' => 'English', 'ru' => 'Русский', 'uz' => "O‘zbekcha"];
?>
<div class="subscription-plan-items-form">
    <?php $form = ActiveForm::begin([
        'action' => $url,
        'enableClientValidation' => true,
        'fieldConfig' => ['options' => ['class' => 'mb-3'], 'labelOptions' => ['class' => 'form-label fw-medium'], 'errorOptions' => ['class' => 'invalid-feedback d-block']],
    ]); ?>
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>
    <ul class="nav nav-pills nav-justified mb-3" role="tablist">
        <?php foreach ($languages as $code => $label): ?><li class="nav-item"><button class="nav-link<?= $code === 'en' ? ' active' : '' ?>" data-bs-toggle="tab" data-bs-target="#facility-<?= $model->isNewRecord ? 'new' : $model->id ?>-<?= $code ?>" type="button"><?= Html::encode($label) ?><?= $code === 'en' ? ' *' : '' ?></button></li><?php endforeach; ?>
    </ul>
    <div class="tab-content">
        <?php foreach ($languages as $code => $label): ?><div class="tab-pane fade<?= $code === 'en' ? ' show active' : '' ?>" id="facility-<?= $model->isNewRecord ? 'new' : $model->id ?>-<?= $code ?>">
            <?= $form->field($model, "name_{$code}")->textInput(['maxlength' => true, 'placeholder' => 'Facility name in ' . $label]) ?>
            <?= $form->field($model, "desc_{$code}")->textarea(['rows' => 3, 'maxlength' => true, 'placeholder' => 'Short description in ' . $label]) ?>
        </div><?php endforeach; ?>
    </div>
    <div class="text-end"><?= Html::submitButton('<i class="ri-save-3-line me-1"></i>Save facility', ['class' => 'btn btn-success px-4']) ?></div>
    <?php ActiveForm::end(); ?>
</div>
