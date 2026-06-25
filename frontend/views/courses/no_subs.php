<?php

use common\models\SubscriptionPlanItems;
use yii\helpers\Html;
use yii\helpers\Url;

function translate($key)
{
   $lang = Yii::$app->language;
   return Yii::$app->params[$key][$lang];
}

$this->title = "Courses";
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
$params = Yii::$app->params;
$comments = $params['comments_arr'][$lang] ?? $params['comments_arr']['en'] ?? [];
if (!empty($comments)) {
   shuffle($comments);
   $comments = array_slice($comments, 0, 3);
}
?>
<!-- course banner -->
<section id="course-banner" class="meros-course-hero reveal-section" aria-label="<?= Html::encode(translate('course_banner_aria')) ?>">
    <div class="position-relative meros-course-hero-bg"
         style="background-image: url(<?= Html::encode("$base/uploads/courses/$courses->image") ?>)">
        <div class="container h-100">
            <div class="row h-100 align-items-center g-4">
                <div class="col-md col-12">
                    <div class="course-banner-caption meros-course-caption text-center w-100 px-3 mt-4">
                        <div class="mb-3">
                            <img src="<?= "$base/uploads/course_icons/$courses->course_icons" ?>" alt="">
                        </div>
                        <div>
                           <?php if ($courses->preview_video_link): ?>
                               <span class="meros-kicker"><?= Html::encode(translate('medical_english_course')) ?></span>
                           <?php endif; ?>
                            <h1 class="course-banner-subtitle mb-3"
                                style="text-transform: uppercase"><?= $courses->{"name_$lang"} ?></h1>
                        </div>
                        <div>
                            <h2><?= Html::encode(translate('advanced_communication_skills')) ?></h2>
                        </div>
                    </div>
                </div>
               <?php if ($courses->preview_video_link): ?>
                   <div class="col-md col-12">
                       <div class="meros-video-frame reveal-section">
                           <iframe src="https://www.youtube.com/embed/<?= Html::encode($courses->preview_video_link) ?>"
                                   title="<?= Html::encode(translate('youtube_video_player')) ?>" class="course-preview-video"
                                   allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                   referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                       </div>
                   </div>
               <?php endif; ?>
            </div>
        </div>

    </div>
</section>
<!-- end course banner -->

