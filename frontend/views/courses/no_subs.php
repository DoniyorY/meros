<?php

use common\models\SubscriptionPlanItems;

$this->title = "Courses";
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
?>
<!-- Landing Page Slider -->
<section id="homepage-slider">
    <div class="flexslider">
        <ul class="slides">
            <li class="slide">
                <figure>
                    <div class="slide-wrapper">
                        <div class="inner">
                            <div class="container" style="display: none">
                                <h2>Business Course</h2>
                                <h1>Be a marketing guru</h1>
                                <div class="scroll-down">
                                    <h3>Scroll down to find out more</h3>
                                    <div class="fa fa-angle-down"></div>
                                </div><!-- /.scroll-down -->
                            </div>
                        </div><!-- /.inner -->
                    </div><!-- /.wrapper -->
                </figure>
                <img src="<?= "$base/uploads/courses/$courses->image" ?>">
            </li><!-- /.slide -->
        </ul><!-- /.slides -->
    </div><!-- /.flexslider -->
</section>
<!-- end Landing Page Slider -->

<!-- Page Content -->
<div id="page-content">

    <section id="course-detail">
        <div class="block">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-6 about_course_text">
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
                <div class="row">
                    <div class="text-center">
                        <h2 style="font-size: 40px;">All Packages include</h2>
                    </div>
                    <div class="col-md-6">
                        <div class="panel-group package-accordion" id="accordion" role="tablist"
                             aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Profession-specific focus
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        The course is designed for medical professionals and students. It is not a
                                        general English or even a general healthcare English course. Rather, it focuses
                                        on the language used by doctors in practice – with patients, with colleagues, in
                                        hospital settings, and when researching symptoms, conditions and treatments.
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                           aria-controls="collapseTwo">
                                            Available on any device, off- and online
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        Study on PC, tablet and phone through the course app. The course can be
                                        downloaded so you can study offline as well as online.
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingThree">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapseThree" aria-expanded="false"
                                           aria-controls="collapseThree">
                                            OET preparation support
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingThree">
                                    <div class="panel-body">
                                        This English for doctors course is also excellent preparation for OET Medicine,
                                        required by regulatory bodies for doctors registering to work in
                                        English-speaking countries such as the US, UK, Australia, Canada and Ireland. It
                                        provides the language foundation that those studying for OET Medicine need. The
                                        course can be combined with SLC’s Reach OET B Medicine.
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingFour">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapseFour" aria-expanded="false"
                                           aria-controls="collapseFour">
                                            Face to face tuition option
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFour" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingFour">
                                    <div class="panel-body">
                                        Getting personalised and targeted practice and feedback can effectively
                                        accelerate and improve your learning. If you would like to work with an expert
                                        English for Healthcare teacher, then check out the <strong>PREMIUM</strong>
                                        option below.
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingFive">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapseFive" aria-expanded="false"
                                           aria-controls="collapseThree">
                                            Official CPD certificate on completion
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFive" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingFive">
                                    <div class="panel-body">
                                        When you complete the course, you receive an official CPD certificate. English
                                        for Doctors is accredited by the CPD Standards Office, whose qualifications are
                                        recognised worldwide.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="<?= "$base/images/English-for-Doctors-1-300x231.png" ?>" alt="" style="
    width: 550px;
    height: 430px;
    object-fit: cover;
">
                    </div>
                </div>
            </div>
            <div class="background background-color-grey-background"></div>
        </div>
    </section>

    <section id="instructors" style="margin-top: 50px;">
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

    <hr>

    <section id="tickets">
        <div class="block">
            <div class="container-fluid">
                <div class="pricing">
                    <div class="row">
                       <?php $i = 1;
                       foreach ($subs as $item): $features = SubscriptionPlanItems::findAll(['plan_id' => $item->id]); ?>
                           <div class="col-md-4 col-sm-12">
                               <div class="price-box <?= ($i == 2) ? "recommended" : '' ?>" style="padding: 20px;">
                                   <header><h3><?= $item->{"name_$lang"} ?></h3></header>
                                   <div class="price"><?= Yii::$app->formatter->asDecimal($item->price, 0) ?> uzs</div>
                                   <figure><?php
                                      if ($item->duration_days == 30) {
                                         echo "1 Month";
                                      } elseif ($item->duration_days == 90) {
                                         echo "3 Months";
                                      }
                                      ?></figure>
                                   <a href="#" class="btn btn-large">Buy Now</a>
                                   <ul class="features">
                                       <div class="panel-group" id="accordion-<?= $item->id ?>">
                                          <?php foreach ($features as $v): $k = $v->id ?>
                                              <div class="panel panel-default">
                                                  <div class="panel-heading" style="background: #07707a ">
                                                      <h4 class="panel-title" style="color: white; font-weight: 600">
                                                          <a data-toggle="collapse"
                                                             data-parent="#accordion-<?= $item->id ?>"
                                                             href="<?= "#feature-$k" ?>" class="collapsed">
                                                              <span><?= $v->{"name_$lang"} ?></span>
                                                          </a>
                                                      </h4>
                                                  </div>
                                                  <div id="<?= "feature-$k" ?>" class="panel-collapse collapse"
                                                       style="height: 0px;">
                                                      <div class="panel-body" style="text-align: left">
                                                         <?= $v->{"desc_$lang"} ?>
                                                      </div>
                                                  </div>
                                              </div>
                                          <?php endforeach; ?>
                                       </div>
                                   </ul>
                               </div><!-- /.price-box -->
                           </div><!-- /.col-md-3 -->
                          <?php $i++; endforeach; ?>
                    </div><!-- /.row -->
                </div><!-- /.pricing -->
            </div><!-- /.container -->
            <div class="background"></div><!-- /.background -->
        </div><!-- /.block -->
    </section><!-- /#tickets -->
</div>
<!-- end Page Content -->