<?php

/** @var yii\web\View $this */
/** @var common\models\MedicalDictionary[] $model */
/** @var yii\data\Pagination $pagination */
/** @var int|string $categoryCount */
/** @var array<string, string> $translatorLanguages */

use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

$lang = in_array(Yii::$app->language, ['ru', 'en', 'uz'], true) ? Yii::$app->language : 'en';
$categories = Yii::$app->params['medical_dictionary_categories'][$lang] ?? [];
$types = Yii::$app->params['medical_dictionary_types'][$lang] ?? [];

$dictionaryCopy = Yii::$app->params['medical_dictionary'] ?? [];
$copy = $dictionaryCopy[$lang] ?? $dictionaryCopy['en'];

$usedCategoryIds = array_unique(array_map(static fn($term) => (int) $term->category_id, $model));
$usedTypeIds = array_unique(array_filter(array_map(static fn($term) => $term->type !== null ? (int) $term->type : null, $model)));
$termCount = $pagination->totalCount;
$pageTermCount = count($model);

$this->title = $copy['title'];
$this->registerJsFile('@web/js/medical-dictionary.js', ['depends' => AppAsset::class]);

?>

<div id="page-content" class="meros-modern-page meros-content-page meros-dictionary-page">
      <section class="meros-section meros-page-hero">
         <div class="container">
            <div class="row align-items-center g-5">
               <div class="col-lg-7">
                  <div class="meros-about-card meros-dictionary-hero-card">
                     <span class="meros-kicker"><?= Html::encode($copy['kicker']) ?></span>
                     <h1><?= Html::encode($copy['title']) ?></h1>
                     <div class="meros-hero-copy"><?= Html::encode($copy['intro']) ?></div>
                     <div class="meros-dictionary-stats">
                        <div class="meros-dictionary-stat"><strong><?= $termCount ?></strong><span><?= Html::encode($copy['terms']) ?></span></div>
                        <div class="meros-dictionary-stat"><strong><?= $categoryCount ?></strong><span><?= Html::encode($copy['categories']) ?></span></div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-5">
                  <div class="meros-dictionary-visual" aria-hidden="true"><span class="meros-dictionary-symbol">A–Z</span></div>
               </div>
            </div>
         </div>
      </section>

      <?= $this->render('_medical-dictionary-translator', [
         'copy' => $copy,
         'lang' => $lang,
         'languages' => $translatorLanguages,
      ]) ?>

      <section class="meros-section">
         <div class="container">
            <div class="meros-dictionary-heading">
               <div>
                  <span class="meros-kicker"><?= Html::encode($copy['list_kicker']) ?></span>
                  <h2><?= Html::encode($copy['list_title']) ?></h2>
                  <p><?= Html::encode($copy['list_text']) ?></p>
               </div>
            </div>
            <div class="meros-dictionary-list">
               <?php if ($model): ?>
                  <div class="meros-dictionary-filters" data-medical-dictionary-filters>
                     <div class="meros-dictionary-search">
                        <label class="visually-hidden" for="medical-dictionary-search"><?= Html::encode($copy['search_label']) ?></label>
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input id="medical-dictionary-search" class="form-control" type="search" placeholder="<?= Html::encode($copy['search_placeholder']) ?>" autocomplete="off" data-dictionary-search>
                     </div>
                     <div class="meros-dictionary-category-filter">
                        <label class="visually-hidden" for="medical-dictionary-category"><?= Html::encode($copy['category_filter']) ?></label>
                        <i class="bi bi-grid" aria-hidden="true"></i>
                        <select id="medical-dictionary-category" class="form-select" data-dictionary-category data-no-selectize>
                           <option value=""><?= Html::encode($copy['all_categories']) ?></option>
                           <?php foreach ($categories as $categoryId => $categoryName): ?>
                              <?php if (in_array((int) $categoryId, $usedCategoryIds, true)): ?>
                                 <option value="<?= (int) $categoryId ?>"><?= Html::encode($categoryName) ?></option>
                              <?php endif; ?>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="meros-dictionary-type-filter">
                        <label class="visually-hidden" for="medical-dictionary-type"><?= Html::encode($copy['type_filter']) ?></label>
                        <i class="bi bi-tags" aria-hidden="true"></i>
                        <select id="medical-dictionary-type" class="form-select" data-dictionary-type data-no-selectize>
                           <option value=""><?= Html::encode($copy['all_types']) ?></option>
                           <?php foreach ($types as $typeId => $typeName): ?>
                              <?php if (in_array((int) $typeId, $usedTypeIds, true)): ?>
                                 <option value="<?= (int) $typeId ?>"><?= Html::encode($typeName) ?></option>
                              <?php endif; ?>
                           <?php endforeach; ?>
                        </select>
                     </div>
                  </div>
                  <div class="meros-dictionary-filter-status">
                     <span aria-live="polite"><strong data-dictionary-count><?= $pageTermCount ?></strong> <?= Html::encode($copy['results']) ?></span>
                     <button class="meros-dictionary-reset" type="button" data-dictionary-reset hidden>
                        <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> <?= Html::encode($copy['reset_filters']) ?>
                     </button>
                  </div>
                  <div class="accordion meros-accordion" id="medical-dictionary-accordion">
                     <?php foreach ($model as $term): ?>
                        <?php
                        $id = 'medical-term-' . (int) $term->id;
                        $name = $term->{"name_{$lang}"} ?: $term->name_en;
                        $description = $term->{"desc_{$lang}"} ?: $term->desc_en;
                        $slug = $term->{"slug_{$lang}"} ?: $term->slug_en;
                        $meta = array_filter([$categories[$term->category_id] ?? null, $types[$term->type] ?? null]);
                        ?>
                        <div class="accordion-item meros-term-item" data-dictionary-item data-category="<?= (int) $term->category_id ?>" data-type="<?= $term->type !== null ? (int) $term->type : '' ?>" data-search="<?= Html::encode(implode(' ', [$name, $description, ...$meta])) ?>">
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
                  <div class="meros-dictionary-no-results" data-dictionary-no-results hidden><?= Html::encode($copy['no_results']) ?></div>
                  <?= LinkPager::widget([
                     'pagination' => $pagination,
                     'options' => ['class' => 'pagination meros-dictionary-pagination', 'aria-label' => 'Medical dictionary pagination'],
                     'linkContainerOptions' => ['class' => 'page-item'],
                     'linkOptions' => ['class' => 'page-link'],
                     'disabledListItemSubTagOptions' => ['class' => 'page-link'],
                     'maxButtonCount' => 7,
                  ]) ?>
               <?php else: ?>
                  <div class="meros-dictionary-empty"><?= Html::encode($copy['empty']) ?></div>
               <?php endif; ?>
            </div>
         </div>
      </section>
</div>
