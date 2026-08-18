<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\SeoMeta $model */

$this->title = 'Update Seo Meta: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Seo Metas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="seo-meta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
