<?php

use common\models\SubscriptionPlanItems;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Courses";
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
?>
<!-- course banner -->
<section id="course-banner" class="course-banner" aria-label="course banner banner">
    <div class="course-banner position-relative"
         style="background-image: url(<?= "$base/uploads/courses/$courses->image" ?>)">
        <div class="container-fluid h-100">
            <div class="row h-100 align-items-md-center">
                <div class="col-md-6 col-12">
                    <div class="course-banner-caption text-center w-100 px-3 mt-4">
                        <div>
                            <img src="<?= "$base/uploads/course_icons/English-for-Doctor-600x96.png" ?>" alt="">
                        </div>
                        <div>
                            <h1 class="course-banner-subtitle mb-3"><?= $courses->{"name_$lang"} ?></h1>
                        </div>
                        <div>
                            <h2>Advanced Communication Skills</h2>
                        </div>
                    </div>
                </div>
               <?php if ($courses->preview_video_link): ?>
                   <div class="col col-md-5">
                       <iframe src="https://www.youtube.com/embed/<?= $courses->preview_video_link ?>"
                               title="YouTube video player" class="course-preview-video"
                               allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                               referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                   </div>
               <?php endif; ?>
            </div>
        </div>

    </div>
</section>
<!-- end course banner -->

<!-- Page Content -->
<div id="page-content">

    <section id="course-detail">
        <div class="block">
            <div class="container">
                <div class="row g-4">
                    <div class="col-12 about_course_text">
                        <h1>About the Course</h1>
                       <?= $courses->{"desc_$lang"} ?>
                    </div>
                </div>
            </div>
            <!--<div class="background background-color-grey-background"></div>--><!-- /.background -->
        </div>
    </section><!-- /#course-detail -->

    <section id="package-include">
        <div class="block">
            <div class="container">
                <div class="row g-4">
                    <div class="text-center">
                        <h2 class="package-title">All Packages include</h2>
                    </div>
                    <div class="col-md-6">
                        <div class="panel-group package-accordion" id="accordion" role="tablist"
                             aria-multiselectable="true">
                           <?php foreach ($courses->features as $item): ?>
                               <div class="panel panel-default">
                                   <div class="panel-heading" role="tab" id="headingOne">
                                       <h4 class="panel-title">
                                           <a role="button" data-bs-toggle="collapse" data-bs-parent="#accordion"
                                              href="#<?= "collapse-$item->id" ?>" class="collapsed"
                                              aria-expanded="false" aria-controls="<?= "collapse-$item->id" ?>">
                                              <?= $item->{"name_$lang"} ?>
                                           </a>
                                       </h4>
                                   </div>
                                   <div id="<?= "collapse-$item->id" ?>" class="panel-collapse collapsed collapse"
                                        role="tabpanel"
                                        aria-labelledby="headingOne">
                                       <div class="panel-body">
                                          <?= $item->{"desc_$lang"} ?>
                                       </div>
                                   </div>
                               </div>
                           <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="<?= "$base/images/English-for-Doctors-1-300x231.png" ?>" alt="English for Doctors"
                             class="package-image">
                    </div>
                </div>
            </div>
            <div class="background background-color-grey-background"></div>
        </div>
    </section>

    <section id="instructors" class="instructors-section">
        <div class="block">
            <div class="container">
                <div class="instructors">
                    <div class="author-carousel">
                        <div class="author">
                            <blockquote>
                                <article class="paragraph-wrapper">
                                    <div class="inner">
                                        <header>Dr Amarylis Cooper</header>
                                        <p>
                                            I would recommended this course to anyone who wants work in England. It is
                                            an easier way to introduce everyone in the difficult pathway to work in the
                                            environment where your language is not English. And, it is necessary to
                                            recognize how to interact with the patient, or how to show empathy and
                                            respect for their beliefs.
                                        </p>
                                        <figure>Doctor</figure>
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

    <section id="tickets">
        <div class="block">
            <div class="container-fluid">
                <div class="pricing">
                    <div class="row g-4">
                        <div class="col-md-12 text-center">
                            Choose the right plan for you
                        </div>
                        <div class="col-12 text-center">
                            <h1>Subscribe to your <?= $courses->{"name_$lang"} ?> course today!</h1>
                        </div>
                        <div class="row g-4 subscription-benefits-row">
                            <div class="col-12 subscription-benefits">
                                <div class="subscription-benefit secure-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                                         class="bi bi-shield" viewBox="0 0 16 16">
                                        <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                                    </svg>
                                    <span>Secure Payment</span>
                                </div>
                                <div class="subscription-benefit payme-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                                         class="bi bi-shield" viewBox="0 0 16 16">
                                        <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                                    </svg>
                                    <span>Payme Payments</span>
                                </div>
                                <div class="subscription-benefit online-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                                         class="bi bi-globe2" viewBox="0 0 16 16">
                                        <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855q-.215.403-.395.872c.705.157 1.472.257 2.282.287zM4.249 3.539q.214-.577.481-1.078a7 7 0 0 1 .597-.933A7 7 0 0 0 3.051 3.05q.544.277 1.198.49zM3.509 7.5c.036-1.07.188-2.087.436-3.008a9 9 0 0 1-1.565-.667A6.96 6.96 0 0 0 1.018 7.5zm1.4-2.741a12.3 12.3 0 0 0-.4 2.741H7.5V5.091c-.91-.03-1.783-.145-2.591-.332M8.5 5.09V7.5h2.99a12.3 12.3 0 0 0-.399-2.741c-.808.187-1.681.301-2.591.332zM4.51 8.5c.035.987.176 1.914.399 2.741A13.6 13.6 0 0 1 7.5 10.91V8.5zm3.99 0v2.409c.91.03 1.783.145 2.591.332.223-.827.364-1.754.4-2.741zm-3.282 3.696q.18.469.395.872c.552 1.035 1.218 1.65 1.887 1.855V11.91c-.81.03-1.577.13-2.282.287zm.11 2.276a7 7 0 0 1-.598-.933 9 9 0 0 1-.481-1.079 8.4 8.4 0 0 0-1.198.49 7 7 0 0 0 2.276 1.522zm-1.383-2.964A13.4 13.4 0 0 1 3.508 8.5h-2.49a6.96 6.96 0 0 0 1.362 3.675c.47-.258.995-.482 1.565-.667m6.728 2.964a7 7 0 0 0 2.275-1.521 8.4 8.4 0 0 0-1.197-.49 9 9 0 0 1-.481 1.078 7 7 0 0 1-.597.933M8.5 11.909v3.014c.67-.204 1.335-.82 1.887-1.855q.216-.403.395-.872A12.6 12.6 0 0 0 8.5 11.91zm3.555-.401c.57.185 1.095.409 1.565.667A6.96 6.96 0 0 0 14.982 8.5h-2.49a13.4 13.4 0 0 1-.437 3.008M14.982 7.5a6.96 6.96 0 0 0-1.362-3.675c-.47.258-.995.482-1.565.667.248.92.4 1.938.437 3.008zM11.27 2.461q.266.502.482 1.078a8.4 8.4 0 0 0 1.196-.49 7 7 0 0 0-2.275-1.52c.218.283.418.597.597.932m-.488 1.343a8 8 0 0 0-.395-.872C9.835 1.897 9.17 1.282 8.5 1.077V4.09c.81-.03 1.577-.13 2.282-.287z"/>
                                    </svg>
                                    <span>100% Online</span>
                                </div>
                            </div>
                        </div>
                       <?php $i = 1;
                       foreach ($subs as $item): $features = SubscriptionPlanItems::findAll(['plan_id' => $item->id]); ?>
                           <div class="col-lg-4 col-md-6 col-12">
                               <div class="price-box <?= ($i == 2) ? "recommended" : '' ?> subscription-card">
                                   <header><h3><?= $item->{"name_$lang"} ?></h3></header>
                                   <div class="price"><?= Yii::$app->formatter->asDecimal($item->price, 0) ?> uzs</div>
                                   <figure><?php
                                      if ($item->duration_days == 30) {
                                         echo "1 Month";
                                      } elseif ($item->duration_days == 90) {
                                         echo "3 Months";
                                      }
                                      ?></figure>
                                   <a href="<?= Url::to(['get-plan', 'id' => $item->id]) ?>"
                                      class="btn btn-primary btn-lg w-100">Buy Now</a>
                                   <div class="features">
                                       <div class="panel-group" id="accordion-<?= $item->id ?>">
                                          <?php foreach ($features as $v): $k = $v->id ?>
                                              <div class="panel panel-default">
                                                  <div class="panel-heading" style="background: #07707a ">
                                                      <h4 class="panel-title">
                                                          <a data-bs-toggle="collapse"
                                                             style="color: white; font-weight: 600"
                                                             data-bs-parent="#accordion-<?= $item->id ?>"
                                                             href="<?= "#feature-$k" ?>" class="collapsed">
                                                              <span><?= $v->{"name_$lang"} ?></span>
                                                          </a>
                                                      </h4>
                                                  </div>
                                                  <div id="<?= "feature-$k" ?>" class="panel-collapse collapse">
                                                      <div class="panel-body" style="text-align: left">
                                                         <?= $v->{"desc_$lang"} ?>
                                                      </div>
                                                  </div>
                                              </div>
                                          <?php endforeach; ?>
                                       </div>
                                   </div>
                               </div><!-- /.price-box -->
                           </div><!-- /.col-md-3 -->
                          <?php $i++; endforeach; ?>
                    </div><!-- /.row -->
                </div><!-- /.pricing -->
            </div><!-- /.container -->
            <div class="background background-color-grey-background"></div><!-- /.background -->
        </div><!-- /.block -->
    </section><!-- /#tickets -->
</div>
<!-- end Page Content -->
