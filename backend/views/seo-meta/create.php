<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\SeoMeta $model */

$this->title = 'Create Seo Meta';
$this->params['breadcrumbs'][] = ['label' => 'Seo Metas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-meta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
