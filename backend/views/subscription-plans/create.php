<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\SubscriptionPlans $model */

$this->title = 'Create Subscription Plans';
$this->params['breadcrumbs'][] = ['label' => 'Subscription Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-plans-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
