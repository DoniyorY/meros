<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PostImages $model */

$this->title = 'Create Post Images';
$this->params['breadcrumbs'][] = ['label' => 'Post Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-images-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
