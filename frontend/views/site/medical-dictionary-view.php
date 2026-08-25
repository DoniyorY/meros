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
$canonicalUrl = Url::canonical();

$this->title = $term->{"seo_title_{$lang}"} ?: $name;
$this->registerMetaTag(['name' => 'description', 'content' => $term->{"seo_desc_{$lang}"} ?: $description]);
$this->registerMetaTag(['property' => 'og:title', 'content' => $this->title]);
$this->registerMetaTag(['property' => 'og:description', 'content' => $description]);
$this->registerMetaTag(['property' => 'og:type', 'content' => 'article']);
$this->registerMetaTag(['property' => 'og:url', 'content' => $canonicalUrl]);
$this->registerLinkTag(['rel' => 'canonical', 'href' => $canonicalUrl]);
$this->params['seoSchema'] = [
   '@context' => 'https://schema.org',
   '@type' => 'DefinedTerm',
   'name' => $name,
   'description' => $description,
   'url' => $canonicalUrl,
   'inDefinedTermSet' => [
      '@type' => 'DefinedTermSet',
      'name' => $copy['title'],
      'url' => Url::to(['site/medical-dictionary'], true),
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
