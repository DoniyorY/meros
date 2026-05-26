<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\SubscriptionPlanPacks $model */

$this->title = 'Update Subscription Plan Packs: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Subscription Plan Packs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subscription-plan-packs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
