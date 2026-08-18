<?php

use common\models\SeoMeta;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\SeoMetaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Seo Metas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-meta-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Seo Meta', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'entity_type',
            'entity_id',
            'title_ru',
            'title_en',
            //'title_uz',
            //'description_ru:ntext',
            //'description_en:ntext',
            //'description_uz:ntext',
            //'h1_ru',
            //'h1_en',
            //'h1_uz',
            //'text_ru:ntext',
            //'text_en:ntext',
            //'text_uz:ntext',
            //'canonical',
            //'og_image',
            //'robots',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SeoMeta $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
