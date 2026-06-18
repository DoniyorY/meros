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

?>
<!-- Homepage Slider -->
<section id="homepage-slider" class="homepage-slider meros-hero" aria-label="Homepage banner slider">
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
                                         <span class="meros-kicker"><?= Html::encode(Yii::$app->name) ?></span>
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
                                         <div class="banner-logo meros-hero-logos mt-5"
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
                <span class="meros-kicker">Medical English Institute</span>
                <h2><?= translate('about_meros') ?></h2>
            </div>
            <div class="meros-about-card">
               <?= translate('about_content_index') ?>
            </div>
        </div>
    </section>

    <section class="meros-section meros-events reveal-section" id="events">
        <div class="container">
            <div class="meros-section-heading text-center">
                <span class="meros-kicker">Academic calendar</span>
                <h2>Events</h2>
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
                                   <p class="meros-muted"><span class="fa fa-youtube-play"></span> YouTube video available</p>
                               <?php endif; ?>
                               <a href="<?= Url::to(['events/view', 'id' => $event->id]) ?>" class="meros-link">View Details</a>
                           </div>
                       </article>
                   </div>
               <?php endforeach; ?>
               <?php if (empty($events)): ?>
                   <div class="col-12 text-center"><p class="meros-muted">Events will be announced soon.</p></div>
               <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="testimonials" class="meros-section meros-testimonial reveal-section">
        <div class="container">
            <div class="meros-quote-card">
                <span class="meros-kicker">Student outcomes</span>
                <blockquote>
                    <p>I would recommended this course to anyone who wants work in England. It is an easier way to
                        introduce everyone in the difficult pathway to work in the environment where your language is
                        not English.</p>
                    <footer>Dr Amarylis Cooper</footer>
                </blockquote>
            </div>
        </div>
    </section>

    <section id="course-list" class="meros-section meros-news reveal-section">
        <div class="container">
            <div class="meros-section-heading text-center">
                <span class="meros-kicker">Institute updates</span>
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
        <div class="meros-consultation-doctor d-none d-md-block" aria-label="Reserved place for doctor photo">
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
                        <h2>Персональная консультация в<br> <?= Yii::$app->name ?></h2>
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
                       <?= $form->field($contactModel, 'name')->textInput(['autocomplete' => 'name'])->label('Как мы можем к вам обращаться') ?>
                       <?= $form->field($contactModel, 'phone')->textInput(['autocomplete' => 'tel'])->label('Телефон (обязательно)') ?>
                       <?= $form->field($contactModel, 'direction')->textInput()->label('Направление') ?>
                       <?= $form->field($contactModel, 'body')->textarea(['rows' => 5])->label('Сообщение') ?>
                       <?= Html::submitButton('Отправить сообщение', ['class' => 'btn meros-consultation-btn', 'name' => 'homepage-contact-button']) ?>
                        <p class="meros-consultation-note">Ваши персональные данные находятся под защитой и используются
                            только для связи с вами.</p>
                       <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
