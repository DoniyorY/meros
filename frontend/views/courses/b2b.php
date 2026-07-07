<?php

use common\models\Courses;
use yii\helpers\Html;

$params = Yii::$app->params;
$lang = Yii::$app->language;
$t = static function ($key) use ($params, $lang) {
   return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};
$tList = static function ($key) use ($params, $lang) {
   return $params[$key][$lang] ?? $params[$key]['en'] ?? [];
};
$limitText = static function ($text, $limit = 200) {
   $text = trim(strip_tags((string)$text));
   if (function_exists('mb_strlen') && function_exists('mb_substr')) {
      return mb_strlen($text, 'UTF-8') > $limit ? mb_substr($text, 0, $limit, 'UTF-8') . '...' : $text;
   }
   return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
};

$this->registerMetaTag(['name' => 'description', 'content' => $t('b2b_meta_description')]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $t('b2b_meta_keywords')]);
$this->title = $t('b2b_page_title');

$base = Yii::$app->request->baseUrl;
$courseName = $courses->{"name_$lang"} ?: $courses->name_en;
$this->params['hideBreadcrumbs'] = true;
$courseDescription = $courses->{"desc_$lang"} ?: $courses->desc_en;
$heroImage = $courses->image ? "$base/uploads/courses/$courses->image" : "$base/images/meros_hospital.jpg";
$courseIcon = $courses->course_icons ? "$base/uploads/course_icons/$courses->course_icons" : "$base/slc_logo_white.png";
$consultationSubject = rawurlencode($t('b2b_email_subject'));

$libraryCourses = Courses::find()
   ->where(['status' => 1])
   ->orderBy(['id' => SORT_ASC])
   ->limit(8)
   ->all();
$challengeCards = $tList('b2b_challenge_cards');
$solutionCards = $tList('b2b_solution_cards');
$roleItems = $tList('b2b_roles');
$faqItems = $tList('b2b_faq_items');
$caseSteps = $tList('b2b_case_steps');

$this->registerJs(<<<JS
const exploreProgrammeButton = document.querySelector('[data-b2b-scroll="programme"]');
if (exploreProgrammeButton) {
   exploreProgrammeButton.addEventListener('click', function (event) {
      const target = document.querySelector(this.getAttribute('href'));
      if (!target) {
         return;
      }
      event.preventDefault();
      target.scrollIntoView({
         behavior: 'smooth',
         block: 'start'
      });
   });
}
JS, \yii\web\View::POS_READY);
?>


