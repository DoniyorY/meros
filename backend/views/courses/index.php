<?php

use common\models\CourseCategory;
use common\models\Courses;
use common\models\User;
use common\widgets\Alert;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\search\CoursesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Courses';
$this->params['breadcrumbs'][] = $this->title;

$categories = ArrayHelper::map(CourseCategory::find()->orderBy(['name_en' => SORT_ASC])->all(), 'id', 'name_en');
$admins = ArrayHelper::map(
    User::find()->joinWith('assignment')->where(['auth_assignment.item_name' => 'admin'])->orderBy(['username' => SORT_ASC])->all(),
    'id',
    'username'
);
$totalCourses = (int) Courses::find()->count();
$activeCourses = (int) Courses::find()->where(['status' => Courses::STATUS_ACTIVE])->count();
$inactiveCourses = $totalCourses - $activeCourses;
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?= Html::encode($this->title) ?></h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode(Yii::$app->name) ?></a></li>
                        <li class="breadcrumb-item active"><?= Html::encode($this->title) ?></li>
                    </ol>
                </div>
            </div>
        </div>

        <?= Alert::widget() ?>

        <div class="courses-index">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <h2 class="mb-1">Course catalogue</h2>
                    <p class="text-muted mb-0">Manage course content, availability and related materials.</p>
                </div>
                <?= Html::a('<i class="ri-add-line me-1"></i>Create course', ['create'], ['class' => 'btn btn-success px-4']) ?>
            </div>

            <div class="row g-3 mb-4">
                <?php foreach ([
                    ['label' => 'All courses', 'value' => $totalCourses, 'icon' => 'ri-book-open-line', 'class' => 'primary'],
                    ['label' => 'Active', 'value' => $activeCourses, 'icon' => 'ri-checkbox-circle-line', 'class' => 'success'],
                    ['label' => 'Inactive', 'value' => $inactiveCourses, 'icon' => 'ri-pause-circle-line', 'class' => 'warning'],
                ] as $stat): ?>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 mb-0">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted text-uppercase small fw-semibold mb-1"><?= Html::encode($stat['label']) ?></div>
                                    <div class="fs-3 fw-semibold"><?= Yii::$app->formatter->asInteger($stat['value']) ?></div>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title rounded-circle bg-<?= $stat['class'] ?>-subtle text-<?= $stat['class'] ?> fs-3">
                                        <i class="<?= $stat['icon'] ?>"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h5 class="card-title mb-1">All courses</h5>
                        <p class="text-muted small mb-0">Use the filters below each heading to quickly find a course.</p>
                    </div>
                    <span class="badge bg-light text-body fs-12"><?= Yii::$app->formatter->asInteger($dataProvider->getTotalCount()) ?> results</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'layout' => "{items}\n<div class=\"d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 p-3 border-top\"><div class=\"text-muted small\">{summary}</div><div>{pager}</div></div>",
                            'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
                            'headerRowOptions' => ['class' => 'table-light text-muted text-uppercase small'],
                            'filterRowOptions' => ['class' => 'bg-light'],
                            'emptyText' => '<div class="text-center py-5"><i class="ri-search-line fs-32 text-muted"></i><h5 class="mt-2">No courses found</h5><p class="text-muted mb-0">Try changing or clearing the filters.</p></div>',
                            'emptyTextOptions' => ['class' => 'p-0', 'colspan' => 8],
                            'pager' => array_merge(Yii::$app->params['pager'] ?? [], [
                                'options' => ['class' => 'pagination pagination-sm mb-0'],
                                'linkOptions' => ['class' => 'page-link'],
                                'maxButtonCount' => 5,
                            ]),
                            'columns' => [
                                [
                                    'attribute' => 'name_en',
                                    'label' => 'Course',
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'min-width: 260px'],
                                    'value' => static function (Courses $course): string {
                                        $image = $course->image
                                            ? Html::img(Yii::$app->request->hostInfo . '/uploads/courses/' . $course->image, [
                                                'class' => 'rounded object-fit-cover',
                                                'style' => 'width: 48px; height: 48px',
                                                'alt' => $course->name_en,
                                            ])
                                            : '<span class="avatar-title rounded bg-light text-muted fs-4"><i class="ri-book-open-line"></i></span>';

                                        return Html::a(
                                            '<span class="avatar-sm flex-shrink-0">' . $image . '</span>'
                                            . '<span><span class="d-block fw-semibold text-body">' . Html::encode($course->name_en) . '</span>'
                                            . '<span class="d-block text-muted small">' . Html::encode($course->slug) . '</span></span>',
                                            ['view', 'id' => $course->id],
                                            ['class' => 'd-flex align-items-center gap-3 text-decoration-none']
                                        );
                                    },
                                ],
                                [
                                    'attribute' => 'category_id',
                                    'value' => static fn (Courses $course): string => $course->category ? $course->category->name_en : 'Not set',
                                    'filter' => $categories,
                                    'filterInputOptions' => ['class' => 'form-select form-select-sm', 'prompt' => 'All categories'],
                                    'contentOptions' => ['class' => 'text-nowrap'],
                                ],
                                [
                                    'attribute' => 'page_type',
                                    'label' => 'Audience',
                                    'value' => static fn (Courses $course): string => Yii::$app->params['page_type'][$course->page_type] ?? 'Not set',
                                    'filter' => Yii::$app->params['page_type'],
                                    'filterInputOptions' => ['class' => 'form-select form-select-sm', 'prompt' => 'All'],
                                ],
                                [
                                    'attribute' => 'lvl',
                                    'value' => static fn (Courses $course): string => $course->lvl ?: 'Not set',
                                    'filterInputOptions' => ['class' => 'form-control form-control-sm', 'placeholder' => 'Level'],
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'format' => 'raw',
                                    'filter' => false,
                                    'value' => static function (Courses $course): string {
                                        return '<span class="d-block text-nowrap">' . Yii::$app->formatter->asDate($course->updated_at) . '</span>'
                                            . '<span class="text-muted small text-nowrap">' . Yii::$app->formatter->asRelativeTime($course->updated_at) . '</span>';
                                    },
                                ],
                                [
                                    'attribute' => 'user_id',
                                    'label' => 'Author',
                                    'value' => static fn (Courses $course): string => $course->user ? $course->user->username : 'Unknown',
                                    'filter' => $admins,
                                    'filterInputOptions' => ['class' => 'form-select form-select-sm', 'prompt' => 'All authors'],
                                ],
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'filter' => Yii::$app->params['status'],
                                    'filterInputOptions' => ['class' => 'form-select form-select-sm', 'prompt' => 'All'],
                                    'value' => static function (Courses $course): string {
                                        $active = (int) $course->status === Courses::STATUS_ACTIVE;

                                        return Html::a(
                                            '<i class="ri-checkbox-blank-circle-fill me-1" style="font-size: 7px"></i>' . ($active ? 'Active' : 'Inactive'),
                                            ['status', 'id' => $course->id, 'status' => $active ? Courses::STATUS_INACTIVE : Courses::STATUS_ACTIVE],
                                            [
                                                'class' => 'badge rounded-pill ' . ($active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'),
                                                'data' => ['confirm' => 'Are you sure you want to change the course status?', 'method' => 'post'],
                                            ]
                                        );
                                    },
                                ],
                                [
                                    'label' => '',
                                    'format' => 'raw',
                                    'filter' => false,
                                    'contentOptions' => ['class' => 'text-end text-nowrap'],
                                    'value' => static function (Courses $course): string {
                                        return Html::a('<i class="ri-eye-line"></i>', ['view', 'id' => $course->id], [
                                            'class' => 'btn btn-sm btn-soft-primary me-1', 'title' => 'View', 'aria-label' => 'View course',
                                        ]) . Html::a('<i class="ri-edit-line"></i>', ['update', 'id' => $course->id], [
                                            'class' => 'btn btn-sm btn-soft-secondary', 'title' => 'Edit', 'aria-label' => 'Edit course',
                                        ]);
                                    },
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
