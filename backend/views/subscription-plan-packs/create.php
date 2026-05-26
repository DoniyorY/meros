<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\SubscriptionPlanPacks $model */

$this->title = 'Create Subscription Plan Packs';
$this->params['breadcrumbs'][] = ['label' => 'Subscription Plan Packs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-plan-packs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
