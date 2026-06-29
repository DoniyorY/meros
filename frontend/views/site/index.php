<?php

/** @var yii\web\View $this */

/** @var \frontend\models\ContactForm $contactModel */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::$app->name;
$base = Yii::$app->request->baseUrl;
$params = Yii::$app->params;
$lang = Yii::$app->language;
function translate($key)
{
   $lang = Yii::$app->language;
   return Yii::$app->params[$key][$lang];
}
$homeT = static function ($key) use ($params, $lang) {
   return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};
$b2bStudentBenefits = $params['b2b_student_benefits'] ?? [];
$b2bHomeUrl = Url::to(['courses/index', 'category' => 'university-materials', 'slug' => 'medical-english-courses-for-universities-and-schools']);
$hospitalHomeUrl = Url::to(['courses/index', 'category' => 'healthcare-employers', 'slug' => 'hospitals']);
$hospitalHomeT = static function ($key) use ($params, $lang) {
   return $params['hospital_home'][$key][$lang] ?? $params['hospital_home'][$key]['en'] ?? $key;
};
$comments = $params['comments_arr'][$lang] ?? $params['comments_arr']['en'] ?? [];
if (!empty($comments)) {
    shuffle($comments);
    $comments = array_slice($comments, 0, 3);
}
?>
<!-- Homepage Slider -->
<section id="homepage-slider" class="homepage-slider meros-hero" aria-label="<?= Html::encode($homeT('homepage_slider_aria')) ?>">
   <?php if (!empty($banner)): ?>
       <div class="homepage-banner-carousel owl-carousel owl-theme">
          <?php foreach ($banner as $item): ?>
             <?php
             $title = $item->{"name_$lang"};
             $description = $item->{"desc_$lang"};
             $imageUrl = "$base/uploads/banners/$item->image";
             ?>
              <div class="homepage-banner-slide position-relative meros-hero-slide">
                 <?= Html::img($imageUrl, [
                    'class' => 'homepage-banner-image img-fluid w-100',
                    'alt' => $title ?: Yii::$app->name,
                    'loading' => 'eager',
                 ]) ?>
                  <div class="meros-hero-pulse meros-hero-pulse-one"></div>
                  <div class="meros-hero-pulse meros-hero-pulse-two"></div>
                 
                 <?php if ($title || $description || $item->link): ?>
                     <div class="homepage-banner-caption position-absolute top-50 start-50 translate-middle w-100 px-3">
                         <div class="container">
                             <div class="row align-items-center">
                                 <div class="col-xl-7 col-lg-8 col-md-10">
                                     <div class="meros-hero-card reveal-section">
                                         <span class="meros-kicker w-100"><?= Html::encode(Yii::$app->name) ?></span>
                                        <?php if ($title): ?>
                                            <h2 class="homepage-banner-subtitle mb-3"><?= Html::encode($title) ?></h2>
                                        <?php endif; ?>
                                        
                                        <?php if ($description != '-'): ?>
                                            <h1 class="homepage-banner-title mb-4"><?= Html::encode($description) ?></h1>
                                        <?php endif; ?>
                                        
                                        <?php if ($item->link): ?>
                                            <a href="<?= Url::to([$item->link]) ?>"
                                               class="btn btn-primary btn-lg meros-primary-btn">
                                               <?= translate('banner_button') ?>
                                            </a>
                                        <?php endif; ?>
                                         <div class="banner-logo meros-hero-logos mt-5 d-none"
                                              style="justify-content:space-around">
                                             <img src="<?= "$base/logo-white.png" ?>" alt="Meros" loading="lazy">
                                             <img src="<?= "$base/slc_logo_white.png" ?>" alt="SLC" loading="lazy">
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 <?php endif; ?>
              </div>
          <?php endforeach; ?>
       </div>
   <?php endif; ?>
</section>
<!-- end Homepage Slider -->

