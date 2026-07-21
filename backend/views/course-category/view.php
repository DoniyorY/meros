<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\CourseCategory $model */

$this->title = $model->name_en;
\yii\web\YiiAsset::register($this);
?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?=Html::encode($this->title)?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?=Yii::$app->homeUrl?>"><?=Yii::$app->name?></a></li>
                            <li class="breadcrumb-item"><a href="<?=Url::to(['index'])?>">Course Categories</a></li>
                            <li class="breadcrumb-item active"><?=Html::encode($this->title)?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="course-category-view">
            <div class="row">
                <div class="col-md-8">
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="col-md-4 text-end">
                   <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                   <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                      'class' => 'btn btn-danger',
                      'data' => [
                         'confirm' => 'Are you sure you want to delete this item?',
                         'method' => 'post',
                      ],
                   ]) ?>
                </div>
                <div class="col-md-5">
                   <?= DetailView::widget([
                      'model' => $model,
                      'attributes' => [
                         'id',
                         'slug',
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
                            'attribute' => 'user_id',
                            'value' => function ($data) {
                               return $data->user->username;
                            },
                         ],
                      ],
                   ]) ?>
                </div>
                <div class="col-md-7">
                <table class="table table-sm table-hover table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name Ru</th>
                        <th>Name En</th>
                        <th>Name Uz</th>
                        <th>Updated At</th>
                        <th>Lvl</th>
                        <th>Status</th>
                        <th>User ID</th>
                      
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($model->courses as $item):?>
                    
                    <tr>
                        <td><?=$i++?></td>
                        <td><?=Html::a($item->name_ru,['courses/view','id'=>$item->id])?></td>
                        <td><?=$item->name_en?></td>
                        <td><?=$item->name_uz?></td>
                        <td><?=date('d.m.Y H:i:s',$item->updated_at)?></td>
                        <td><?=$item->lvl?></td>
                        <td><?=Yii::$app->params['status'][$item->status]?></td>
                        <td><?=$item->user->username?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

