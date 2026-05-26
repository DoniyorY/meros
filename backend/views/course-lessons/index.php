<?php

use common\models\CourseLessons;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\CourseLessonsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Course Lessons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-lessons-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Course Lessons', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'course_id',
            'slug',
            'name_ru',
            'name_en',
            //'name_uz',
            //'desc_ru:ntext',
            //'desc_en:ntext',
            //'desc_uz:ntext',
            //'created_at',
            //'updated_at',
            //'status',
            //'user_id',
            //'sort',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, CourseLessons $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
