<?php

/** @var yii\web\View $this */
/** @var common\models\MedicalDictionary[] $model */
/** @var yii\data\Pagination $pagination */
/** @var common\models\search\MedicalDictionarySearch $searchModel */
/** @var int|string $categoryCount */
/** @var int|string $termCount */
/** @var int[] $availableCategoryIds */
/** @var int[] $availableTypeIds */
/** @var array<string, string> $translatorLanguages */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

$seo = Yii::$app->seo;
$lang = $seo->getCurrentLanguage();
$categories = Yii::$app->params['medical_dictionary_categories'][$lang];
$types = Yii::$app->params['medical_dictionary_types'][$lang];

$dictionaryCopy = Yii::$app->params['medical_dictionary'];
$copy = $dictionaryCopy[$lang];

$this->title = $copy['title'];
$page = max(1, (int) Yii::$app->request->get('page', 1));
$hasFilters = trim((string) $searchModel->query) !== ''
   || !empty($searchModel->category_id)
   || !empty($searchModel->type);
$canonicalQuery = $page > 1 ? ['page' => $page] : [];

$this->params['seoDescription'] = $copy['intro'];
$this->params['schemaPageType'] = 'CollectionPage';
$this->params['canonical'] = $seo->localizedUrl('medical-dictionary', $lang, $canonicalQuery);
$this->params['seoNoIndex'] = $hasFilters;
$this->params['seoAlternates'] = [];
foreach ($seo->languages as $alternateLanguage) {
   $this->params['seoAlternates'][$alternateLanguage] = $seo->localizedUrl(
      'medical-dictionary',
      $alternateLanguage,
      $canonicalQuery,
   );
}
$this->params['seoXDefault'] = $this->params['seoAlternates']['en'];
$this->params['seoSchema'] = [
   '@type' => 'DefinedTermSet',
   '@id' => $this->params['canonical'] . '#dictionary',
   'name' => $copy['title'],
   'description' => $copy['intro'],
   'url' => $this->params['canonical'],
];
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
                  <div class="meros-dictionary-filters" data-medical-dictionary-filters data-url="<?= Url::to(['site/medical-dictionary']) ?>">
                     <div class="meros-dictionary-search">
                        <label class="visually-hidden" for="medical-dictionary-search"><?= Html::encode($copy['search_label']) ?></label>
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input id="medical-dictionary-search" class="form-control" type="search" value="<?= Html::encode((string) $searchModel->query) ?>" placeholder="<?= Html::encode($copy['search_placeholder']) ?>" autocomplete="off" data-dictionary-search>
                     </div>
                     <div class="meros-dictionary-category-filter">
                        <label class="visually-hidden" for="medical-dictionary-category"><?= Html::encode($copy['category_filter']) ?></label>
                        <i class="bi bi-grid" aria-hidden="true"></i>
                        <select id="medical-dictionary-category" class="form-select" data-dictionary-category data-no-selectize>
                           <option value=""><?= Html::encode($copy['all_categories']) ?></option>
                           <?php foreach ($categories as $categoryId => $categoryName): ?>
                              <?php if (in_array((int) $categoryId, $availableCategoryIds, true)): ?>
                                 <option value="<?= (int) $categoryId ?>"<?= (int) $searchModel->category_id === (int) $categoryId ? ' selected' : '' ?>><?= Html::encode($categoryName) ?></option>
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
                              <?php if (in_array((int) $typeId, $availableTypeIds, true)): ?>
                                 <option value="<?= (int) $typeId ?>"<?= (int) $searchModel->type === (int) $typeId ? ' selected' : '' ?>><?= Html::encode($typeName) ?></option>
                              <?php endif; ?>
                           <?php endforeach; ?>
                        </select>
                     </div>
                  </div>
                  <?= $this->render('_medical-dictionary-results', [
                     'model' => $model,
                     'pagination' => $pagination,
                     'filteredCount' => $filteredCount,
                     'searchModel' => $searchModel,
                  ]) ?>
            </div>
         </div>
      </section>
</div>