<!-- Page Content -->
<div id="page-content" class="meros-modern-page meros-course-page">

    <section id="course-detail" class="meros-section reveal-section">
        <div class="block">
            <div class="container">
                <div class="row g-4">
                    <div class="col-12 about_course_text meros-about-card">
                       
                       <?= $courses->{"desc_$lang"} ?>
                    </div>
                </div>
            </div>
            <!--<div class="background background-color-grey-background"></div>--><!-- /.background -->
        </div>
    </section><!-- /#course-detail -->

    <section id="package-include" class="meros-section reveal-section">
        <div class="block">
            <div class="container">
                <div class="row g-4">
                    <div class="text-center">
                        <h2 class="package-title"><?= translate('all_package_include') ?></h2>
                    </div>
                    <div class="col-md-6">
                        <div class="accordion meros-accordion package-accordion" id="package-accordion">
                           <?php foreach ($courses->features as $item): ?>
                              <?php
                              $featureName = $item->{"name_$lang"};
                              $featureDesc = $item->{"desc_$lang"};
                              $englishDescription = trim(strip_tags((string)($item->desc_en ?? '')));
                              $hasEnglishDescription = $englishDescription !== '' && $englishDescription !== '-';
                              ?>
                              <?php if ($hasEnglishDescription): ?>
                                   <div class="accordion-item meros-accordion-item">
                                       <h3 class="accordion-header" id="<?= "package-heading-$item->id" ?>">
                                           <button class="accordion-button collapsed" type="button"
                                                   data-bs-toggle="collapse"
                                                   data-bs-target="#<?= "package-collapse-$item->id" ?>"
                                                   aria-expanded="false"
                                                   aria-controls="<?= "package-collapse-$item->id" ?>">
                                              <?= Html::encode($featureName) ?>
                                           </button>
                                       </h3>
                                       <div id="<?= "package-collapse-$item->id" ?>" class="accordion-collapse collapse"
                                            aria-labelledby="<?= "package-heading-$item->id" ?>"
                                            data-bs-parent="#package-accordion">
                                           <div class="accordion-body">
                                              <?= $featureDesc ?>
                                           </div>
                                       </div>
                                   </div>
                              <?php else: ?>
                                   <div class="meros-check-item">
                                       <span class="fa fa-check" aria-hidden="true"></span>
                                       <span><?= Html::encode($featureName) ?></span>
                                   </div>
                              <?php endif; ?>
                           <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-6 mt-5">
                        <img src="<?= "$base/images/images_for_doctors.png" ?>" alt="<?= Html::encode(translate('english_for_doctors')) ?>"
                             class="package-image">
                    </div>
                </div>
            </div>
            <div class="background background-color-grey-background"></div>
        </div>
    </section>

    <section id="instructors" class="instructors-section meros-section meros-testimonial reveal-section">
        <div class="block">
            <div class="container">
                <div class="instructors">
                    <div class="author-carousel">
                        <div class="author">
                            <blockquote>
                                <article class="paragraph-wrapper">
                                    <div class="inner">
                                        <header><?= Html::encode(translate('testimonial_doctor_name')) ?></header>
                                        <p>
                                            <?= Html::encode(translate('testimonial_course_text')) ?>
                                        </p>
                                        <figure><?= Html::encode(translate('doctor')) ?></figure>
                                    </div>
                                </article>
                            </blockquote>
                        </div><!-- /.author -->
                    </div><!-- /.author-carousel -->
                </div>
            </div>
            <div class="background"></div><!-- /.background -->
        </div>
    </section><!-- /#instructors -->

    <section id="tickets" class="meros-section meros-pricing-section reveal-section">
        <div class="block">
            <div class="container-fluid">
                <div class="pricing meros-pricing">
                    <div class="row g-4">
                        <div class="col-md-12 text-center meros-section-heading">
                            <span class="meros-kicker"><?= translate('choose_the_right_plan_for_you') ?></span>
                        </div>
                        <div class="col-12 text-center">
                            <h1><?php
                               $text = translate('subscribe_course');
                               echo strtr($text, [
                                  '{course}' => \yii\helpers\Html::encode($courses->{"name_$lang"}),
                               ]);
                               ?>
                            </h1>
                        </div>
                        <div class="row g-4 subscription-benefits-row reveal-section">
                            <div class="col-12 subscription-benefits">
                                <div class="subscription-benefit secure-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                                         class="bi bi-shield" viewBox="0 0 16 16">
                                        <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                                    </svg>
                                    <span><?= translate('secure_payment') ?></span>
                                </div>
                                <div class="subscription-benefit payme-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                                         class="bi bi-shield" viewBox="0 0 16 16">
                                        <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                                    </svg>
                                    <span><?= translate('payme_payments') ?></span>
                                </div>
                                <div class="subscription-benefit online-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                                         class="bi bi-globe2" viewBox="0 0 16 16">
                                        <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855q-.215.403-.395.872c.705.157 1.472.257 2.282.287zM4.249 3.539q.214-.577.481-1.078a7 7 0 0 1 .597-.933A7 7 0 0 0 3.051 3.05q.544.277 1.198.49zM3.509 7.5c.036-1.07.188-2.087.436-3.008a9 9 0 0 1-1.565-.667A6.96 6.96 0 0 0 1.018 7.5zm1.4-2.741a12.3 12.3 0 0 0-.4 2.741H7.5V5.091c-.91-.03-1.783-.145-2.591-.332M8.5 5.09V7.5h2.99a12.3 12.3 0 0 0-.399-2.741c-.808.187-1.681.301-2.591.332zM4.51 8.5c.035.987.176 1.914.399 2.741A13.6 13.6 0 0 1 7.5 10.91V8.5zm3.99 0v2.409c.91.03 1.783.145 2.591.332.223-.827.364-1.754.4-2.741zm-3.282 3.696q.18.469.395.872c.552 1.035 1.218 1.65 1.887 1.855V11.91c-.81.03-1.577.13-2.282.287zm.11 2.276a7 7 0 0 1-.598-.933 9 9 0 0 1-.481-1.079 8.4 8.4 0 0 0-1.198.49 7 7 0 0 0 2.276 1.522zm-1.383-2.964A13.4 13.4 0 0 1 3.508 8.5h-2.49a6.96 6.96 0 0 0 1.362 3.675c.47-.258.995-.482 1.565-.667m6.728 2.964a7 7 0 0 0 2.275-1.521 8.4 8.4 0 0 0-1.197-.49 9 9 0 0 1-.481 1.078 7 7 0 0 1-.597.933M8.5 11.909v3.014c.67-.204 1.335-.82 1.887-1.855q.216-.403.395-.872A12.6 12.6 0 0 0 8.5 11.91zm3.555-.401c.57.185 1.095.409 1.565.667A6.96 6.96 0 0 0 14.982 8.5h-2.49a13.4 13.4 0 0 1-.437 3.008M14.982 7.5a6.96 6.96 0 0 0-1.362-3.675c-.47.258-.995.482-1.565.667.248.92.4 1.938.437 3.008zM11.27 2.461q.266.502.482 1.078a8.4 8.4 0 0 0 1.196-.49 7 7 0 0 0-2.275-1.52c.218.283.418.597.597.932m-.488 1.343a8 8 0 0 0-.395-.872C9.835 1.897 9.17 1.282 8.5 1.077V4.09c.81-.03 1.577-.13 2.282-.287z"/>
                                    </svg>
                                    <span><?= translate('online') ?></span>
                                </div>
                            </div>
                        </div>
                       <?php
                       $subscriptionPlanCount = (is_array($subs) || $subs instanceof Countable) ? count($subs) : 0;
                       $useSubscriptionCarousel = $subscriptionPlanCount > 3;
                       ?>
                       <?php if ($useSubscriptionCarousel): ?>
                           <div class="col-12 subscription-plans-carousel-wrap">
                               <div class="subscription-plans-carousel owl-carousel owl-theme">
                       <?php else: ?>
                           <div class="col-12">
                               <div class="row g-4 subscription-plans-row <?= ($subscriptionPlanCount === 2) ? 'justify-content-between' : '' ?>">
                       <?php endif; ?>
                       <?php $i = 1;
                       foreach ($subs as $item): $features = SubscriptionPlanItems::findAll(['plan_id' => $item->id]); ?>
                           <div class="<?= $useSubscriptionCarousel ? 'subscription-plan-carousel-item' : 'col-lg-4 col-md-6 col-12' ?>">
                               <div class="price-box <?= ($i == 2) ? "recommended" : '' ?> subscription-card meros-plan-card reveal-section">
                                   <header><h3><?= $item->{"name_$lang"} ?></h3></header>
                                   <div class="price"><?= Yii::$app->formatter->asDecimal($item->price, 0) ?> uzs</div>
                                   <figure style="height: 27px;"><?php
                                      if ($item->duration_days == 30) {
                                         $month = floor($item->duration_days / 30);
                                         switch ($lang) {
                                            case 'en':
                                               echo "$month Month";
                                               break;
                                            case 'ru':
                                               echo "$month Месяц";
                                               break;
                                            case 'uz':
                                               echo "$month Oy";
                                               break;
                                         }
                                      } elseif ($item->duration_days == 90) {
                                         $month = floor($item->duration_days / 30);
                                         switch ($lang) {
                                            case 'en':
                                               echo "$month Month";
                                               break;
                                            case 'ru':
                                               echo "$month Месяца";
                                               break;
                                            case 'uz':
                                               echo "$month Oy";
                                               break;
                                         }
                                      }
                                      ?></figure>
                                   <a href="<?= Url::to(['get-plan', 'id' => $item->id]) ?>"
                                      class="btn btn-primary btn-lg w-100 meros-primary-btn"><?= Html::encode(translate('buy_now')) ?></a>
                                   <div class="features">
                                       <div class="accordion meros-accordion meros-plan-accordion"
                                            id="<?= "plan-accordion-$item->id" ?>">
                                          <?php foreach ($features as $v): $k = $v->id ?>
                                              <div class="accordion-item meros-accordion-item">
                                                  <h4 class="accordion-header" id="<?= "plan-feature-heading-$k" ?>">
                                                      <button class="accordion-button collapsed" type="button"
                                                              data-bs-toggle="collapse"
                                                              data-bs-target="<?= "#plan-feature-$k" ?>"
                                                              aria-expanded="false"
                                                              aria-controls="<?= "plan-feature-$k" ?>">
                                                         <?= Html::encode($v->{"name_$lang"}) ?>
                                                      </button>
                                                  </h4>
                                                  <div id="<?= "plan-feature-$k" ?>" class="accordion-collapse collapse"
                                                       aria-labelledby="<?= "plan-feature-heading-$k" ?>"
                                                       data-bs-parent="#<?= "plan-accordion-$item->id" ?>">
                                                      <div class="accordion-body">
                                                         <?= $v->{"desc_$lang"} ?>
                                                      </div>
                                                  </div>
                                              </div>
                                          <?php endforeach; ?>
                                       </div>
                                   </div>
                               </div><!-- /.price-box -->
                           </div><!-- /.subscription plan item -->
                          <?php $i++; endforeach; ?>
                       <?php if ($useSubscriptionCarousel): ?>
                               </div>
                           </div>
                       <?php else: ?>
                               </div>
                           </div>
                       <?php endif; ?>

                        <div class="col-12">
                            <div class="meros-faq-card reveal-section">
                                <div class="meros-section-heading text-center">
                                    <span class="meros-kicker"><?= Html::encode(translate('faq')) ?></span>
                                    <h2><?= Html::encode(translate('frequently_asked_questions')) ?></h2>
                                </div>
                                <div class="row g-3" id="course-faq-accordion">
                                   <?php foreach ($faqItems as $faq): ?>
                                       <div class="col-md-6 col-12">
                                           <div class="accordion meros-accordion meros-faq-accordion">
                                               <div class="accordion-item meros-accordion-item">
                                                   <h3 class="accordion-header" id="<?= $faq['id'] ?>-heading">
                                                       <button class="accordion-button collapsed" type="button"
                                                               data-bs-toggle="collapse"
                                                               data-bs-target="#<?= $faq['id'] ?>-collapse"
                                                               aria-expanded="false"
                                                               aria-controls="<?= $faq['id'] ?>-collapse">
                                                          <?= Html::encode($faq["question_$lang"]) ?>
                                                       </button>
                                                   </h3>
                                                   <div id="<?= $faq['id'] ?>-collapse"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="<?= $faq['id'] ?>-heading"
                                                        data-bs-parent="#course-faq-accordion">
                                                       <div class="accordion-body">
                                                          <?= Html::encode($faq["answer_$lang"]) ?>
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.pricing -->
            </div><!-- /.container -->
            <div class="background background-color-grey-background"></div><!-- /.background -->
        </div><!-- /.block -->
    </section><!-- /#tickets -->
</div>
<!-- end Page Content -->
