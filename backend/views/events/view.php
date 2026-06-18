<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Events $model */

$this->title = $model->name_en ?: $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="events-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name_ru',
            'name_en',
            'name_uz',
            'desc_ru',
            'desc_en',
            'desc_uz',
            'content_ru:ntext',
            'content_en:ntext',
            'content_uz:ntext',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => $model->image ? Html::img(Yii::getAlias('@web') . '/../uploads/events/' . $model->image, [
                    'class' => 'img-thumbnail',
                    'style' => 'max-width: 320px; max-height: 220px;',
                    'alt' => $model->name_en,
                ]) : null,
            ],
            [
                'attribute' => 'created_at',
                'value' => date('d.m.Y H:i:s', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('d.m.Y H:i:s', $model->updated_at),
            ],
            [
                'attribute' => 'user_id',
                'value' => $model->user ? $model->user->username : null,
            ],
            [
                'attribute' => 'status',
                'value' => Yii::$app->params['status'][$model->status] ?? $model->status,
            ],
            [
                'attribute' => 'video_link',
                'format' => 'raw',
                'value' => $model->video_link ? Html::a(Html::encode($model->video_link), $model->video_link, ['target' => '_blank', 'rel' => 'noopener']) : null,
            ],
        ],
    ]) ?>

</div>
