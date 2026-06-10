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
<section id="homepage-slider" class="homepage-slider" aria-label="Homepage banner slider">
   <?php if (!empty($banner)): ?>
       <div class="homepage-banner-carousel owl-carousel owl-theme">
          <?php foreach ($banner as $item): ?>
             <?php
             $title = $item->{"name_$lang"};
             $description = $item->{"desc_$lang"};
             $imageUrl = "$base/uploads/banners/$item->image";
             ?>
              <div class="homepage-banner-slide position-relative">
                 <?= Html::img($imageUrl, [
                    'class' => 'homepage-banner-image img-fluid w-100',
                    'alt' => $title ?: Yii::$app->name,
                    'loading' => 'eager',
                 ]) ?>
                 
                 <?php if ($title || $description || $item->link): ?>
                     <div class="homepage-banner-caption position-absolute top-50 start-50 translate-middle text-center w-100 px-3">
                         <div class="container-fluid">
                             <div class="row">
                                 <div class="col-md-1"></div>
                                 <div class="col-md-6 banner-title">
                                    <?php if ($title): ?>
                                        <h2 class="homepage-banner-subtitle mb-3"><?= Html::encode($title) ?></h2>
                                    <?php endif; ?>
                                    
                                    <?php if ($description != '-'): ?>
                                        <h1 class="homepage-banner-title mb-4"><?= Html::encode($description) ?></h1>
                                    <?php endif; ?>
                                    
                                    <?php if ($item->link): ?>
                                        <a href="<?= Url::to([$item->link]) ?>" class="btn btn-primary btn-lg">
                                            View Details
                                        </a>
                                    <?php endif; ?>
                                     <div class="banner-logo row">
                                         <div class="col-md-6 mt-4 text-md-start">
                                             <img src="<?= "$base/logo.png" ?>" alt=""
                                                  style="width: 200px; height: auto;">
                                         </div>
                                         <div class="col-md-6">
                                             <img src="<?= "$base/slc_logo.png" ?>" alt=""
                                                  style="width: 200px; height: auto;">
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

