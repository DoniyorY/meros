<?php

use yii\helpers\Html;

$this->registerMetaTag(['name' => 'description', 'content' => 'Meros institutional Medical English programmes for universities, medical schools and healthcare organisations']);
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Meros, Medical English, B2B, universities, healthcare English']);
$this->title = 'Medical English for Institutions';

$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
$courseName = $courses->{"name_$lang"} ?: $courses->name_en;
$courseDescription = $courses->{"desc_$lang"} ?: $courses->desc_en;
$heroImage = $courses->image ? "$base/uploads/courses/$courses->image" : "$base/images/meros_hospital.jpg";
$courseIcon = $courses->course_icons ? "$base/uploads/course_icons/$courses->course_icons" : "$base/slc_logo_white.png";

$libraryCourses = [
   ['title' => 'English for Doctors', 'level' => 'B2-C1', 'hours' => '120 hrs'],
   ['title' => 'English for Nurses', 'level' => 'B1-B2', 'hours' => '120 hrs'],
   ['title' => 'Nursing Foundation', 'level' => 'A2-B1', 'hours' => '60 hrs'],
   ['title' => 'English for Pharmacy', 'level' => 'B1-B2', 'hours' => '20 hrs'],
   ['title' => 'English for Radiography', 'level' => 'B1-B2', 'hours' => '30 hrs'],
   ['title' => 'Medical Terminology', 'level' => 'A2-B2', 'hours' => '30 hrs'],
   ['title' => 'Medical Academic Purposes', 'level' => 'B2-C1', 'hours' => '50 hrs'],
   ['title' => 'OET & IELTS Preparation', 'level' => 'A2-C1', 'hours' => '300+ hrs'],
];

$faqItems = [
   ['q' => 'What does an institutional licence include?', 'a' => 'Your organisation receives scalable access to Medical English learning materials, practical communication modules, reporting, and onboarding support for learners and administrators.'],
   ['q' => 'Can the platform match our brand?', 'a' => 'Yes. We can prepare a learning space with your logo, colours, cohorts, and programme structure so students work in a familiar environment.'],
   ['q' => 'Is this suitable for mixed-level groups?', 'a' => 'Yes. The content structure supports foundation, intermediate, and advanced learners across healthcare specialisms.'],
   ['q' => 'How do we get started?', 'a' => 'Book a consultation, choose the access model, and our team will help configure the platform and onboard your first cohort.'],
];
?>

