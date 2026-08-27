<?php

use common\models\SubscriptionPlans;
use common\widgets\Alert;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Courses $model */
/** @var common\models\Faq[] $faq */
/** @var common\models\ReadMore[] $readMore */

$this->title = $model->name_en ?: $model->name_ru;
$this->params['breadcrumbs'][] = ['label' => 'Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$pageType = Yii::$app->params['page_type'][$model->page_type] ?? 'Not set';
$category = $model->category ? $model->category->name_en : 'Not set';
$mentor = $model->mentor ? $model->mentor->fullname : 'Not assigned';
$author = $model->user ? $model->user->username : 'Unknown';
$languages = ['ru' => 'Русский', 'en' => 'English', 'uz' => "O‘zbekcha"];
$imageUrl = $model->image ? Yii::$app->request->hostInfo . '/uploads/courses/' . $model->image : null;
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?= Html::encode($this->title) ?></h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode(Yii::$app->name) ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">Courses</a></li>
                        <li class="breadcrumb-item active"><?= Html::encode($this->title) ?></li>
                    </ol>
                </div>
            </div>
        </div>

        <?= Alert::widget() ?>

        <div class="courses-view">
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                        <div>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary-subtle text-primary"><?= Html::encode($category) ?></span>
                                <span class="badge bg-info-subtle text-info"><?= Html::encode($pageType) ?></span>
                                <span class="badge <?= $model->status ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>">
                                    <i class="ri-checkbox-blank-circle-fill me-1" style="font-size: 8px"></i><?= $model->status ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                            <h2 class="mb-2"><?= Html::encode($this->title) ?></h2>
                            <p class="text-muted mb-0"><?= Html::encode($model->title_en ?: $model->lvl) ?></p>
                        </div>
                        <div class="d-flex flex-wrap align-items-start gap-2">
                            <?= Html::a(
                                '<i class="ri-toggle-line me-1"></i>' . ($model->status ? 'Deactivate' : 'Activate'),
                                ['status', 'id' => $model->id, 'status' => $model->status ? 0 : 1],
                                [
                                    'class' => $model->status ? 'btn btn-outline-warning' : 'btn btn-outline-success',
                                    'data' => ['confirm' => 'Are you sure you want to change the course status?', 'method' => 'post'],
                                ]
                            ) ?>
                            <?= Html::a('<i class="ri-edit-line me-1"></i>Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('<i class="ri-delete-bin-line"></i>', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-outline-danger',
                                'title' => 'Delete course',
                                'aria-label' => 'Delete course',
                                'data' => ['confirm' => 'Are you sure you want to delete this course?', 'method' => 'post'],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <?php if ($imageUrl): ?>
                            <?= Html::img($imageUrl, ['class' => 'card-img-top', 'style' => 'max-height: 240px; object-fit: cover', 'alt' => $this->title]) ?>
                        <?php else: ?>
                            <div class="bg-light text-muted d-flex flex-column align-items-center justify-content-center" style="height: 220px">
                                <i class="ri-image-line fs-32"></i><span>No course image</span>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title mb-3">Course details</h5>
                            <div class="vstack gap-3">
                                <div><div class="text-muted small">Slug</div><code><?= Html::encode($model->slug) ?></code></div>
                                <div><div class="text-muted small">Recommended level</div><div class="fw-medium"><?= Html::encode($model->lvl ?: 'Not set') ?></div></div>
                                <div><div class="text-muted small">Mentor</div><div class="fw-medium"><?= Html::encode($mentor) ?></div></div>
                                <div><div class="text-muted small">Created by</div><div class="fw-medium"><?= Html::encode($author) ?></div></div>
                                <?php if ($model->preview_video_link): ?>
                                    <div><?= Html::a('<i class="ri-play-circle-line me-1"></i>Open preview video', $model->preview_video_link, ['class' => 'btn btn-soft-primary w-100', 'target' => '_blank', 'rel' => 'noopener noreferrer']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-white text-muted small d-flex flex-column gap-1">
                            <span><i class="ri-calendar-line me-1"></i>Created <?= Yii::$app->formatter->asDatetime($model->created_at) ?></span>
                            <span><i class="ri-refresh-line me-1"></i>Updated <?= Yii::$app->formatter->asRelativeTime($model->updated_at) ?></span>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white"><h5 class="card-title mb-0">Localized description</h5></div>
                        <div class="card-body">
                            <ul class="nav nav-pills nav-justified mb-3" role="tablist">
                                <?php foreach ($languages as $code => $label): ?>
                                    <li class="nav-item" role="presentation"><button class="nav-link<?= $code === 'en' ? ' active' : '' ?>" data-bs-toggle="tab" data-bs-target="#description-<?= $code ?>" type="button"><?= strtoupper($code) ?></button></li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="tab-content">
                                <?php foreach ($languages as $code => $label): ?>
                                    <div class="tab-pane fade<?= $code === 'en' ? ' show active' : '' ?>" id="description-<?= $code ?>">
                                        <h6><?= Html::encode($model->{"name_{$code}"}) ?></h6>
                                        <?php if ($model->{"title_{$code}"}): ?><p class="text-muted"><?= Html::encode($model->{"title_{$code}"}) ?></p><?php endif; ?>
                                        <div class="lh-lg"><?= HtmlPurifier::process($model->{"desc_{$code}"}) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <ul class="nav nav-tabs nav-tabs-custom px-3 pt-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="subs-tab" data-bs-toggle="tab"
                                        data-bs-target="#subs-tab-pane" type="button" role="tab"
                                        aria-controls="subs-tab-pane" aria-selected="true">Subscriptions
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="features-tab" data-bs-toggle="tab"
                                        data-bs-target="#features-tab-pane" type="button" role="tab"
                                        aria-controls="features-tab-pane" aria-selected="false">Features
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="faq-tab" data-bs-toggle="tab"
                                        data-bs-target="#faq-tab-pane" type="button" role="tab"
                                        aria-controls="faq-tab-pane" aria-selected="true">FAQ
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="read-more-tab" data-bs-toggle="tab"
                                        data-bs-target="#read-more-tab-pane" type="button" role="tab"
                                        aria-controls="read-more-tab-pane" aria-selected="false">More Info
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane p-3 fade show active" id="subs-tab-pane" role="tabpanel"
                                 aria-labelledby="subs-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>Subscriptions</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                                data-bs-target="#subsModal">
                                            Add Subscription
                                        </button>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <!-- Modal -->
                                        <div class="modal fade" id="subsModal" tabindex="-1"
                                             aria-labelledby="subsModalLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="subsModalLabel">New subscription</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                       <?= $this->render('_form_subs', [
                                                          'model' => new SubscriptionPlans(),
                                                          'url' => Url::to(['add-subscription', 'course_id' => $model->id]),
                                                          'course_id' => $model->id,
                                                       ]) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <table class="table table-sm table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name Ru</th>
                                                <th>Name En</th>
                                                <th>Name Uz</th>
                                                <th>Price</th>
                                                <th>Days</th>
                                                <th>Status</th>
                                                <th>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i = 1;
                                            foreach ($model->subs as $item): ?>
                                                <tr>
                                                    <td><?= $i++ ?></td>
                                                    <td><?= Html::encode($item->name_en) ?></td>
                                                    <td><?= Html::encode($item->name_ru) ?></td>
                                                    <td><?= Html::encode($item->name_uz) ?></td>
                                                    <td><?= Yii::$app->formatter->asDecimal($item->price, 0) ?></td>
                                                    <td><?= $item->duration_days ?></td>
                                                    <td><?php
                                                       if ($item->status == 0) {
                                                          echo Html::a('Inactive',
                                                             ['update-subs-status', 'id' => $item->id, 'status' => 1],
                                                             [
                                                                'class' => 'btn btn-warning btn-sm w-100',
                                                                'data-confirm' => 'Are you sure you want to activate this Lesson?',
                                                             ]);
                                                       } else {
                                                          echo Html::a('Active', ['update-subs-status', 'id' => $item->id, 'status' => 0],
                                                             [
                                                                'class' => 'btn btn-success btn-sm w-100',
                                                                'data-confirm' => 'Are you sure you want to inactivate this Lesson?',
                                                             ]);
                                                       }
                                                       ?></td>
                                                    <td>
                                                        <a href="<?= Url::to(['subscription-plans/view', 'id' => $item->id]) ?>" class="btn btn-primary btn-sm " target="_blank">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane p-3 fade" id="faq-tab-pane" role="tabpanel"
                                 aria-labelledby="faq-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>FAQ</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                                data-bs-target="#FaqModal">
                                            Add FAQ
                                        </button>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <!-- Modal -->
                                        <div class="modal fade" id="FaqModal" tabindex="-1"
                                             aria-labelledby="FaqModalLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="FaqModalLabel">New faq</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                       <?= $this->render('_form_faq', [
                                                          'model' => new \common\models\Faq(),
                                                          'url' => \yii\helpers\Url::to(['add-faq', 'course_id' => $model->id]),
                                                          'course_id' => $model->id,
                                                       ]) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <table class="table table-sm table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Question Ru</th>
                                                <th>Question En</th>
                                                <th>Question Uz</th>
                                                <th>Answer Ru</th>
                                                <th>Answer En</th>
                                                <th>Answer Uz</th>
                                                <th>Created</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i = 1;
                                            foreach ($faq as $item): ?>
                                                <tr>
                                                    <td><?= $i++ ?></td>
                                                    <td><?= Html::encode($item->question_ru) ?></td>
                                                    <td><?= Html::encode($item->question_en) ?></td>
                                                    <td><?= Html::encode($item->question_uz) ?></td>
                                                    <td><?= Html::encode($item->answer_ru) ?></td>
                                                    <td><?= Html::encode($item->answer_en) ?></td>
                                                    <td><?= Html::encode($item->answer_uz) ?></td>
                                                    <td><?=date('d.m.Y H:i',$item->created_at)?></td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm modalUpdateBtn"
                                                                data-url="<?= Url::to(['update-faq-modal', 'id' => $item->id]) ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <a class="btn btn-sm btn-danger"
                                                           href="<?= Url::to(['delete-faq', 'id' => $item->id]) ?>"
                                                           data-method="post"
                                                           data-confirm="Are you sure you want to delete this video?">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane p-3 fade" id="read-more-tab-pane" role="tabpanel"
                                 aria-labelledby="read-more-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>More Info</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                                data-bs-target="#ReadMoreModal">
                                            Create Info
                                        </button>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <div class="modal fade" id="ReadMoreModal" tabindex="-1"
                                             aria-labelledby="ReadMoreModalLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="ReadMoreModalLabel">Create Info</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                       <?= $this->render('_form_read_more', [
                                                          'models' => [new \common\models\ReadMore()],
                                                          'url' => Url::to(['add-read-more', 'course_id' => $model->id]),
                                                          'isUpdate' => false,
                                                       ]) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <table class="table table-sm table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title Ru</th>
                                                <th>Title En</th>
                                                <th>Title Uz</th>
                                                <th>Content Ru</th>
                                                <th>Content En</th>
                                                <th>Content Uz</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i = 1;
                                            foreach ($readMore as $item): ?>
                                                <tr>
                                                    <td><?= $i++ ?></td>
                                                    <td><?= Html::encode($item->title_ru) ?></td>
                                                    <td><?= Html::encode($item->title_en) ?></td>
                                                    <td><?= Html::encode($item->title_uz) ?></td>
                                                    <td><?= Html::encode($item->content_ru) ?></td>
                                                    <td><?= Html::encode($item->content_en) ?></td>
                                                    <td><?= Html::encode($item->content_uz) ?></td>
                                                    <td class="text-nowrap">
                                                        <button class="btn btn-primary btn-sm modalUpdateBtn"
                                                                data-url="<?= Url::to(['update-read-more-modal', 'id' => $item->id]) ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <a class="btn btn-sm btn-danger"
                                                           href="<?= Url::to(['delete-read-more', 'id' => $item->id]) ?>"
                                                           data-method="post"
                                                           data-confirm="Are you sure you want to delete this info?">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade p-4" id="features-tab-pane" role="tabpanel" aria-labelledby="features-tab"
                                 tabindex="0">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>Course Features</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                                data-bs-target="#FeatureModal">
                                            Add Feature
                                        </button>
                                    </div>
                                    <div class="col-md-12">
                                        <!-- Modal -->
                                        <div class="modal fade" id="FeatureModal" tabindex="-1"
                                             aria-labelledby="FeatureModalLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="FeatureModalLabel">New Feature</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                       <?= $this->render('_form_features', [
                                                          'model' => new \common\models\CourseFeatures(),
                                                          'url' => \yii\helpers\Url::to(['add-feature', 'course_id' => $model->id]),
                                                          'course_id' => $model->id,
                                                       ]) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <table class="table table-sm table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name En</th>
                                                <th>Desc En</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i = 1;
                                            foreach ($model->features as $item): ?>
                                                <tr>
                                                    <td><?= $i++ ?></td>
                                                    <td><?= Html::encode($item->name_en) ?></td>
                                                    <td><?= Html::encode($item->desc_en) ?></td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm modalUpdateBtn"
                                                                data-url="<?= Url::to(['update-feature-ajax', 'id' => $item->id]) ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                    </td>
                                                    <td>

                                                        <a href="<?= Url::to(['delete-feature', 'id' => $item->id]) ?>"
                                                           class="btn btn-danger btn-sm" data-method="post"
                                                           data-confirm="Are you sure that you want to delete this item?">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    </div>
    <!-- End Page-content -->

 


<?php
Modal::begin([
   'id' => 'updateModal',
   'title' => 'Редактировать',
   'size' => Modal::SIZE_EXTRA_LARGE,
   'options' => ['tabindex' => false],
]);
echo '<div class="modal-body p-0"></div>';
Modal::end(); ?>
