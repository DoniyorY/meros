<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\SeoMeta $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Seo Metas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="seo-meta-view">

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
            'entity_type',
            'entity_id',
            'title_ru',
            'title_en',
            'title_uz',
            'description_ru:ntext',
            'description_en:ntext',
            'description_uz:ntext',
            'h1_ru',
            'h1_en',
            'h1_uz',
            'text_ru:ntext',
            'text_en:ntext',
            'text_uz:ntext',
            'canonical',
            'og_image',
            'robots',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