<section id="course-banner" class="meros-course-hero meros-b2b-hero reveal-section" aria-label="<?= Html::encode($t('b2b_hero_aria')) ?>">
   <div class="position-relative meros-course-hero-bg" style="background-image: url(<?= Html::encode($heroImage) ?>)">
      <div class="container h-100">
         <div class="row h-100 align-items-center g-5">
            <div class="col-lg-7 col-12">
               <div class="course-banner-caption meros-course-caption text-start w-100 px-3">
                  <img src="<?= Html::encode($courseIcon) ?>" alt="<?= Html::encode($t('b2b_logo_alt')) ?>" class="mb-4">
                  <span class="meros-kicker"><?= Html::encode($t('b2b_hero_kicker')) ?></span>
                  <h1 class="course-banner-subtitle mb-4"><?= Html::encode($t('b2b_hero_title')) ?></h1>
                  <h2 class="mb-4"><?= Html::encode($courseName) ?></h2>
                  <p class="text-white fs-5 mb-4"><?= Html::encode($t('b2b_hero_text')) ?></p>
                  <div class="d-flex flex-wrap gap-3">
                     <a href="mailto:info@merosedu.uz?subject=<?= $consultationSubject ?>" class="btn btn-primary btn-lg meros-primary-btn"><?= Html::encode($t('b2b_book_consultation')) ?></a>
                     <a href="#b2b-library" class="btn btn-outline-light btn-lg rounded-pill px-4" data-b2b-scroll="programme"><?= Html::encode($t('b2b_explore_programme')) ?></a>
                  </div>
               </div>
            </div>
            <div class="col-lg-5 col-12">
               <div class="meros-b2b-card reveal-section">
                  <span class="meros-kicker"><?= Html::encode($t('b2b_platform_kicker')) ?></span>
                  <h3><?= Html::encode($t('b2b_platform_title')) ?></h3>
                  <p><?= Html::encode($t('b2b_platform_text')) ?></p>
                  <div class="row g-3 mt-2">
                     <div class="col-6"><div class="meros-b2b-stat"><strong>850+</strong><span><?= Html::encode($t('b2b_stat_hours')) ?></span></div></div>
                     <div class="col-6"><div class="meros-b2b-stat"><strong>A2-C2</strong><span><?= Html::encode($t('b2b_stat_levels')) ?></span></div></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<div id="page-content" class="meros-modern-page meros-course-page meros-b2b-page">
   <section class="meros-section reveal-section">
      <div class="container">
         <div class="row g-4 align-items-center">
            <div class="col-lg-5">
               <span class="meros-kicker"><?= Html::encode($t('b2b_challenge_kicker')) ?></span>
               <h2><?= Html::encode($t('b2b_challenge_title')) ?></h2>
               <p><?= Html::encode($t('b2b_challenge_text')) ?></p>
            </div>
            <div class="col-lg-7">
               <div class="row g-4">
                  <?php foreach ($challengeCards as $card): ?>
                     <div class="col-md-4"><div class="meros-b2b-card"><div class="meros-b2b-icon bi <?= Html::encode($card['icon']) ?>"></div><h3><?= Html::encode($card['title']) ?></h3><p><?= Html::encode($card['text']) ?></p></div></div>
                  <?php endforeach; ?>
               </div>
            </div>
         </div>
      </div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container">
         <div class="text-center meros-section-heading"><span class="meros-kicker"><?= Html::encode($t('b2b_solution_kicker')) ?></span><h2><?= Html::encode($t('b2b_solution_title')) ?></h2></div>
         <div class="row g-4">
            <?php foreach ($solutionCards as $card): ?>
               <div class="col-lg-3 col-md-6"><div class="meros-b2b-card"><h3><?= Html::encode($card['title']) ?></h3><p><?= Html::encode($card['text']) ?></p></div></div>
            <?php endforeach; ?>
         </div>
      </div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container">
         <div class="meros-b2b-case">
            <div class="row g-4 align-items-center mb-4">
               <div class="col-lg-7">
                  <span class="meros-kicker"><?= Html::encode($t('b2b_case_kicker')) ?></span>
                  <h2><?= Html::encode($t('b2b_case_title')) ?></h2>
               </div>
               <div class="col-lg-5">
                  <p><?= Html::encode($t('b2b_case_text')) ?></p>
               </div>
            </div>
            <div class="row g-4">
               <?php foreach ($caseSteps as $step): ?>
                  <div class="col-lg-4"><div class="meros-b2b-case-step"><h3><?= Html::encode($step['title']) ?></h3><p class="mb-0"><?= Html::encode($step['text']) ?></p></div></div>
               <?php endforeach; ?>
            </div>
         </div>
      </div>
   </section>

   <section id="b2b-library" class="meros-section reveal-section">
      <div class="container">
         <div class="row g-4 align-items-end mb-4"><div class="col-lg-8"><span class="meros-kicker"><?= Html::encode($t('b2b_library_kicker')) ?></span><h2><?= Html::encode($t('b2b_library_title')) ?></h2></div><div class="col-lg-4"><p><?= Html::encode($t('b2b_library_text')) ?></p></div></div>
         <div class="row g-4">
            <?php foreach ($libraryCourses as $item): ?>
               <?php
                  $itemTitle = $item->{"name_$lang"} ?: $item->name_en;
                  $itemDescription = $item->{"desc_$lang"} ?: $item->desc_en;
                  $itemLevel = $item->lvl ?: 'Meros';
                  $courseImage = $item->course_image ? "$base/uploads/courses/courseImage/$item->course_image" : "$base/images/meros_hospital.jpg";
               ?>
               <div class="col-lg-3 col-md-6">
                  <article class="meros-b2b-card meros-b2b-course" tabindex="0">
                     <div class="meros-b2b-course-image">
                        <img src="<?= Html::encode($courseImage) ?>" alt="<?= Html::encode($itemTitle) ?>">
                     </div>
                     <div class="meros-b2b-course-content">
                        <div class="meros-b2b-course-badge"><?= Html::encode($itemLevel) ?></div>
                        <div><h3><?= Html::encode($itemTitle) ?></h3></div>
                     </div>
                     <div class="meros-b2b-course-overlay">
                        <h3><?= Html::encode($itemTitle) ?></h3>
                        <p><?= Html::encode($limitText($itemDescription)) ?></p>
                     </div>
                  </article>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container">
         <div class="row g-5 align-items-center">
            <div class="col-lg-6"><div class="meros-about-card"><span class="meros-kicker"><?= Html::encode($t('b2b_about_course')) ?></span><h2><?= Html::encode($courseName) ?></h2><?= $courseDescription ?></div></div>
            <div class="col-lg-6">
               <div class="meros-section-heading"><span class="meros-kicker"><?= Html::encode($t('b2b_roles_kicker')) ?></span><h2><?= Html::encode($t('b2b_roles_title')) ?></h2></div>
               <table class="meros-b2b-table"><tbody>
                  <?php foreach ($roleItems as $role): ?>
                     <tr><td><?= Html::encode($role['role']) ?></td><td><?= Html::encode($role['text']) ?></td></tr>
                  <?php endforeach; ?>
               </tbody></table>
            </div>
         </div>
      </div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container"><div class="meros-b2b-cta text-center"><span class="meros-kicker"><?= Html::encode($t('b2b_cta_kicker')) ?></span><h2><?= Html::encode($t('b2b_cta_title')) ?></h2><p class="mx-auto mb-4 meros-b2b-cta-text"><?= Html::encode($t('b2b_cta_text')) ?></p><a href="mailto:info@merosedu.uz?subject=<?= $consultationSubject ?>" class="btn btn-light btn-lg rounded-pill px-5"><?= Html::encode($t('b2b_request_consultation')) ?></a></div></div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container"><div class="text-center meros-section-heading"><span class="meros-kicker"><?= Html::encode($t('b2b_faq_kicker')) ?></span><h2><?= Html::encode($t('b2b_faq_title')) ?></h2></div><div class="row g-3" id="b2b-faq-accordion">
         <?php foreach ($faqItems as $index => $faq): $faqId = 'b2b-faq-' . $index; ?>
            <div class="col-md-6"><div class="accordion meros-accordion"><div class="accordion-item meros-accordion-item"><h3 class="accordion-header" id="<?= $faqId ?>-heading"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $faqId ?>-collapse" aria-expanded="false" aria-controls="<?= $faqId ?>-collapse"><?= Html::encode($faq['q']) ?></button></h3><div id="<?= $faqId ?>-collapse" class="accordion-collapse collapse" aria-labelledby="<?= $faqId ?>-heading" data-bs-parent="#b2b-faq-accordion"><div class="accordion-body"><?= Html::encode($faq['a']) ?></div></div></div></div></div>
         <?php endforeach; ?>
      </div></div>
   </section>
</div>
