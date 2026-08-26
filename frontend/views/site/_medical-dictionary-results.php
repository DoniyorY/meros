<?php

/** @var yii\web\View $this */
/** @var common\models\MedicalDictionary[] $model */
/** @var yii\data\Pagination $pagination */
/** @var int $filteredCount */
/** @var common\models\search\MedicalDictionarySearch $searchModel */

use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;

$lang = in_array(Yii::$app->language, ['ru', 'en', 'uz'], true) ? Yii::$app->language : 'en';
$categories = Yii::$app->params['medical_dictionary_categories'][$lang] ?? [];
$types = Yii::$app->params['medical_dictionary_types'][$lang] ?? [];
$dictionaryCopy = Yii::$app->params['medical_dictionary'] ?? [];
$copy = $dictionaryCopy[$lang] ?? $dictionaryCopy['en'];
?>
<div data-dictionary-results>
   <div class="meros-dictionary-filter-status">
      <span aria-live="polite"><strong data-dictionary-count><?= $filteredCount ?></strong> <?= Html::encode($copy['results']) ?></span>
      <button class="meros-dictionary-reset" type="button" data-dictionary-reset<?= !$searchModel->query && !$searchModel->category_id && !$searchModel->type ? ' hidden' : '' ?>>
         <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> <?= Html::encode($copy['reset_filters']) ?>
      </button>
   </div>
   <?php if ($model): ?>
      <div class="accordion meros-accordion" id="medical-dictionary-accordion">
         <?php foreach ($model as $term): ?>
            <?php
            $id = 'medical-term-' . (int) $term->id;
            $name = $term->{"name_{$lang}"} ?: $term->name_en;
            $description = $term->{"desc_{$lang}"} ?: $term->desc_en;
            $slug = $term->{"slug_{$lang}"} ?: $term->slug_en;
            $meta = array_filter([$categories[$term->category_id] ?? null, $types[$term->type] ?? null]);
            ?>
            <div class="accordion-item meros-term-item">
               <h3 class="accordion-header meros-term-heading" id="<?= $id ?>-heading">
                  <button class="accordion-button collapsed meros-term-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $id ?>-collapse" aria-expanded="false" aria-controls="<?= $id ?>-collapse">
                     <span><span class="meros-term-title"><?= Html::encode($name) ?></span><span class="meros-term-meta"><?= Html::encode(implode(' · ', $meta)) ?></span></span>
                  </button>
                  <a class="meros-term-link" href="<?= Url::to(['site/medical-dictionary-view', 'slug' => $slug]) ?>" aria-label="<?= Html::encode($copy['details'] . ': ' . $name) ?>">
                     <?= Html::encode($copy['details']) ?> <span aria-hidden="true">→</span>
                  </a>
               </h3>
               <div id="<?= $id ?>-collapse" class="accordion-collapse collapse" aria-labelledby="<?= $id ?>-heading" data-bs-parent="#medical-dictionary-accordion">
                  <div class="accordion-body"><?= Html::encode($description) ?></div>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   <?php else: ?>
      <div class="meros-dictionary-no-results"><?= Html::encode($copy['no_results']) ?></div>
   <?php endif; ?>
   <?= LinkPager::widget([
      'pagination' => $pagination,
      'options' => ['class' => 'pagination meros-dictionary-pagination', 'aria-label' => 'Medical dictionary pagination'],
      'linkContainerOptions' => ['class' => 'page-item'],
      'linkOptions' => ['class' => 'page-link'],
      'disabledListItemSubTagOptions' => ['class' => 'page-link'],
      'maxButtonCount' => 7,
   ]) ?>
</div>
