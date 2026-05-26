<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CoursePacks $model */

$this->title = 'Create Course Packs';
$this->params['breadcrumbs'][] = ['label' => 'Course Packs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-packs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
