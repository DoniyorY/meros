<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CourseLessons $model */

$this->title = 'Create Course Lessons';
$this->params['breadcrumbs'][] = ['label' => 'Course Lessons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-lessons-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
