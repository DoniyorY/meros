<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\CourseCategory $model */

$this->title = $model->name_en;
$this->params['breadcrumbs'][] = ['label' => 'Course Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
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
        <div class="col-md-12">
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
    </div>
</div>
