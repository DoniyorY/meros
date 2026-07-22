<?php

use common\widgets\Alert;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\SubscriptionPlanItems;
use yii\helpers\Url;
/** @var yii\web\View $this */
/** @var common\models\SubscriptionPlans $model */

$this->title = $model->name_en;

\yii\web\YiiAsset::register($this);
?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?= \yii\helpers\Html::encode($this->title) ?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="<?= Yii::$app->homeUrl ?>"><?= Yii::$app->name ?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?= Url::to(['index']) ?>"><?= "Subscriptions" ?></a>
                            </li>
                            <li class="breadcrumb-item active">
                               <?= \yii\helpers\Html::encode($this->title) ?>
                            </li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
       <?= Alert::widget() ?>
        <div class="subscription-plans-view">
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4 text-end">
                   <?php
                   if ($model->status === 0) {
                      echo Html::a('Inactive',
                         ['status', 'id' => $model->id, 'status' => 1],
                         ['class' => 'btn btn-warning', 'data-confirm' => 'Are you sure you want to activate this subscription?']);
                   } else {
                      echo Html::a('Active',
                         ['status', 'id' => $model->id, 'status' => 0],
                         ['class' => 'btn btn-success', 'data-confirm' => 'Are you sure you want to inactive this subscription?']);
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
                            'attribute' => 'course_id',
                            'value' => function ($data) {
                               if ($data->course) {
                                  return $data->course->name_en;
                               } else {
                                  return "Not Set!!!";
                               }
                            }
                         ],
                         'name_ru',
                         'name_en',
                         'name_uz',
                         [
                            'attribute' => 'price',
                            'value' => function ($data) {
                               return Yii::$app->formatter->asDecimal($data->price);
                            }
                         ],
                         'duration_days',
                         'status',
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
                <div class="col-md-8">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#subscription-items" type="button" role="tab"
                                    aria-controls="subscription-items" aria-selected="true">Subscription Facilities
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane"
                                    type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Subscription
                                Courses
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content p-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="subscription-items" role="tabpanel"
                             aria-labelledby="home-tab" tabindex="0">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4>Add Facility</h4>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary w-100" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#new-facility" aria-expanded="false" aria-controls="new-facility">
                                        Create Facility
                                    </button>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="collapse" id="new-facility">
                                        <div class="card card-body">
                                           <?= $this->render('_form_item',
                                              [
                                                 'model' => new SubscriptionPlanItems(),
                                                 'plan_id' => $model->id,
                                                 'url' => \yii\helpers\Url::to(['add-items', 'plan_id' => $model->id])
                                              ]) ?>
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
                                            <th>Description Ru</th>
                                            <th>Description En</th>
                                            <th>Description Uz</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1;
                                        foreach ($model->items as $item): ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $item->name_ru ?></td>
                                                <td><?= $item->name_en ?></td>
                                                <td><?= $item->name_uz ?></td>
                                                <td><?= $item->desc_ru ?></td>
                                                <td><?= $item->desc_en ?></td>
                                                <td><?= $item->desc_uz ?></td>
                                                <td class="text-center">
                                                   <?= Html::button('<i class="bi bi-pencil"></i>',
                                                      [
                                                         'class' => 'btn btn-primary btn-sm modalUpdateBtn',
                                                         'data-url' => \yii\helpers\Url::to(['update-item-modal', 'id' => $item->id]),
                                                      ]) ?>
                                                   <?= Html::a('<i class="bi bi-trash"></i>',
                                                      ['delete-item', 'id' => $item->id],
                                                      [
                                                         'class' => 'btn btn-sm btn-danger',
                                                         'data' => [
                                                            'confirm' => 'Are you sure you want to delete this item?',
                                                            'method' => 'post',
                                                         ]
                                                      ]
                                                   ) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
                             tabindex="0">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

<?php
Modal::begin([
   'id' => 'updateModal',
   'title' => 'Редактировать',
   'size' => Modal::SIZE_LARGE,
   'options' => ['tabindex' => false],
]);
echo '<div class="modal-body p-0"></div>';
Modal::end(); ?>

