<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\MedicalDictionary $model */

$this->title = $model->name_en ?: $model->name_ru;
$category = Yii::$app->params['medical_dictionary_categories']['en'][$model->category_id] ?? 'Not set';
$type = Yii::$app->params['medical_dictionary_types']['en'][$model->type] ?? 'Not set';
$languages = ['ru' => 'Русский', 'en' => 'English', 'uz' => "O‘zbekcha"];
\yii\web\YiiAsset::register($this);
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?= Html::encode($this->title) ?></h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode(Yii::$app->name) ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">Medical Dictionary</a></li>
                        <li class="breadcrumb-item active"><?= Html::encode($this->title) ?></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="medical-dictionary-view">
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-start justify-content-between gap-3">
                        <div>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary-subtle text-primary fs-12"><?= Html::encode($category) ?></span>
                                <span class="badge bg-info-subtle text-info fs-12"><?= Html::encode($type) ?></span>
                                <span class="badge <?= $model->status ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?> fs-12">
                                    <i class="ri-checkbox-blank-circle-fill me-1" style="font-size: 8px"></i><?= $model->status ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                            <h2 class="mb-2"><?= Html::encode($this->title) ?></h2>
                            <p class="text-muted mb-0">Entry #<?= Html::encode($model->id) ?> · Updated <?= Yii::$app->formatter->asRelativeTime($model->updated_at) ?></p>
                        </div>
                        <div class="d-flex gap-2">
                            <?= Html::a('<i class="ri-edit-line me-1"></i>Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('<i class="ri-delete-bin-line"></i>', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-outline-danger',
                                'title' => 'Delete entry',
                                'aria-label' => 'Delete entry',
                                'data' => ['confirm' => 'Are you sure you want to delete this entry?', 'method' => 'post'],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white pt-3 px-3 pb-0">
                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                        <?php foreach ($languages as $code => $label): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link<?= $code === 'ru' ? ' active' : '' ?>" data-bs-toggle="tab" data-bs-target="#entry-<?= $code ?>" type="button" role="tab">
                                    <?= Html::encode($label) ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <?php foreach ($languages as $code => $label): ?>
                            <div class="tab-pane fade<?= $code === 'ru' ? ' show active' : '' ?>" id="entry-<?= $code ?>" role="tabpanel">
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 border-bottom pb-3 mb-4">
                                    <div>
                                        <div class="text-muted small text-uppercase fw-semibold mb-1">Term</div>
                                        <h3 class="mb-1"><?= Html::encode($model->{"name_{$code}"}) ?></h3>
                                        <code>/<?= Html::encode($model->{"slug_{$code}"}) ?></code>
                                    </div>
                                </div>
                                <div class="alert bg-light border-0 text-body mb-4">
                                    <i class="ri-double-quotes-l text-primary me-2"></i><?= Html::encode($model->{"desc_{$code}"}) ?>
                                </div>
                                <article class="dictionary-content lh-lg">
                                    <?= HtmlPurifier::process($model->{"content_{$code}"}) ?>
                                </article>
                                <?php if ($model->{"seo_title_{$code}"} || $model->{"seo_desc_{$code}"}): ?>
                                    <div class="border-top mt-4 pt-4">
                                        <h6 class="text-muted text-uppercase mb-3"><i class="ri-search-eye-line me-1"></i>Search preview</h6>
                                        <div class="border rounded p-3">
                                            <div class="text-primary fs-5 mb-1"><?= Html::encode($model->{"seo_title_{$code}"} ?: $model->{"name_{$code}"}) ?></div>
                                            <div class="text-success small mb-1">/<?= Html::encode($model->{"slug_{$code}"}) ?></div>
                                            <div class="text-muted"><?= Html::encode($model->{"seo_desc_{$code}"} ?: $model->{"desc_{$code}"}) ?></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body d-flex flex-wrap justify-content-between gap-3 text-muted small">
                    <span><i class="ri-calendar-line me-1"></i>Created: <?= Yii::$app->formatter->asDatetime($model->created_at) ?></span>
                    <span><i class="ri-refresh-line me-1"></i>Updated: <?= Yii::$app->formatter->asDatetime($model->updated_at) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
