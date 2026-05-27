<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CoursePackItems;

/** @var yii\web\View $this */
/** @var common\models\CoursePacks $model */

$this->title = $model->name_en;
$this->params['breadcrumbs'][] = ['label' => 'Course Packs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="course-packs-view">
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
        <div class="col-md-5">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',
                    'name_ru',
                    'name_en',
                    'name_uz',
                    [
                        'attribute' => 'status',
                        'value' => function ($data) {
                            return Yii::$app->params['status'][$data->status];
                        }
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => function ($data) {
                            return $data->user->username;
                        }
                    ],
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
                ],
            ]) ?>
        </div>
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-8">
                    <h4>Pack Courses</h4>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                            data-bs-target="#createPackItemModal">
                        Add Course
                    </button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="createPackItemModal" tabindex="-1"
                     aria-labelledby="createPackItemModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="createPackItemModalLabel">New Lesson</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <?= $this->render('_form_items', [
                                    'model' => new CoursePackItems(),
                                    'url' => \yii\helpers\Url::to(['add-item', 'pack_id' => $model->id]),
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
