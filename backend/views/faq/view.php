<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Faq $model */

$this->title = $model->question_en ?: 'FAQ #' . $model->id;
$course = $model->course ? $model->course->name_en : 'Not assigned';
$page = Yii::$app->params['faq_page_id'][$model->page_id] ?? 'Not set';
$author = $model->user ? $model->user->username : 'Unknown';
$languages = ['en' => 'English', 'ru' => 'Русский', 'uz' => "O‘zbekcha"];
\yii\web\YiiAsset::register($this);
?>
<div class="page-content"><div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
        <h4 class="mb-sm-0">FAQ #<?= Html::encode($model->id) ?></h4>
        <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode(Yii::$app->name) ?></a></li><li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">FAQ</a></li><li class="breadcrumb-item active">#<?= Html::encode($model->id) ?></li></ol>
    </div>

    <div class="card border-0 shadow-sm mb-4"><div class="card-body p-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
            <div><div class="d-flex flex-wrap gap-2 mb-3"><span class="badge bg-primary-subtle text-primary"><?= Html::encode($course) ?></span><span class="badge bg-info-subtle text-info"><?= Html::encode($page) ?></span></div><h2 class="mb-2"><?= Html::encode($this->title) ?></h2><p class="text-muted mb-0">Created by <?= Html::encode($author) ?> · <?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></p></div>
            <div class="d-flex align-items-start gap-2"><?= Html::a('<i class="ri-edit-line me-1"></i>Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?><?= Html::a('<i class="ri-delete-bin-line"></i>', ['delete', 'id' => $model->id], ['class' => 'btn btn-outline-danger', 'title' => 'Delete FAQ', 'aria-label' => 'Delete FAQ', 'data' => ['confirm' => 'Are you sure you want to delete this FAQ?', 'method' => 'post']]) ?></div>
        </div>
    </div></div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white pt-3 px-3 pb-0"><ul class="nav nav-tabs nav-tabs-custom" role="tablist">
            <?php foreach ($languages as $code => $label): ?><li class="nav-item"><button class="nav-link<?= $code === 'en' ? ' active' : '' ?>" data-bs-toggle="tab" data-bs-target="#faq-view-<?= $code ?>" type="button"><?= Html::encode($label) ?></button></li><?php endforeach; ?>
        </ul></div>
        <div class="card-body p-4"><div class="tab-content">
            <?php foreach ($languages as $code => $label): ?><div class="tab-pane fade<?= $code === 'en' ? ' show active' : '' ?>" id="faq-view-<?= $code ?>"><div class="text-muted text-uppercase small fw-semibold mb-2">Question</div><h3 class="mb-4"><?= Html::encode($model->{"question_{$code}"}) ?></h3><div class="border-start border-3 border-primary bg-light rounded-end p-4"><div class="text-muted text-uppercase small fw-semibold mb-2">Answer</div><div class="fs-5 lh-lg"><?= nl2br(Html::encode($model->{"answer_{$code}"})) ?></div></div></div><?php endforeach; ?>
        </div></div>
        <div class="card-footer bg-white text-muted small d-flex flex-wrap justify-content-between gap-2"><span>Created: <?= Yii::$app->formatter->asDatetime($model->created_at) ?></span><span>Updated: <?= Yii::$app->formatter->asDatetime($model->updated_at) ?></span></div>
    </div>
</div></div>