<style>
   .meros-b2b-hero .meros-course-hero-bg:after { background: radial-gradient(circle at 22% 24%, rgba(75,192,202,.38), transparent 30%), linear-gradient(90deg, rgba(4,54,63,.92), rgba(7,113,123,.55)); }
   .meros-b2b-card { background:#fff; border:1px solid var(--meros-border); border-radius:28px; box-shadow:var(--meros-shadow); height:100%; padding:28px; }
   .meros-b2b-card h3 { font-size:24px; margin-bottom:14px; }
   .meros-b2b-icon { align-items:center; background:var(--meros-primary-soft); border-radius:18px; color:var(--meros-primary); display:inline-flex; font-size:24px; height:58px; justify-content:center; margin-bottom:18px; width:58px; }
   .meros-b2b-stat { background:linear-gradient(135deg,var(--meros-primary-dark),var(--meros-primary)); border-radius:26px; color:#fff; padding:28px; text-align:center; }
   .meros-b2b-stat strong { color:#fff; display:block; font-size:clamp(34px,4vw,56px); font-weight:900; line-height:1; }
   .meros-b2b-stat span { color:rgba(255,255,255,.82); font-weight:800; }
   .meros-b2b-course { align-items:center; display:flex; gap:18px; }
   .meros-b2b-course-badge { align-items:center; background:linear-gradient(135deg,var(--meros-primary),var(--meros-accent)); border-radius:18px; color:#fff; display:flex; flex:0 0 64px; font-weight:900; height:64px; justify-content:center; }
   .meros-b2b-table { border-collapse:separate; border-spacing:0 12px; width:100%; }
   .meros-b2b-table td, .meros-b2b-table th { background:#fff; border-bottom:1px solid var(--meros-border); border-top:1px solid var(--meros-border); padding:18px; }
   .meros-b2b-table th { color:var(--meros-primary-dark); }
   .meros-b2b-table td:first-child, .meros-b2b-table th:first-child { border-left:1px solid var(--meros-border); border-radius:18px 0 0 18px; font-weight:800; }
   .meros-b2b-table td:last-child, .meros-b2b-table th:last-child { border-right:1px solid var(--meros-border); border-radius:0 18px 18px 0; }
   .meros-b2b-cta { background:linear-gradient(135deg,var(--meros-primary-dark),var(--meros-primary)); border-radius:34px; box-shadow:var(--meros-shadow); overflow:hidden; padding:clamp(34px,5vw,68px); position:relative; }
   .meros-b2b-cta h2, .meros-b2b-cta p, .meros-b2b-cta .meros-kicker { color:#fff; }
</style>

<section id="course-banner" class="meros-course-hero meros-b2b-hero reveal-section" aria-label="Institutional course banner">
   <div class="position-relative meros-course-hero-bg" style="background-image: url(<?= Html::encode($heroImage) ?>)">
      <div class="container h-100">
         <div class="row h-100 align-items-center g-5">
            <div class="col-lg-7 col-12">
               <div class="course-banner-caption meros-course-caption text-start w-100 px-3">
                  <img src="<?= Html::encode($courseIcon) ?>" alt="Meros" class="mb-4">
                  <span class="meros-kicker">For universities, medical schools and healthcare teams</span>
                  <h1 class="course-banner-subtitle mb-4">Institutional Medical English that scales with your learners</h1>
                  <h2 class="mb-4"><?= Html::encode($courseName) ?></h2>
                  <p class="text-white fs-5 mb-4">Give every cohort structured access to healthcare-focused English, practical communication tasks, progress visibility, and a branded learning environment.</p>
                  <div class="d-flex flex-wrap gap-3">
                     <a href="mailto:info@merosedu.uz?subject=Institutional%20Medical%20English%20Demo" class="btn btn-primary btn-lg meros-primary-btn">Book a consultation</a>
                     <a href="#b2b-library" class="btn btn-outline-light btn-lg rounded-pill px-4">Explore programme</a>
                  </div>
               </div>
            </div>
            <div class="col-lg-5 col-12">
               <div class="meros-b2b-card reveal-section">
                  <span class="meros-kicker">One platform</span>
                  <h3>Designed for programme directors, teachers and students</h3>
                  <p>Manage cohorts, assign specialist materials, support classroom delivery, and track outcomes from a single online learning space.</p>
                  <div class="row g-3 mt-2">
                     <div class="col-6"><div class="meros-b2b-stat"><strong>850+</strong><span>hours</span></div></div>
                     <div class="col-6"><div class="meros-b2b-stat"><strong>A2-C2</strong><span>levels</span></div></div>
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
               <span class="meros-kicker">The challenge</span>
               <h2>Medical English needs more than generic materials</h2>
               <p>Healthcare learners need language for clinical placements, patient communication, academic writing, exams, and professional confidence. Institutions need materials that are accurate, manageable, and easy to deploy.</p>
            </div>
            <div class="col-lg-7">
               <div class="row g-4">
                  <?php foreach ([['fa-book','Specialist depth','Profession-specific topics replace generic English with clinical scenarios.'],['fa-layer-group','Simple access','One programme structure reduces fragmented course purchasing and administration.'],['fa-chart-line','Progress visibility','Teachers and managers can use reporting to identify needs and support learners earlier.']] as $card): ?>
                     <div class="col-md-4"><div class="meros-b2b-card"><div class="meros-b2b-icon fa <?= $card[0] ?>"></div><h3><?= Html::encode($card[1]) ?></h3><p><?= Html::encode($card[2]) ?></p></div></div>
                  <?php endforeach; ?>
               </div>
            </div>
         </div>
      </div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container">
         <div class="text-center meros-section-heading"><span class="meros-kicker">The solution</span><h2>One institutional learning hub</h2></div>
         <div class="row g-4">
            <?php foreach ([['Branded platform','Your logo, colours, cohorts and welcome messaging in one dedicated area.'],['Curated access','Use the full library or select the courses that match your programme.'],['Flexible delivery','Support self-study, blended learning, teacher-led classes and exam preparation.'],['Onboarding support','We help configure access and guide administrators and teachers through launch.']] as $card): ?>
               <div class="col-lg-3 col-md-6"><div class="meros-b2b-card"><h3><?= Html::encode($card[0]) ?></h3><p><?= Html::encode($card[1]) ?></p></div></div>
            <?php endforeach; ?>
         </div>
      </div>
   </section>

   <section id="b2b-library" class="meros-section reveal-section">
      <div class="container">
         <div class="row g-4 align-items-end mb-4"><div class="col-lg-8"><span class="meros-kicker">What students get</span><h2>Medical English library for multiple professions and goals</h2></div><div class="col-lg-4"><p>Courses can support healthcare communication, terminology, academic purposes, publication writing, and exam preparation.</p></div></div>
         <div class="row g-4">
            <?php foreach ($libraryCourses as $item): ?>
               <div class="col-lg-3 col-md-6"><div class="meros-b2b-card meros-b2b-course"><div class="meros-b2b-course-badge"><?= Html::encode($item['level']) ?></div><div><h3><?= Html::encode($item['title']) ?></h3><p class="mb-0"><?= Html::encode($item['hours']) ?></p></div></div></div>
            <?php endforeach; ?>
         </div>
      </div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container">
         <div class="row g-5 align-items-center">
            <div class="col-lg-6"><div class="meros-about-card"><span class="meros-kicker">About this course</span><h2><?= Html::encode($courseName) ?></h2><?= $courseDescription ?></div></div>
            <div class="col-lg-6">
               <div class="meros-section-heading"><span class="meros-kicker">Built for institutions</span><h2>Clear value for every role</h2></div>
               <table class="meros-b2b-table"><tbody>
                  <tr><td>Programme directors</td><td>Reporting, cohorts, access control and scalable rollout.</td></tr>
                  <tr><td>Teachers</td><td>Ready-made activities, assignable content and blended-learning support.</td></tr>
                  <tr><td>Students</td><td>Interactive practice on any device with structured healthcare language pathways.</td></tr>
               </tbody></table>
            </div>
         </div>
      </div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container"><div class="meros-b2b-cta text-center"><span class="meros-kicker">Ready to start?</span><h2>Transform how your organisation delivers Medical English</h2><p class="mx-auto mb-4" style="max-width:760px">Tell us about your cohorts, professions and timeline. We will recommend a practical access model and implementation plan.</p><a href="mailto:info@merosedu.uz?subject=Institutional%20Medical%20English%20Demo" class="btn btn-light btn-lg rounded-pill px-5">Request a free consultation</a></div></div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container"><div class="text-center meros-section-heading"><span class="meros-kicker">FAQ</span><h2>Frequently Asked Questions</h2></div><div class="row g-3" id="b2b-faq-accordion">
         <?php foreach ($faqItems as $index => $faq): $faqId = 'b2b-faq-' . $index; ?>
            <div class="col-md-6"><div class="accordion meros-accordion"><div class="accordion-item meros-accordion-item"><h3 class="accordion-header" id="<?= $faqId ?>-heading"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $faqId ?>-collapse" aria-expanded="false" aria-controls="<?= $faqId ?>-collapse"><?= Html::encode($faq['q']) ?></button></h3><div id="<?= $faqId ?>-collapse" class="accordion-collapse collapse" aria-labelledby="<?= $faqId ?>-heading" data-bs-parent="#b2b-faq-accordion"><div class="accordion-body"><?= Html::encode($faq['a']) ?></div></div></div></div></div>
         <?php endforeach; ?>
      </div></div>
   </section>
</div>
