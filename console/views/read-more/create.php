<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ReadMore $model */

$this->title = 'Create Read More';
$this->params['breadcrumbs'][] = ['label' => 'Read Mores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="read-more-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
