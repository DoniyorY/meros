<?php

use common\models\Courses;
use common\models\Faq;
use common\widgets\Alert;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\search\FaqSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'FAQ';
$courses = ArrayHelper::map(Courses::find()->orderBy(['name_en' => SORT_ASC])->all(), 'id', 'name_en');
$totalFaq = (int) Faq::find()->count();
$courseCount = (int) Faq::find()->select('course_id')->distinct()->count();
?>
<div class="page-content"><div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
        <h4 class="mb-sm-0"><?= Html::encode($this->title) ?></h4>
        <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode(Yii::$app->name) ?></a></li><li class="breadcrumb-item active">FAQ</li></ol>
    </div>
    <?= Alert::widget() ?>

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div><h2 class="mb-1">Frequently asked questions</h2><p class="text-muted mb-0">Manage translated answers shown on course and landing pages.</p></div>
        <?= Html::a('<i class="ri-add-line me-1"></i>Create FAQ', ['create'], ['class' => 'btn btn-success px-4']) ?>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6"><div class="card border-0 shadow-sm mb-0"><div class="card-body d-flex align-items-center justify-content-between"><div><div class="text-muted text-uppercase small fw-semibold">Total questions</div><div class="fs-3 fw-semibold"><?= Yii::$app->formatter->asInteger($totalFaq) ?></div></div><span class="avatar-sm"><span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-3"><i class="ri-question-answer-line"></i></span></span></div></div></div>
        <div class="col-md-6"><div class="card border-0 shadow-sm mb-0"><div class="card-body d-flex align-items-center justify-content-between"><div><div class="text-muted text-uppercase small fw-semibold">Courses with FAQ</div><div class="fs-3 fw-semibold"><?= Yii::$app->formatter->asInteger($courseCount) ?></div></div><span class="avatar-sm"><span class="avatar-title rounded-circle bg-success-subtle text-success fs-3"><i class="ri-book-open-line"></i></span></span></div></div></div>
    </div>

    <div class="card border-0 shadow-sm"><div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center gap-2"><div><h5 class="card-title mb-1">All questions</h5><p class="text-muted small mb-0">Filter by course, page or question text.</p></div><span class="badge bg-light text-body"><?= Yii::$app->formatter->asInteger($dataProvider->getTotalCount()) ?> results</span></div>
        <div class="card-body p-0"><div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "{items}\n<div class=\"d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 p-3 border-top\"><div class=\"text-muted small\">{summary}</div><div>{pager}</div></div>",
                'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
                'headerRowOptions' => ['class' => 'table-light text-muted text-uppercase small'],
                'filterRowOptions' => ['class' => 'bg-light'],
                'emptyText' => '<div class="text-center py-5"><i class="ri-question-line fs-32 text-muted"></i><h5 class="mt-2">No FAQ found</h5><p class="text-muted mb-0">Try changing the filters or create the first question.</p></div>',
                'pager' => array_merge(Yii::$app->params['pager'] ?? [], ['options' => ['class' => 'pagination pagination-sm mb-0'], 'linkOptions' => ['class' => 'page-link']]),
                'columns' => [
                    [
                        'attribute' => 'question_en', 'label' => 'Question', 'format' => 'raw',
                        'contentOptions' => ['style' => 'min-width: 300px'],
                        'filterInputOptions' => ['class' => 'form-control form-control-sm', 'placeholder' => 'Search question'],
                        'value' => static fn (Faq $faq): string => Html::a('<span class="d-block fw-semibold text-body">' . Html::encode($faq->question_en) . '</span><span class="text-muted small">FAQ #' . $faq->id . '</span>', ['view', 'id' => $faq->id], ['class' => 'text-decoration-none']),
                    ],
                    [
                        'attribute' => 'course_id', 'value' => static fn (Faq $faq): string => $faq->course ? $faq->course->name_en : 'Not assigned',
                        'filter' => $courses, 'filterInputOptions' => ['class' => 'form-select form-select-sm', 'prompt' => 'All courses'],
                    ],
                    [
                        'attribute' => 'page_id', 'label' => 'Page',
                        'value' => static fn (Faq $faq): string => Yii::$app->params['faq_page_id'][$faq->page_id] ?? 'Not set',
                        'filter' => Yii::$app->params['faq_page_id'], 'filterInputOptions' => ['class' => 'form-select form-select-sm', 'prompt' => 'All pages'],
                    ],
                    [
                        'attribute' => 'answer_en', 'label' => 'Answer preview', 'filter' => false,
                        'value' => static fn (Faq $faq): string => mb_strimwidth(strip_tags($faq->answer_en), 0, 100, '…'),
                        'contentOptions' => ['class' => 'text-muted', 'style' => 'min-width: 240px'],
                    ],
                    [
                        'attribute' => 'updated_at', 'format' => 'raw', 'filter' => false,
                        'value' => static fn (Faq $faq): string => '<span class="text-nowrap">' . Yii::$app->formatter->asDate($faq->updated_at) . '</span><span class="d-block text-muted small text-nowrap">' . Yii::$app->formatter->asRelativeTime($faq->updated_at) . '</span>',
                    ],
                    [
                        'label' => '', 'format' => 'raw', 'filter' => false, 'contentOptions' => ['class' => 'text-end text-nowrap'],
                        'value' => static fn (Faq $faq): string => Html::a('<i class="ri-eye-line"></i>', ['view', 'id' => $faq->id], ['class' => 'btn btn-sm btn-soft-primary me-1', 'title' => 'View', 'aria-label' => 'View FAQ']) . Html::a('<i class="ri-edit-line"></i>', ['update', 'id' => $faq->id], ['class' => 'btn btn-sm btn-soft-secondary', 'title' => 'Edit', 'aria-label' => 'Edit FAQ']),
                    ],
                ],
            ]) ?>
        </div></div>
    </div>
</div></div>
