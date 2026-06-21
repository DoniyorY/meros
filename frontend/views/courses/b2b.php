<?php

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
$courseDescription = $courses->{"desc_$lang"} ?: $courses->desc_en;
$heroImage = $courses->image ? "$base/uploads/courses/$courses->image" : "$base/images/meros_hospital.jpg";
$courseIcon = $courses->course_icons ? "$base/uploads/course_icons/$courses->course_icons" : "$base/slc_logo_white.png";
$consultationSubject = rawurlencode($t('b2b_email_subject'));

$libraryCourses = $tList('b2b_library_courses');
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

<style>
   .meros-b2b-hero .meros-course-hero-bg:after { background: radial-gradient(circle at 22% 24%, rgba(75,192,202,.38), transparent 30%), linear-gradient(90deg, rgba(4,54,63,.92), rgba(7,113,123,.55)); }
   .meros-b2b-card { background:#fff; border:1px solid var(--meros-border); border-radius:28px; box-shadow:var(--meros-shadow); height:100%; padding:28px; }
   .meros-b2b-card h3 { font-size:24px; margin-bottom:14px; }
   .meros-b2b-icon { align-items:center; background:var(--meros-primary-soft); border-radius:18px; color:var(--meros-primary); display:inline-flex; font-size:24px; height:58px; justify-content:center; margin-bottom:18px; width:58px; }
   .meros-b2b-stat { background:linear-gradient(135deg,var(--meros-primary-dark),var(--meros-primary)); border-radius:26px; color:#fff; padding:28px; text-align:center; }
   .meros-b2b-stat strong { color:#fff; display:block; font-size:clamp(34px,4vw,56px); font-weight:900; line-height:1; }
   .meros-b2b-stat span { color:rgba(255,255,255,.82); font-weight:800; }
   .meros-b2b-course { display:block; overflow:hidden; padding:0; position:relative; }
   .meros-b2b-course-image { height:190px; overflow:hidden; position:relative; }
   .meros-b2b-course-image img { height:100%; object-fit:cover; transition:transform .55s ease; width:100%; }
   .meros-b2b-course-content { align-items:center; display:flex; gap:18px; padding:24px; }
   .meros-b2b-course-badge { align-items:center; background:linear-gradient(135deg,var(--meros-primary),var(--meros-accent)); border-radius:18px; color:#fff; display:flex; flex:0 0 64px; font-weight:900; height:64px; justify-content:center; }
   .meros-b2b-course-overlay { align-items:center; background:linear-gradient(135deg,rgba(4,54,63,.94),rgba(7,113,123,.9)); color:#fff; display:flex; flex-direction:column; inset:0; justify-content:center; opacity:0; padding:28px; position:absolute; text-align:center; transform:translateY(18px); transition:opacity .35s ease, transform .35s ease; z-index:2; }
   .meros-b2b-course-overlay h3, .meros-b2b-course-overlay p { color:#fff; }
   .meros-b2b-course:hover .meros-b2b-course-overlay, .meros-b2b-course:focus-within .meros-b2b-course-overlay { opacity:1; transform:translateY(0); }
   .meros-b2b-course:hover .meros-b2b-course-image img, .meros-b2b-course:focus-within .meros-b2b-course-image img { transform:scale(1.08); }
   .meros-b2b-table { border-collapse:separate; border-spacing:0 12px; width:100%; }
   .meros-b2b-table td, .meros-b2b-table th { background:#fff; border-bottom:1px solid var(--meros-border); border-top:1px solid var(--meros-border); padding:18px; }
   .meros-b2b-table th { color:var(--meros-primary-dark); }
   .meros-b2b-table td:first-child, .meros-b2b-table th:first-child { border-left:1px solid var(--meros-border); border-radius:18px 0 0 18px; font-weight:800; }
   .meros-b2b-table td:last-child, .meros-b2b-table th:last-child { border-right:1px solid var(--meros-border); border-radius:0 18px 18px 0; }
   .meros-b2b-cta { background:linear-gradient(135deg,var(--meros-primary-dark),var(--meros-primary)); border-radius:34px; box-shadow:var(--meros-shadow); overflow:hidden; padding:clamp(34px,5vw,68px); position:relative; }
   .meros-b2b-cta h2, .meros-b2b-cta p, .meros-b2b-cta .meros-kicker { color:#fff; }
   .meros-b2b-case { background:linear-gradient(135deg,rgba(236,252,253,.95),#fff); border:1px solid var(--meros-border); border-radius:34px; box-shadow:var(--meros-shadow); overflow:hidden; padding:clamp(28px,5vw,58px); position:relative; }
   .meros-b2b-case:before { background:radial-gradient(circle, rgba(75,192,202,.22), transparent 65%); content:''; height:360px; position:absolute; right:-130px; top:-150px; width:360px; }
   .meros-b2b-case > * { position:relative; z-index:1; }
   .meros-b2b-case-step { background:#fff; border:1px solid var(--meros-border); border-radius:24px; height:100%; padding:24px; }
   .meros-b2b-case-step h3 { color:var(--meros-primary-dark); font-size:22px; }
</style>

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
                     <div class="col-md-4"><div class="meros-b2b-card"><div class="meros-b2b-icon fa <?= Html::encode($card['icon']) ?>"></div><h3><?= Html::encode($card['title']) ?></h3><p><?= Html::encode($card['text']) ?></p></div></div>
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
               <?php $courseImage = $base . '/' . ltrim($item['image'] ?? 'images/meros_hospital.jpg', '/'); ?>
               <div class="col-lg-3 col-md-6">
                  <article class="meros-b2b-card meros-b2b-course" tabindex="0">
                     <div class="meros-b2b-course-image">
                        <img src="<?= Html::encode($courseImage) ?>" alt="<?= Html::encode($item['title']) ?>">
                     </div>
                     <div class="meros-b2b-course-content">
                        <div class="meros-b2b-course-badge"><?= Html::encode($item['level']) ?></div>
                        <div><h3><?= Html::encode($item['title']) ?></h3><p class="mb-0"><?= Html::encode($item['hours']) ?></p></div>
                     </div>
                     <div class="meros-b2b-course-overlay">
                        <h3><?= Html::encode($item['title']) ?></h3>
                        <p><?= Html::encode($limitText($item['description'] ?? '')) ?></p>
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
      <div class="container"><div class="meros-b2b-cta text-center"><span class="meros-kicker"><?= Html::encode($t('b2b_cta_kicker')) ?></span><h2><?= Html::encode($t('b2b_cta_title')) ?></h2><p class="mx-auto mb-4" style="max-width:760px"><?= Html::encode($t('b2b_cta_text')) ?></p><a href="mailto:info@merosedu.uz?subject=<?= $consultationSubject ?>" class="btn btn-light btn-lg rounded-pill px-5"><?= Html::encode($t('b2b_request_consultation')) ?></a></div></div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container"><div class="text-center meros-section-heading"><span class="meros-kicker"><?= Html::encode($t('b2b_faq_kicker')) ?></span><h2><?= Html::encode($t('b2b_faq_title')) ?></h2></div><div class="row g-3" id="b2b-faq-accordion">
         <?php foreach ($faqItems as $index => $faq): $faqId = 'b2b-faq-' . $index; ?>
            <div class="col-md-6"><div class="accordion meros-accordion"><div class="accordion-item meros-accordion-item"><h3 class="accordion-header" id="<?= $faqId ?>-heading"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $faqId ?>-collapse" aria-expanded="false" aria-controls="<?= $faqId ?>-collapse"><?= Html::encode($faq['q']) ?></button></h3><div id="<?= $faqId ?>-collapse" class="accordion-collapse collapse" aria-labelledby="<?= $faqId ?>-heading" data-bs-parent="#b2b-faq-accordion"><div class="accordion-body"><?= Html::encode($faq['a']) ?></div></div></div></div></div>
         <?php endforeach; ?>
      </div></div>
   </section>
</div>
