<?php

/** @var yii\web\View $this */

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
                                            <a href="<?= Url::to([$item->link]) ?>" class="btn btn-primary btn-lg meros-primary-btn">
                                                <?=translate('banner_button')?>
                                            </a>
                                        <?php endif; ?>
                                         <div class="banner-logo meros-hero-logos mt-5">
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
                <h2><?=translate('about_meros')?></h2>
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
                <?php $events = [
                    ['image' => 'event-img-01.jpg', 'month' => 'jan', 'day' => '18', 'title' => 'Conservatory Exhibit: The garden of india a country and culture revealed', 'place' => 'Matthaei Botanical Gardens'],
                    ['image' => 'event-img-02.jpg', 'month' => 'feb', 'day' => '01', 'title' => 'February Half-Term Activities: Big Stars and Little Secrets', 'place' => 'Pitt Rivers and Natural History Museums'],
                ]; ?>
                <?php foreach ($events as $event): ?>
                    <div class="col-lg-6">
                        <article class="meros-event-card">
                            <div class="meros-event-image">
                                <img src="<?= "$base/img/{$event['image']}" ?>" alt="<?= Html::encode($event['title']) ?>" loading="lazy">
                                <span class="meros-event-date"><strong><?= $event['day'] ?></strong><?= $event['month'] ?></span>
                            </div>
                            <div class="meros-event-body">
                                <h3><a href="#"><?= Html::encode($event['title']) ?></a></h3>
                                <p class="meros-muted"><span class="fa fa-map-marker"></span> <?= Html::encode($event['place']) ?></p>
                                <p>Interactive learning sessions for healthcare professionals who want confident, patient-centered communication.</p>
                                <a href="#" class="meros-link">View Details</a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="testimonials" class="meros-section meros-testimonial reveal-section">
        <div class="container">
            <div class="meros-quote-card">
                <span class="meros-kicker">Student outcomes</span>
                <blockquote>
                    <p>I would recommended this course to anyone who wants work in England. It is an easier way to introduce everyone in the difficult pathway to work in the environment where your language is not English.</p>
                    <footer>Dr Amarylis Cooper</footer>
                </blockquote>
            </div>
        </div>
    </section>

    <section id="course-list" class="meros-section meros-news reveal-section">
        <div class="container">
            <div class="meros-section-heading text-center">
                <span class="meros-kicker">Institute updates</span>
                <h2><?=translate('news')?></h2>
            </div>
            <div class="row g-4">
               <?php foreach ($news as $item): ?>
                   <div class="col-lg-6 col-12">
                       <article class="meros-news-card">
                           <a class="meros-news-image" href="<?= Url::to(['post/view', 'id' => $item->id]) ?>">
                               <img src="<?= "$base/uploads/posts/$item->image" ?>" alt="<?= Html::encode($item->{"name_$lang"}) ?>" loading="lazy">
                               <span><?= date('d.m.Y', $item->created_at) ?></span>
                           </a>
                           <div class="meros-news-body">
                               <h3><a href="<?= Url::to(['post/view', 'id' => $item->id]) ?>"><?= $item->{"name_$lang"} ?></a></h3>
                               <p><?= $item->{"desc_$lang"} ?></p>
                               <a href="<?= Url::to(['post/view', 'id' => $item->id]) ?>" class="meros-link"><?=translate('read_more')?></a>
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
                <span class="meros-kicker">Trusted partners</span>
                <h2><?=translate('partners')?></h2>
            </div>
            <div class="meros-partner-card">
                <a href="https://specialistlanguagecourses.com/" target="_blank" rel="noopener">
                    <img src="<?= "$base/images/partners/slc.svg" ?>" alt="Specialist Language Courses" loading="lazy">
                </a>
            </div>
        </div>
    </section>
</div>
