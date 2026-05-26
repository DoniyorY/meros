<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Mentors $model */

$this->title = 'Create Mentors';
$this->params['breadcrumbs'][] = ['label' => 'Mentors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mentors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
