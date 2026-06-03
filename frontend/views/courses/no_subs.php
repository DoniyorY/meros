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
            <div class="background background-color-grey-background"></div><!-- /.background -->
        </div>
    </section><!-- /#course-detail -->

    <section id="instructors">
        <div class="block">
            <div class="container">
                <div class="instructors">
                    <div class="author-carousel">
                        <div class="author">
                            <blockquote>
                                <figure class="author-picture"><img src="<?= "$base/" ?>img/student-testimonial.jpg"
                                                                    alt=""></figure>
                                <article class="paragraph-wrapper">
                                    <div class="inner">
                                        <header>Claire Doe</header>
                                        <p>
                                            Morbi nec nisi ante. Quisque lacus ligula, iaculis in elit et, interdum
                                            semper quam. Fusce in interdum tortor.
                                            Ut sollicitudin lectus dolor eget imperdiet libero pulvinar sit amet. Lorem
                                            ipsum dolor sit amet, consectetur adipiscing elit.
                                            Suspendisse et urna fringilla, volutpat elit non, tristique lectus..
                                        </p>
                                        <figure>Marketing Specialist</figure>
                                    </div>
                                </article>
                            </blockquote>
                        </div><!-- /.author -->
                        <div class="author">
                            <blockquote>
                                <figure class="author-picture"><img src="<?= "$base/" ?>img/student-testimonial.jpg"
                                                                    alt=""></figure>
                                <article class="paragraph-wrapper">
                                    <div class="inner">
                                        <header>Claire Doe</header>
                                        <p>
                                            Morbi nec nisi ante. Quisque lacus ligula, iaculis in elit et, interdum
                                            semper quam. Fusce in interdum tortor.
                                            Ut sollicitudin lectus dolor eget imperdiet libero pulvinar sit amet. Lorem
                                            ipsum dolor sit amet, consectetur adipiscing elit.
                                            Suspendisse et urna fringilla, volutpat elit non, tristique lectus..
                                        </p>
                                        <figure>Marketing Specialist</figure>
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
                        <?php $i=1; foreach ($subs as $item): $features = SubscriptionPlanItems::findAll(['plan_id'=>$item->id]); ?>
                        <div class="col-md-4 col-sm-12">
                            <div class="price-box <?=($i == 2)?"recommended":''?>" style="padding: 20px;">
                                <header><h3><?=$item->{"name_$lang"}?></h3></header>
                                <div class="price"><?=Yii::$app->formatter->asDecimal($item->price,0)?> uzs</div>
                                <figure><?php
                                   if($item->duration_days == 30){
                                       echo "1 Month";
                                   }elseif($item->duration_days == 90){
                                       echo "3 Months";
                                   }
                                ?></figure>
                                <a href="#" class="btn btn-large">Buy Now</a>
                                <ul class="features">
                                    <div class="panel-group" id="accordion-<?=$item->id?>">
                                        <?php foreach ($features as $v): $k = $v->id?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" style="background: #07707a ">
                                                <h4 class="panel-title" style="color: white; font-weight: 600">
                                                    <a data-toggle="collapse" data-parent="#accordion-<?=$item->id?>"
                                                       href="<?="#feature-$k"?>" class="collapsed">
                                                        <span><?=$v->{"name_$lang"}?></span>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="<?="feature-$k"?>" class="panel-collapse collapse" style="height: 0px;">
                                                <div class="panel-body" style="text-align: left">
                                                    <?=$v->{"desc_$lang"}?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php  endforeach;?>
                                    </div>
                                </ul>
                            </div><!-- /.price-box -->
                        </div><!-- /.col-md-3 -->
                        <?php $i++; endforeach;?>
                    </div><!-- /.row -->
                </div><!-- /.pricing -->
            </div><!-- /.container -->
            <div class="background"></div><!-- /.background -->
        </div><!-- /.block -->
    </section><!-- /#tickets -->
</div>
<!-- end Page Content -->