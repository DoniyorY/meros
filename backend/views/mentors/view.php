<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Mentors $model */

$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Mentors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$params=Yii::$app->params;
?>
<div class="mentors-view">
    <div class="row">
        <div class="col-md-10">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-2 text-end">
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fullname',
            'email:email',
            'phone',
            'position_ru',
            'position_en',
            'position_uz',
            'desc_ru',
            'desc_en',
            'desc_uz',
            'image',
            'avatar',
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    if ($data->status == 0) {
                        return "Inactive";
                    } else {
                        return "Active";
                    }
                },
                'format' => 'raw',
                'filter' => $params['status'],
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
                }
            ],
            'instagram_link',
            'linked_in_link',
            'facebook_link',
        ],
    ]) ?>

</div>
