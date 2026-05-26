<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\SubscriptionPlans $model */

$this->title = 'Update Subscription Plans: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Subscription Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subscription-plans-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
