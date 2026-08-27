<?php

use common\models\Courses;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\SubscriptionPlans $model */

$courses = ArrayHelper::map(Courses::find()->orderBy(['name_en' => SORT_ASC])->all(), 'id', 'name_en');
$languages = ['en' => 'English', 'ru' => 'Русский', 'uz' => "O‘zbekcha"];
?>
<div class="subscription-plans-form">
    <?php $form = ActiveForm::begin([
        'id' => 'subscription-plan-form',
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

    <?= $form->errorSummary($model, ['class' => 'alert alert-danger shadow-sm', 'header' => '<div class="fw-semibold mb-1"><i class="ri-error-warning-line me-1"></i>Please check the plan details:</div>']) ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3"><h5 class="card-title mb-1"><i class="ri-book-open-line text-primary me-2"></i>Course</h5><p class="text-muted small mb-0">Select which course this subscription plan unlocks.</p></div>
        <div class="card-body">
            <?= $form->field($model, 'course_id')->widget(Select2::class, [
                'data' => $courses,
                'options' => ['placeholder' => 'Select a course…'],
                'pluginOptions' => ['allowClear' => true],
            ]) ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom pt-3 px-3 pb-0"><h5 class="card-title mb-3"><i class="ri-translate-2 text-primary me-2"></i>Localized plan name</h5><ul class="nav nav-tabs nav-tabs-custom" role="tablist">
            <?php foreach ($languages as $code => $label): ?><li class="nav-item"><button class="nav-link<?= $code === 'en' ? ' active' : '' ?>" data-bs-toggle="tab" data-bs-target="#plan-name-<?= $code ?>" type="button"><?= Html::encode($label) ?><?= $code === 'en' ? ' *' : '' ?></button></li><?php endforeach; ?>
        </ul></div>
        <div class="card-body"><div class="tab-content">
            <?php foreach ($languages as $code => $label): ?><div class="tab-pane fade<?= $code === 'en' ? ' show active' : '' ?>" id="plan-name-<?= $code ?>"><?= $form->field($model, "name_{$code}")->textInput(['maxlength' => true, 'placeholder' => 'Plan name in ' . $label]) ?></div><?php endforeach; ?>
        </div></div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3"><h5 class="card-title mb-1"><i class="ri-bank-card-line text-primary me-2"></i>Pricing and access</h5><p class="text-muted small mb-0">Set a positive price and the number of access days.</p></div>
        <div class="card-body"><div class="row g-3">
            <div class="col-lg-6"><?= $form->field($model, 'price')->textInput(['type' => 'number', 'min' => 0, 'step' => '0.01', 'placeholder' => '0.00', 'inputmode' => 'decimal'])->hint('Enter the price in the configured billing currency.') ?></div>
            <div class="col-lg-6"><?= $form->field($model, 'duration_days')->textInput(['type' => 'number', 'min' => 1, 'step' => 1, 'placeholder' => '30', 'inputmode' => 'numeric'])->hint('How many days access remains active after purchase.') ?></div>
            <?php if (!$model->isNewRecord): ?><div class="col-12"><?= $form->field($model, 'status')->dropDownList(Yii::$app->params['status']) ?></div><?php endif; ?>
        </div></div>
    </div>

    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mb-4">
        <?= Html::a('<i class="ri-close-line me-1"></i>Cancel', $model->isNewRecord ? ['index'] : ['view', 'id' => $model->id], ['class' => 'btn btn-light px-4']) ?>
        <?= Html::submitButton('<i class="ri-save-3-line me-1"></i>' . ($model->isNewRecord ? 'Create plan' : 'Save changes'), ['class' => 'btn btn-success px-4']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
