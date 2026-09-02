<?php

/** @var yii\web\View $this */
/** @var common\models\MedicalDictionary $term */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

$lang = in_array(Yii::$app->language, ['ru', 'en', 'uz'], true) ? Yii::$app->language : 'en';
$copyByLanguage = Yii::$app->params['medical_dictionary'] ?? [];
$copy = $copyByLanguage[$lang] ?? $copyByLanguage['en'];
$categories = Yii::$app->params['medical_dictionary_categories'][$lang] ?? [];
$types = Yii::$app->params['medical_dictionary_types'][$lang] ?? [];

$name = $term->{"name_{$lang}"} ?: $term->name_en;
$description = $term->{"desc_{$lang}"} ?: $term->desc_en;
$content = $term->{"content_{$lang}"} ?: $term->content_en;
$meta = array_filter([$categories[$term->category_id] ?? null, $types[$term->type] ?? null]);
$siteUrl = rtrim(Yii::$app->seo->siteUrl, '/');
$canonicalUrl = $siteUrl . '/' . $lang . '/medical-dictionary/' . rawurlencode($term->{"slug_{$lang}"});
$termId = $canonicalUrl . '#term';

$this->title = $name;
$this->params['seoTitle'] = $term->{"seo_title_{$lang}"} ?: $name;
$this->params['seoDescription'] = $term->{"seo_desc_{$lang}"} ?: $description;
$this->params['canonical'] = $canonicalUrl;
$this->params['ogType'] = 'article';
$this->params['seoAlternates'] = [];
foreach (['ru', 'en', 'uz'] as $alternateLanguage) {
   $alternateSlug = $term->{"slug_{$alternateLanguage}"};
   $this->params['seoAlternates'][$alternateLanguage] = $siteUrl . '/' . $alternateLanguage
      . '/medical-dictionary/' . rawurlencode($alternateSlug);
}
$this->params['seoXDefault'] = $this->params['seoAlternates']['en'];
$this->params['seoSchema'] = [
   [
      '@type' => 'DefinedTerm',
      '@id' => $termId,
      'name' => $name,
      'description' => $description,
      'url' => $canonicalUrl,
      'inDefinedTermSet' => [
         '@type' => 'DefinedTermSet',
         'name' => $copy['title'],
         'url' => $siteUrl . '/' . $lang . '/medical-dictionary',
      ],
   ],
   [
      '@type' => 'BreadcrumbList',
      'itemListElement' => [
         [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => $copy['title'],
            'item' => $siteUrl . '/' . $lang . '/medical-dictionary',
         ],
         [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => $name,
            'item' => $canonicalUrl,
         ],
      ],
   ],
];
?>

<div id="page-content" class="meros-modern-page meros-content-page meros-dictionary-page">
   <section class="meros-section meros-page-hero">
      <div class="container">
         <nav class="meros-dictionary-breadcrumb" aria-label="Breadcrumb">
            <a href="<?= Url::to(['site/medical-dictionary']) ?>"><?= Html::encode($copy['title']) ?></a>
            <span aria-hidden="true">/</span>
            <span aria-current="page"><?= Html::encode($name) ?></span>
         </nav>
         <a class="meros-back-link" href="<?= Url::to(['site/medical-dictionary']) ?>">
            <span aria-hidden="true">←</span> <?= Html::encode($copy['back']) ?>
         </a>
         <article class="meros-term-article">
            <span class="meros-kicker"><?= Html::encode($copy['article']) ?></span>
            <h1><?= Html::encode($name) ?></h1>
            <div class="meros-term-article-meta"><?= Html::encode(implode(' · ', $meta)) ?></div>
            <div class="meros-term-content"><?= HtmlPurifier::process($content) ?></div>
         </article>
      </div>
   </section>
</div>
