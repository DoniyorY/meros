<?php

use common\models\Courses;
use common\models\SubscriptionPlans;
use common\widgets\Alert;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\search\SubscriptionPlansSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Subscription Plans';
$courses = ArrayHelper::map(Courses::find()->orderBy(['name_en' => SORT_ASC])->all(), 'id', 'name_en');
$total = (int) SubscriptionPlans::find()->count();
$active = (int) SubscriptionPlans::find()->where(['status' => SubscriptionPlans::STATUS_ACTIVE])->count();
?>
<div class="page-content"><div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent"><h4 class="mb-sm-0"><?= Html::encode($this->title) ?></h4><ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode(Yii::$app->name) ?></a></li><li class="breadcrumb-item active">Subscription plans</li></ol></div>
    <?= Alert::widget() ?>
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4"><div><h2 class="mb-1">Subscription plans</h2><p class="text-muted mb-0">Manage pricing, access periods and included facilities.</p></div><?= Html::a('<i class="ri-add-line me-1"></i>Create plan', ['create'], ['class' => 'btn btn-success px-4']) ?></div>
    <div class="row g-3 mb-4">
        <?php foreach ([['All plans', $total, 'primary', 'ri-bank-card-line'], ['Active', $active, 'success', 'ri-checkbox-circle-line'], ['Inactive', $total - $active, 'warning', 'ri-pause-circle-line']] as $stat): ?><div class="col-md-4"><div class="card border-0 shadow-sm mb-0"><div class="card-body d-flex align-items-center justify-content-between"><div><div class="text-muted text-uppercase small fw-semibold"><?= $stat[0] ?></div><div class="fs-3 fw-semibold"><?= Yii::$app->formatter->asInteger($stat[1]) ?></div></div><span class="avatar-sm"><span class="avatar-title rounded-circle bg-<?= $stat[2] ?>-subtle text-<?= $stat[2] ?> fs-3"><i class="<?= $stat[3] ?>"></i></span></span></div></div></div><?php endforeach; ?>
    </div>
    <div class="card border-0 shadow-sm"><div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between"><div><h5 class="card-title mb-1">All plans</h5><p class="text-muted small mb-0">Filter plans by course, name or status.</p></div><span class="badge bg-light text-body"><?= Yii::$app->formatter->asInteger($dataProvider->getTotalCount()) ?> results</span></div><div class="card-body p-0"><div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider, 'filterModel' => $searchModel,
            'layout' => "{items}\n<div class=\"d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 p-3 border-top\"><div class=\"text-muted small\">{summary}</div><div>{pager}</div></div>",
            'tableOptions' => ['class' => 'table table-hover align-middle mb-0'], 'headerRowOptions' => ['class' => 'table-light text-muted text-uppercase small'], 'filterRowOptions' => ['class' => 'bg-light'],
            'emptyText' => '<div class="text-center py-5"><i class="ri-bank-card-line fs-32 text-muted"></i><h5 class="mt-2">No plans found</h5><p class="text-muted mb-0">Change the filters or create a subscription plan.</p></div>',
            'pager' => array_merge(Yii::$app->params['pager'] ?? [], ['options' => ['class' => 'pagination pagination-sm mb-0'], 'linkOptions' => ['class' => 'page-link']]),
            'columns' => [
                ['attribute' => 'name_en', 'label' => 'Plan', 'format' => 'raw', 'contentOptions' => ['style' => 'min-width:220px'], 'filterInputOptions' => ['class' => 'form-control form-control-sm', 'placeholder' => 'Search plan'], 'value' => static fn (SubscriptionPlans $plan): string => Html::a('<span class="fw-semibold text-body d-block">' . Html::encode($plan->name_en) . '</span><span class="text-muted small">Plan #' . $plan->id . '</span>', ['view', 'id' => $plan->id], ['class' => 'text-decoration-none'])],
                ['attribute' => 'course_id', 'value' => static fn (SubscriptionPlans $plan): string => $plan->course ? $plan->course->name_en : 'Not assigned', 'filter' => $courses, 'filterInputOptions' => ['class' => 'form-select form-select-sm', 'prompt' => 'All courses']],
                ['attribute' => 'price', 'format' => 'raw', 'filterInputOptions' => ['class' => 'form-control form-control-sm', 'placeholder' => 'Exact price'], 'value' => static fn (SubscriptionPlans $plan): string => '<span class="fw-semibold">' . Yii::$app->formatter->asDecimal($plan->price, 2) . '</span>'],
                ['attribute' => 'duration_days', 'label' => 'Access', 'filterInputOptions' => ['class' => 'form-control form-control-sm', 'placeholder' => 'Days'], 'value' => static fn (SubscriptionPlans $plan): string => Yii::$app->formatter->asInteger($plan->duration_days) . ' days'],
                ['attribute' => 'updated_at', 'format' => 'raw', 'filter' => false, 'value' => static fn (SubscriptionPlans $plan): string => '<span class="text-nowrap">' . Yii::$app->formatter->asDate($plan->updated_at) . '</span><span class="d-block text-muted small text-nowrap">' . Yii::$app->formatter->asRelativeTime($plan->updated_at) . '</span>'],
                ['attribute' => 'status', 'format' => 'raw', 'filter' => Yii::$app->params['status'], 'filterInputOptions' => ['class' => 'form-select form-select-sm', 'prompt' => 'All'], 'value' => static function (SubscriptionPlans $plan): string { $active = (int) $plan->status === SubscriptionPlans::STATUS_ACTIVE; return Html::a('<i class="ri-checkbox-blank-circle-fill me-1" style="font-size:7px"></i>' . ($active ? 'Active' : 'Inactive'), ['status', 'id' => $plan->id, 'status' => $active ? 0 : 1], ['class' => 'badge rounded-pill ' . ($active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'), 'data' => ['confirm' => 'Are you sure you want to change the plan status?', 'method' => 'post']]); }],
                ['label' => '', 'format' => 'raw', 'filter' => false, 'contentOptions' => ['class' => 'text-end text-nowrap'], 'value' => static fn (SubscriptionPlans $plan): string => Html::a('<i class="ri-eye-line"></i>', ['view', 'id' => $plan->id], ['class' => 'btn btn-sm btn-soft-primary me-1', 'title' => 'View']) . Html::a('<i class="ri-edit-line"></i>', ['update', 'id' => $plan->id], ['class' => 'btn btn-sm btn-soft-secondary', 'title' => 'Edit'])],
            ],
        ]) ?>
    </div></div></div>
</div></div>
