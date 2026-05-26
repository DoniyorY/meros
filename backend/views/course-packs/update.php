<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CoursePacks $model */

$this->title = 'Update Course Packs: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Course Packs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="course-packs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