<div id="page-content" class="meros-modern-page">
    <section id="right-sidebar" class="meros-section meros-about reveal-section">
        <div class="container">
            <div class="meros-section-heading text-center">
                <h2><?=Yii::$app->name?></h2>
            </div>
            <div class="meros-about-card">
               <?= translate('about_content_index') ?>
            </div>
        </div>
    </section>

    <section class="meros-section meros-b2b-home reveal-section">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5">
                    <span class="meros-kicker"><?= Html::encode($homeT('b2b_home_kicker')) ?></span>
                    <h2><?= Html::encode($homeT('b2b_home_title')) ?></h2>
                    <p><?= Html::encode($homeT('b2b_home_text')) ?></p>
                    <a href="<?= Html::encode($b2bHomeUrl) ?>" class="btn btn-primary btn-lg meros-primary-btn"><?= Html::encode($homeT('b2b_home_button')) ?></a>
                </div>
                <div class="col-lg-7">
                    <div class="meros-news-card overflow-hidden">
                        <a class="meros-news-image" href="<?= Html::encode($b2bHomeUrl) ?>">
                            <img src="<?= Html::encode($base . '/images/med_institute.jpg') ?>" alt="<?= Html::encode($homeT('b2b_home_title')) ?>" loading="lazy">
                            <span><?= Html::encode($homeT('medical_english')) ?></span>
                        </a>
                        <div class="meros-news-body">
                            <div class="row g-3">
                                <?php foreach ($b2bStudentBenefits as $benefit): ?>
                                    <div class="col-md-4">
                                        <p class="mb-0"><span class="fa <?= Html::encode($benefit['icon']) ?> text-primary me-2"></span><?= Html::encode($benefit['text'][$lang] ?? $benefit['text']['en'] ?? '') ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="meros-section meros-hospital-home reveal-section">
        <div class="container">
            <div class="meros-about-card">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        <span class="meros-kicker"><?= Html::encode($hospitalHomeT('kicker')) ?></span>
                        <h2><?= Html::encode($hospitalHomeT('title')) ?></h2>
                        <p class="mb-lg-0"><?= Html::encode($hospitalHomeT('text')) ?></p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="<?= Html::encode($hospitalHomeUrl) ?>" class="btn btn-primary btn-lg meros-primary-btn"><?= Html::encode($hospitalHomeT('button')) ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="meros-section meros-events reveal-section" id="events">
        <div class="container">
            <div class="meros-section-heading text-center">
                <span class="meros-kicker"><?= Html::encode($homeT('events_kicker')) ?></span>
                <h2><?=translate('events')?></h2>
            </div>
            <div class="row g-4">
               <?php foreach ($events as $event): ?>
                  <?php
                  $title = $event->{"name_$lang"} ?: $event->name_en;
                  $description = $event->{"desc_$lang"} ?: $event->desc_en;
                  $image = $event->image ? "$base/uploads/events/$event->image" : "$base/img/event-img-01.jpg";
                  ?>
                   <div class="col-lg-6">
                       <article class="meros-event-card">
                           <a class="meros-event-image" href="<?= Url::to(['events/view', 'id' => $event->id]) ?>">
                               <img src="<?= Html::encode($image) ?>"
                                    alt="<?= Html::encode($title) ?>" loading="lazy">
                               <span class="meros-event-date"><strong><?= date('d', $event->created_at) ?></strong><?= date('M', $event->created_at) ?></span>
                           </a>
                           <div class="meros-event-body">
                               <h3><a href="<?= Url::to(['events/view', 'id' => $event->id]) ?>"><?= Html::encode($title) ?></a></h3>
                               <p class="meros-muted"><span class="fa fa-calendar"></span> <?= date('d.m.Y', $event->created_at) ?></p>
                               <p><?= Html::encode(strip_tags($description)) ?></p>
                               <?php if ($event->video_link): ?>
                                   <p class="meros-muted"><span class="fa fa-youtube-play"></span> <?= Html::encode($homeT('events_youtube_available')) ?></p>
                               <?php endif; ?>
                               <a href="<?= Url::to(['events/view', 'id' => $event->id]) ?>" class="meros-link"><?= Html::encode($homeT('view_details')) ?></a>
                           </div>
                       </article>
                   </div>
               <?php endforeach; ?>
               <?php if (empty($events)): ?>
                   <div class="col-12 text-center"><p class="meros-muted"><?= Html::encode($homeT('events_empty_title')) ?></p></div>
               <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="testimonials" class="meros-section meros-testimonial reveal-section">
        <div class="container">
            <div class="meros-quote-card">
                <span class="meros-kicker"><?=translate('student_outcomes')?></span>
                <?php if (!empty($comments)): ?>
                    <div class="meros-comments-carousel owl-carousel owl-theme">
                        <?php foreach ($comments as $comment): ?>
                            <blockquote class="meros-comment-slide">
                                <p><?= Html::encode($comment['comment'] ?? '') ?></p>
                                <footer><?= Html::encode($comment['author'] ?? '') ?></footer>
                            </blockquote>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <blockquote>
                        <p><?= translate('comments') ?></p>
                    </blockquote>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="course-list" class="meros-section meros-news reveal-section">
        <div class="container">
            <div class="meros-section-heading text-center">
                <span class="meros-kicker"><?= Html::encode($homeT('institute_updates')) ?></span>
                <h2><?= translate('news') ?></h2>
            </div>
            <div class="row g-4">
               <?php foreach ($news as $item): ?>
                   <div class="col-lg-6 col-12">
                       <article class="meros-news-card">
                           <a class="meros-news-image" href="<?= Url::to(['post/view', 'id' => $item->id]) ?>">
                               <img src="<?= "$base/uploads/posts/$item->image" ?>"
                                    alt="<?= Html::encode($item->{"name_$lang"}) ?>" loading="lazy">
                               <span><?= date('d.m.Y', $item->created_at) ?></span>
                           </a>
                           <div class="meros-news-body">
                               <h3>
                                   <a href="<?= Url::to(['post/view', 'id' => $item->id]) ?>"><?= $item->{"name_$lang"} ?></a>
                               </h3>
                               <p><?= $item->{"desc_$lang"} ?></p>
                               <a href="<?= Url::to(['post/view', 'id' => $item->id]) ?>"
                                  class="meros-link"><?= translate('read_more') ?></a>
                           </div>
                       </article>
                   </div>
               <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="meros-section meros-partners reveal-section">
        <div class="container">
            <div class="meros-section-heading text-center">
                <span class="meros-kicker"><?= translate('Trusted partners') ?></span>
                <h2><?= translate('partners') ?></h2>
            </div>
            <div class="meros-partner-card">

                <div class="col-md-6">
                    <a href="https://specialistlanguagecourses.com/" target="_blank" rel="noopener"
                       style="width: 100%;">
                        <img src="<?= "$base/images/partners/slc.svg" ?>" alt="Specialist Language Courses"
                             loading="lazy">
                    </a>
                </div>
                <div class="col-md-6"></div>


            </div>
        </div>
    </section>

    <section class="meros-section meros-consultation reveal-section" id="consultation">
        <div class="meros-consultation-photo" aria-hidden="true"></div>
        <div class="meros-consultation-pattern" aria-hidden="true">
            <span class="meros-medical-circle meros-medical-circle-lg"><i class="fa fa-plus"></i></span>
            <span class="meros-medical-circle meros-medical-circle-sm meros-medical-one"><i class="fa fa-heartbeat"></i></span>
            <span class="meros-medical-circle meros-medical-circle-sm meros-medical-two"><i
                        class="fa fa-stethoscope"></i></span>
        </div>
        <div class="meros-consultation-doctor d-none" aria-label="Reserved place for doctor photo">
            <img src="<?="$base/images/doctor_photo.png"?>" alt="" style="    width: 215px;
    object-fit: cover;
    height: 450px;
    position: absolute;
    right: 10px;
    bottom: -55px;">
        </div>
        <div class="container">
            <div class="row justify-content-lg-end justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-10">
                    <div class="meros-consultation-card">
                        <div class="meros-consultation-brand">
                            <img src="<?= "$base/logo-white.png" ?>" alt="Meros Hospital" loading="lazy">
                            <span><?= Yii::$app->name ?></span>
                        </div>
                        <h2><?= Html::encode($homeT('personal_consultation')) ?><br> <?= Yii::$app->name ?></h2>
                       <?php $form = ActiveForm::begin([
                          'id' => 'homepage-consultation-form',
                          'options' => ['class' => 'meros-consultation-form'],
                          'fieldConfig' => [
                             'template' => "{label}
{input}
{error}",
                             'labelOptions' => ['class' => 'form-label'],
                             'errorOptions' => ['class' => 'invalid-feedback d-block'],
                          ],
                       ]); ?>
                       <?= $form->field($contactModel, 'name')->textInput(['autocomplete' => 'name'])->label($homeT('contact_label_name')) ?>
                       <?= $form->field($contactModel, 'phone')->textInput(['autocomplete' => 'tel'])->label($homeT('contact_label_phone_required')) ?>
                       <?= $form->field($contactModel, 'direction')->textInput()->label($homeT('contact_label_direction')) ?>
                       <?= $form->field($contactModel, 'body')->textarea(['rows' => 5])->label($homeT('contact_label_message')) ?>
                       <?= Html::submitButton($homeT('send_message_button'), ['class' => 'btn meros-consultation-btn', 'name' => 'homepage-contact-button']) ?>
                        <p class="meros-consultation-note"><?= Html::encode($homeT('consultation_privacy_note')) ?></p>
                       <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
