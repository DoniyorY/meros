<?php

/** @var yii\web\View $this */
/** @var array<string, string> $copy */
/** @var string $lang */
/** @var array<string, string> $languages */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

$sourceLanguage = $lang === 'en' ? 'ru' : 'en';
$targetLanguage = $lang;
$translateUrl = Url::to(['site/medical-dictionary-translate']);
$dictionaryUrl = rtrim(Url::to(['site/medical-dictionary']), '/');

$this->registerJsFile('@web/js/medical-dictionary-translator.js', ['depends' => AppAsset::class]);
?>

<section class="meros-section meros-dictionary-translator-section">
   <div class="container">
      <div class="meros-dictionary-translator"
           data-dictionary-translator
           data-translate-url="<?= Html::encode($translateUrl) ?>"
           data-dictionary-url="<?= Html::encode($dictionaryUrl) ?>"
           data-empty-message="<?= Html::encode($copy['translator_empty']) ?>"
           data-error-message="<?= Html::encode($copy['translator_error']) ?>">
         <div class="meros-translator-heading">
            <span class="meros-kicker"><?= Html::encode($copy['translator_kicker']) ?></span>
            <h2><?= Html::encode($copy['translator_title']) ?></h2>
            <p><?= Html::encode($copy['translator_text']) ?></p>
         </div>

         <form class="meros-translator-form" data-translator-form>
            <div class="meros-translator-languages">
               <div>
                  <label class="visually-hidden" for="translator-source"><?= Html::encode($copy['translator_input']) ?></label>
                  <select id="translator-source" class="form-select" data-translator-source data-no-selectize>
                     <?php foreach ($languages as $code => $label): ?>
                        <option value="<?= Html::encode($code) ?>" <?= $code === $sourceLanguage ? 'selected' : '' ?>><?= Html::encode($label) ?></option>
                     <?php endforeach; ?>
                  </select>
               </div>
               <button class="meros-translator-swap" type="button" data-translator-swap aria-label="Swap languages">
                  <i class="bi bi-arrow-left-right" aria-hidden="true"></i>
               </button>
               <div>
                  <label class="visually-hidden" for="translator-target"><?= Html::encode($copy['translator_button']) ?></label>
                  <select id="translator-target" class="form-select" data-translator-target data-no-selectize>
                     <?php foreach ($languages as $code => $label): ?>
                        <option value="<?= Html::encode($code) ?>" <?= $code === $targetLanguage ? 'selected' : '' ?>><?= Html::encode($label) ?></option>
                     <?php endforeach; ?>
                  </select>
               </div>
            </div>

            <div class="meros-translator-query">
               <label for="translator-query"><?= Html::encode($copy['translator_input']) ?></label>
               <div class="meros-translator-input-row">
                  <input id="translator-query" class="form-control" type="search" minlength="2" autocomplete="off" placeholder="<?= Html::encode($copy['translator_placeholder']) ?>" data-translator-query>
                  <button class="btn btn-primary meros-primary-btn" type="submit" data-translator-submit><?= Html::encode($copy['translator_button']) ?></button>
               </div>
               <small><?= Html::encode($copy['translator_hint']) ?></small>
            </div>
         </form>

         <div class="meros-translator-results" data-translator-results aria-live="polite"></div>
         <template data-translator-template>
            <article class="meros-translator-result">
               <span class="meros-translator-source" data-result-source></span>
               <h3 data-result-translation></h3>
               <p data-result-description></p>
               <a class="meros-link" data-result-link><?= Html::encode($copy['translator_open']) ?> →</a>
            </article>
         </template>
      </div>
   </div>
</section>