<div id="page-content">
    <section id="right-sidebar">
        <div class="block">
            <div class="container">
                <div class="text-center">
                    <h2>About Meros</h2>
                </div>
               <?= translate('about_content_index') ?>
            </div>
            <div class="background background-color-grey-background"></div>
        </div>
    </section>
    <section class="events images" id="events">
        <div class="block">
            <div class="container">
                <header><h1>Events</h1></header>
                <div class="section-content">
                    <article class="event">
                        <div class="event-thumbnail">
                            <figure class="event-image">
                                <div class="image-wrapper"><img src="<?= "$base/" ?>img/event-img-01.jpg"></div>
                            </figure>
                            <figure class="date">
                                <div class="month">jan</div>
                                <div class="day">18</div>
                            </figure>
                        </div>
                        <aside>
                            <header>
                                <a href="#">Conservatory Exhibit: The garden of india a country and culture revealed</a>
                            </header>
                            <div class="additional-info"><span class="fa fa-map-marker"></span> Matthaei Botanical
                                Gardens
                            </div>
                            <div class="description">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et urna
                                    fringilla
                                    volutpat elit non, tristique lectus. Nam blandit odio nisl, ac malesuada lacus
                                    fermentum sit amet.
                                    Vestibulum vitae aliquet felis, ornare feugiat elit. Nulla varius condimentum elit,
                                    sed pulvinar leo sollicitudin vel.
                                </p>
                            </div>
                            <a href="#" class="btn btn-framed btn-color-grey btn-small">View Details</a>
                        </aside>
                    </article><!-- /.event -->
                    <article class="event">
                        <div class="event-thumbnail">
                            <figure class="event-image">
                                <div class="image-wrapper"><img src="<?= "$base/" ?>img/event-img-02.jpg"></div>
                            </figure>
                            <figure class="date">
                                <div class="month">feb</div>
                                <div class="day">01</div>
                            </figure>
                        </div>
                        <aside>
                            <header>
                                <a href="#">February Half-Term Activities: Big Stars and Little Secrets </a>
                            </header>
                            <div class="additional-info"><span class="fa fa-map-marker"></span> Pitt Rivers and Natural
                                History Museums
                            </div>
                            <div class="description">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et urna
                                    fringilla
                                    volutpat elit non, tristique lectus. Nam blandit odio nisl, ac malesuada lacus
                                    fermentum sit amet.
                                    Vestibulum vitae aliquet felis, ornare feugiat elit. Nulla varius condimentum elit,
                                    sed pulvinar leo sollicitudin vel.
                                </p>
                            </div>
                            <a href="#" class="btn btn-framed btn-color-grey btn-small">View Details</a>
                        </aside>
                    </article><!-- /.event -->
                </div><!-- /.section-content -->
            </div>
        </div>
    </section>

    <!-- Testimonial -->
    <section id="testimonials">
        <div class="block">
            <div class="container">
                <div class="author-carousel">
                    <div class="author has-dark-background">
                        <blockquote>
                            <article class="paragraph-wrapper">
                                <div class="inner">
                                    <header>
                                        I would recommended this course to anyone who wants work in England. It is
                                        an easier way to introduce everyone in the difficult pathway to work in the
                                        environment where your language is not English. And, it is necessary to
                                        recognize how to interact with the patient, or how to show empathy and
                                        respect for their beliefs.

                                    </header>
                                    <footer>Dr Amarylis Cooper</footer>
                                </div>
                            </article>
                        </blockquote>
                    </div><!-- /.author -->
                    <div class="author has-dark-background">
                        <blockquote>
                            <!--<figure class="author-picture"><img src="<?php /*= "$base/" */ ?>img/student-testimonial.jpg" alt="">
                            </figure>-->
                            <article class="paragraph-wrapper">
                                <div class="inner">
                                    <header>Morbi nec nisi ante. Quisque lacus ligula, iaculis in elit et, interdum
                                        semper quam. Fusce in interdum tortor.
                                        Ut sollicitudin lectus dolor eget imperdiet libero pulvinar sit amet.
                                    </header>
                                    <footer>Claire Doe</footer>
                                </div>
                            </article>
                        </blockquote>
                    </div><!-- /.author -->
                </div><!-- /.author-carousel -->
            </div><!-- /.container -->
        </div><!-- /.block -->
    </section>
    <!-- end Testimonial -->

    <section id="course-list">
        <div class="block">
            <div class="container">
                <div class="row g-4">
                    <!--MAIN Content-->
                    <div class="col-12">
                        <div id="page-main">
                            <section class="blog-listing" id="blog-listing">
                                <header><h1>News</h1></header>
                                <div class="row g-4">
                                   <?php foreach ($news as $item): ?>
                                       <div class="col-md-6 col-12" style="min-height: 390px;">
                                           <article class="blog-listing-post">
                                               <figure class="blog-thumbnail">
                                                   <figure class="blog-meta"><span
                                                               class="fa fa-file-text-o"></span><?= date('d.m.Y', $item->created_at) ?>
                                                   </figure>
                                                   <div class="image-wrapper">
                                                       <a href="<?= Url::to(['post/view', 'id' => $item->id]) ?>">
                                                           <img src="<?= "$base/uploads/posts/$item->image" ?>"
                                                                style="height: 330px; object-fit: cover">
                                                       </a>
                                                   </div>
                                               </figure>
                                               <aside>
                                                   <header>
                                                       <a href="<?= Url::to(['post/view', 'id' => $item->id]) ?>">
                                                           <h3>
                                                              <?= $item->{"name_$lang"} ?>
                                                           </h3>
                                                       </a>
                                                   </header>
                                                   <div class="description">
                                                       <p>
                                                          <?= $item->{"desc_$lang"} ?>
                                                       </p>
                                                   </div>
                                                   <a href="<?= Url::to(['post/view', 'id' => $item->id]) ?>"
                                                      class="read-more stick-to-bottom">
                                                       Read More
                                                   </a>
                                               </aside>
                                           </article><!-- /article -->
                                       </div><!-- /.col-md-6 -->
                                   <?php endforeach; ?>
                                </div><!-- /.row -->
                            </section><!-- /.blog-listing -->
                        </div><!-- /#page-main -->
                    </div><!-- /.col-md-8 -->
                </div>
            </div>
            <div class="background background-color-grey-background"></div>
        </div>
    </section><!-- /.course-list -->

    <!-- Partners, Become a Partner -->
    <div class="block">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-9 col-md-12">
                    <section id="partners">
                        <header>
                            <h2>Partners & Donors</h2>
                        </header>
                        <div class="section-content">
                            <div class="logos">
                                <div class="logo">
                                    <a href="https://specialistlanguagecourses.com/" target="_blank">
                                        <img src="<?= "$base/images/partners/slc.svg" ?>" style="height: 80px;"
                                             alt="">
                                    </a>
                                </div>

                            </div>
                        </div>
                    </section>
                </div><!-- /.col-md-9 -->
                <div class="col-lg-3 col-md-12">
                    <section id="donation">
                        <header>
                            <h2>Make a Donation</h2>
                        </header>
                        <div class="section-content">
                            <a href="" class="universal-button">
                                <h3>Become a Partner</h3>
                                <figure class="date"><i class="fa fa-arrow-right"></i></figure>
                            </a>
                        </div><!-- /.section-content -->
                    </section>
                </div><!-- /.col-md-3 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>
    <!-- end Partners, Become a Partner -->
</div>


