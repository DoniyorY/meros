<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\UserCourseProgress $model */

$this->title = 'Create User Course Progress';
$this->params['breadcrumbs'][] = ['label' => 'User Course Progresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-course-progress-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
