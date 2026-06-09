<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\models\CourseLessons;

/** @var yii\web\View $this */
/** @var common\models\Courses $model */

$this->title = $model->name_en;
$this->params['breadcrumbs'][] = ['label' => 'Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$params = Yii::$app->params;
?>
    <div class="courses-view">
        <div class="row">
            <div class="col-md-8">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="col-md-4 text-end">
               <?php
               if ($model->status == 0) {
                  echo Html::a('Inactive',
                     ['status', 'id' => $model->id, 'status' => 1],
                     [
                        'class' => 'btn btn-warning',
                        'data' => [
                           'confirm' => 'Are you sure you want to inactivate this Course?',
                           'method' => 'post'
                        ]
                     ]);
               } else {
                  echo Html::a('Active',
                     ['status', 'id' => $model->id, 'status' => 0],
                     [
                        'class' => 'btn btn-success',
                        'data' => [
                           'confirm' => 'Are you sure you want to activate this Course?',
                           'method' => 'post'
                        ]
                     ]);
               }
               ?>
               <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
               <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                  'class' => 'btn btn-danger',
                  'data' => [
                     'confirm' => 'Are you sure you want to delete this item?',
                     'method' => 'post',
                  ],
               ]) ?>
            </div>
            <hr>
            <div class="col-md-4">
               <?= DetailView::widget([
                  'model' => $model,
                  'attributes' => [
                     'id',
                     [
                        'attribute' => 'category_id',
                        'value' => function ($data) {
                           if ($data->category) {
                              return $data->category->name_en;
                           }
                        }
                     ],
                     'slug',
                     'name_ru',
                     'name_en',
                     'name_uz',
                     'desc_ru:ntext',
                     'desc_en:ntext',
                     'desc_uz:ntext',
                     [
                        'attribute' => 'created_at',
                        'value' => function ($data) {
                           return date('d.m.Y H:i:s', $data->created_at);
                        }
                     ],
                     [
                        'attribute' => 'updated_at',
                        'value' => function ($data) {
                           return date('d.m.Y H:i:s', $data->updated_at);
                        }
                     ],
                     [
                        'attribute' => 'status',
                        'value' => function ($data) {
                           return $data->status == 1 ? 'Active' : 'Inactive';
                        }
                     ],
                     [
                        'attribute' => 'user_id',
                        'value' => function ($data) {
                           return $data->user->username;
                        }
                     ],
                     [
                        'attribute' => 'mentor_id',
                        'value' => function ($data) {
                           if ($data->mentor) {
                              return $data->mentor->fullname;
                           } else {
                              return "Not Set!!!";
                           }
                        }
                     ],
                     'preview_video_link',
                     [
                        'attribute' => 'image',
                        'format' => 'raw',
                        'value' => function ($data) {
                           if (!$data->image) {
                              return 'Not Set!!!';
                           }
                           
                           return Html::img(Yii::$app->request->hostInfo . '/uploads/courses/' . $data->image, [
                              'class' => 'img-thumbnail',
                              'style' => 'max-height: 160px;',
                              'alt' => $data->name_en,
                           ]);
                        }
                     ],
                  ],
               ]) ?>
            </div>
            <div class="col-md-8">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="lessons-tab" data-bs-toggle="tab"
                                data-bs-target="#lessons-tab-pane" type="button" role="tab"
                                aria-controls="lessons-tab-pane" aria-selected="true">Course Lessons
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="features-tab" data-bs-toggle="tab"
                                data-bs-target="#features-tab-pane" type="button" role="tab"
                                aria-controls="features-tab-pane" aria-selected="false">Features
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane p-3 fade show active" id="lessons-tab-pane" role="tabpanel"
                         aria-labelledby="lessons-tab" tabindex="0">
                        <div class="row">
                            <div class="col-md-8">
                                <h4>Course Lessons</h4>
                            </div>
                            <div class="col-md-4">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                        data-bs-target="#LessonModal">
                                    Add Lesson
                                </button>
                            </div>
                            <div class="col-md-12 mt-2">
                                <!-- Modal -->
                                <div class="modal fade" id="LessonModal" tabindex="-1"
                                     aria-labelledby="LessonModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="LessonModalLabel">New Lesson</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                               <?= $this->render('_form_lessons', [
                                                  'model' => new CourseLessons(),
                                                  'url' => \yii\helpers\Url::to(['add-lesson', 'course_id' => $model->id]),
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
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>User ID</th>
                                        <th>Status</th>
                                        <th>Sort</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 1;
                                    foreach ($model->lessons as $item): ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $item->name_en ?></td>
                                            <td><?= $item->desc_en ?></td>
                                            <td><?= date('d.m.Y H:i:s', $item->created_at) ?></td>
                                            <td><?= date('d.m.Y H:i:s', $item->updated_at) ?></td>
                                            <td><?= $item->user->username ?></td>
                                            <td><?php
                                               if ($item->status == 0) {
                                                  echo Html::a('Inactive',
                                                     ['update-lesson-status', 'id' => $item->id, 'status' => 1],
                                                     [
                                                        'class' => 'btn btn-warning btn-sm w-100',
                                                        'data-confirm' => 'Are you sure you want to activate this Lesson?',
                                                     ]);
                                               } else {
                                                  echo Html::a('Active', ['update-lesson-status', 'id' => $item->id, 'status' => 0],
                                                     [
                                                        'class' => 'btn btn-success btn-sm w-100',
                                                        'data-confirm' => 'Are you sure you want to inactivate this Lesson?',
                                                     ]);
                                               }
                                               ?></td>
                                            <td><?= $item->sort ?></td>
                                            <td>
                                                <button class="btn btn-info btn-sm modalUpdateBtn"
                                                        data-url="<?= Url::to(['show-video', 'id' => $item->id]) ?>">
                                                    <i class="bi bi-play-circle"></i>
                                                </button>
                                                <button class="btn btn-primary btn-sm modalUpdateBtn"
                                                        data-url="<?= Url::to(['update-lesson-modal', 'id' => $item->id]) ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <a class="btn btn-sm btn-danger"
                                                   href="<?= Url::to(['delete-video', 'id' => $item->id]) ?>"
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
                    <div class="tab-pane fade p-4" id="features-tab-pane" role="tabpanel" aria-labelledby="features-tab"
                         tabindex="0">
                        <div class="row">
                            <div class="col-md-8">
                                <h4>Course Fetures</h4>
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
                                            <td><?= $item->name_en ?></td>
                                            <td><?= $item->desc_en ?></td>
                                            <td>
                                                <button class="btn btn-primary btn-sm modalUpdateBtn" data-url="<?=Url::to(['update-feature-ajax','id' => $item->id])?>'])?>">
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

    


<?php
Modal::begin([
   'id' => 'updateModal',
   'title' => 'Редактировать',
   'size' => Modal::SIZE_LARGE,
   'options' => ['tabindex' => false],
]);
echo '<div class="modal-body p-0"></div>';
Modal::end(); ?>