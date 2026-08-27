<?php

use common\models\SubscriptionPlanItems;
use common\models\SubscriptionPlans;
use common\widgets\Alert;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\SubscriptionPlans $model */

$this->title = $model->name_en;
$course = $model->course ? $model->course->name_en : 'Not assigned';
$languages = ['en' => 'English', 'ru' => 'Русский', 'uz' => "O‘zbekcha"];
\yii\web\YiiAsset::register($this);
?>
<div class="page-content"><div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent"><h4 class="mb-sm-0"><?= Html::encode($this->title) ?></h4><ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode(Yii::$app->name) ?></a></li><li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">Subscription plans</a></li><li class="breadcrumb-item active"><?= Html::encode($this->title) ?></li></ol></div>
    <?= Alert::widget() ?>

    <div class="card border-0 shadow-sm mb-4"><div class="card-body p-4"><div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
        <div><div class="d-flex flex-wrap gap-2 mb-3"><span class="badge bg-primary-subtle text-primary"><?= Html::encode($course) ?></span><span class="badge <?= $model->status ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>"><?= $model->status ? 'Active' : 'Inactive' ?></span></div><h2 class="mb-1"><?= Html::encode($model->name_en) ?></h2><p class="text-muted mb-0">Subscription plan #<?= Html::encode($model->id) ?></p></div>
        <div class="d-flex flex-wrap align-items-start gap-2">
            <?= Html::a('<i class="ri-toggle-line me-1"></i>' . ($model->status ? 'Deactivate' : 'Activate'), ['status', 'id' => $model->id, 'status' => $model->status ? SubscriptionPlans::STATUS_INACTIVE : SubscriptionPlans::STATUS_ACTIVE], ['class' => $model->status ? 'btn btn-outline-warning' : 'btn btn-outline-success', 'data' => ['confirm' => 'Are you sure you want to change the plan status?', 'method' => 'post']]) ?>
            <?= Html::a('<i class="ri-edit-line me-1"></i>Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="ri-delete-bin-line"></i>', ['delete', 'id' => $model->id], ['class' => 'btn btn-outline-danger', 'title' => 'Delete plan', 'aria-label' => 'Delete plan', 'data' => ['confirm' => 'Are you sure you want to delete this subscription plan?', 'method' => 'post']]) ?>
        </div>
    </div></div></div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4"><div class="card-body">
                <div class="text-center rounded bg-primary-subtle p-4 mb-4"><div class="text-muted text-uppercase small fw-semibold mb-1">Price</div><div class="display-6 fw-semibold text-primary"><?= Yii::$app->formatter->asDecimal($model->price, 2) ?></div><div class="text-muted">for <?= Yii::$app->formatter->asInteger($model->duration_days) ?> days</div></div>
                <div class="vstack gap-3"><div><div class="text-muted small">Course</div><div class="fw-medium"><?= Html::encode($course) ?></div></div><div><div class="text-muted small">Included facilities</div><div class="fw-medium"><?= Yii::$app->formatter->asInteger(count($model->items)) ?></div></div></div>
            </div><div class="card-footer bg-white text-muted small d-flex flex-column gap-1"><span>Created <?= Yii::$app->formatter->asDatetime($model->created_at) ?></span><span>Updated <?= Yii::$app->formatter->asRelativeTime($model->updated_at) ?></span></div></div>
            <div class="card border-0 shadow-sm mb-4"><div class="card-header bg-white"><h5 class="card-title mb-0">Localized names</h5></div><div class="card-body"><div class="vstack gap-3"><?php foreach ($languages as $code => $label): ?><div><div class="text-muted small"><?= Html::encode($label) ?></div><div class="fw-medium"><?= Html::encode($model->{"name_{$code}"}) ?></div></div><?php endforeach; ?></div></div></div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2"><div><h5 class="card-title mb-1">Included facilities</h5><p class="text-muted small mb-0">Benefits and services included in this plan.</p></div><button class="btn btn-soft-primary" type="button" data-bs-toggle="collapse" data-bs-target="#new-facility"><i class="ri-add-line me-1"></i>Add facility</button></div>
                <div class="collapse" id="new-facility"><div class="card-body border-bottom bg-light"><?= $this->render('_form_item', ['model' => new SubscriptionPlanItems(), 'plan_id' => $model->id, 'url' => Url::to(['add-items', 'plan_id' => $model->id])]) ?></div></div>
                <div class="card-body">
                    <?php if (!$model->items): ?><div class="text-center py-5"><i class="ri-list-check-2 fs-32 text-muted"></i><h5 class="mt-2">No facilities yet</h5><p class="text-muted mb-0">Add the first benefit included with this subscription.</p></div><?php else: ?>
                        <div class="accordion accordion-flush" id="plan-facilities">
                            <?php foreach ($model->items as $index => $item): ?><div class="accordion-item border rounded mb-2"><h2 class="accordion-header"><button class="accordion-button<?= $index ? ' collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#facility-item-<?= $item->id ?>"><span class="badge bg-primary-subtle text-primary me-2"><?= $index + 1 ?></span><?= Html::encode($item->name_en) ?></button></h2><div id="facility-item-<?= $item->id ?>" class="accordion-collapse collapse<?= $index ? '' : ' show' ?>" data-bs-parent="#plan-facilities"><div class="accordion-body"><ul class="nav nav-pills mb-3" role="tablist"><?php foreach ($languages as $code => $label): ?><li class="nav-item"><button class="nav-link<?= $code === 'en' ? ' active' : '' ?>" data-bs-toggle="tab" data-bs-target="#item-<?= $item->id ?>-<?= $code ?>" type="button"><?= strtoupper($code) ?></button></li><?php endforeach; ?></ul><div class="tab-content"><?php foreach ($languages as $code => $label): ?><div class="tab-pane fade<?= $code === 'en' ? ' show active' : '' ?>" id="item-<?= $item->id ?>-<?= $code ?>"><h6><?= Html::encode($item->{"name_{$code}"}) ?></h6><p class="text-muted mb-0"><?= nl2br(Html::encode($item->{"desc_{$code}"})) ?></p></div><?php endforeach; ?></div><div class="text-end mt-3 pt-3 border-top"><?= Html::button('<i class="ri-edit-line me-1"></i>Edit', ['class' => 'btn btn-sm btn-soft-primary modalUpdateBtn', 'data-url' => Url::to(['update-item-modal', 'id' => $item->id])]) ?> <?= Html::a('<i class="ri-delete-bin-line me-1"></i>Delete', ['delete-item', 'id' => $item->id], ['class' => 'btn btn-sm btn-soft-danger', 'data' => ['confirm' => 'Are you sure you want to delete this facility?', 'method' => 'post']]) ?></div></div></div></div><?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div></div>
<?php Modal::begin(['id' => 'updateModal', 'title' => 'Edit facility', 'size' => Modal::SIZE_LARGE, 'options' => ['tabindex' => false]]); echo '<div class="modal-body p-0"></div>'; Modal::end(); ?>
