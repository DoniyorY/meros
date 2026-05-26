<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PostImages $model */

$this->title = 'Update Post Images: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Post Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="post-images-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
