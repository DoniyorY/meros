<?php
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
                    <div class="col-md-12 col-sm-6">
                        <h1>About the Course</h1>
                        <?=$courses->{"desc_$lang"}?>
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
            <div class="container">
                <div class="pricing">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="price-box">
                                <header><h3>Standard</h3></header>
                                <div class="price">$20</div>
                                <figure>Forever</figure>
                                <a href="#" class="btn btn-large">Buy Now</a>
                                <ul class="features">
                                    <div class="panel-group" id="accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion"
                                                       href="#question-1" class="collapsed">
                                                        <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit?</span>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="question-1" class="panel-collapse collapse" style="height: 0px;">
                                                <div class="panel-body">
                                                    Aliquam sed fermentum nulla. Praesent dictum, velit in condimentum
                                                    volutpat,
                                                    nulla orci vestibulum risus, et facilisis purus urna non metus.
                                                    Donec aliquam
                                                    urna et tempus luctus.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion"
                                                       href="#question-2" class="collapsed">
                                                        <span>Fusce gravida varius justo sed porta?</span>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="question-2" class="panel-collapse collapse" style="height: 0px;">
                                                <div class="panel-body">
                                                    Ut tincidunt dui non velit aliquet, quis porta quam
                                                    vehicula. Vivamus suscipit hendrerit arcu. Nullam lacinia purus at
                                                    porttitor
                                                    varius. Aliquam rutrum feugiat tempor.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </ul>
                            </div><!-- /.price-box -->
                        </div><!-- /.col-md-3 -->
                        <div class="col-md-4 col-sm-12">
                            <div class="price-box recommended">
                                <header><h3>Premium</h3></header>
                                <div class="price">$30</div>
                                <figure>Forever</figure>
                                <a href="#" class="btn btn-large">But Now</a>
                                <ul class="features">
                                    <li><span class="fa fa-check available"></span></li>
                                    <li><span class="fa fa-check available"></span></li>
                                    <li><span class="fa fa-times"></span></li>
                                </ul>
                            </div><!-- /.price-box -->
                        </div><!-- /.col-md-3 -->
                        <div class="col-md-4 col-sm-12">
                            <div class="price-box">
                                <header><h3>Gold</h3></header>
                                <div class="price">$30</div>
                                <figure>Forever</figure>
                                <a href="#" class="btn btn-large">But Now</a>
                                <ul class="features">
                                    <li><span class="fa fa-check available"></span></li>
                                    <li><span class="fa fa-check available"></span></li>
                                    <li><span class="fa fa-check available"></span></li>
                                </ul>
                            </div><!-- /.price-box -->
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->
                </div><!-- /.pricing -->
            </div><!-- /.container -->
            <div class="background"></div><!-- /.background -->
        </div><!-- /.block -->
    </section><!-- /#tickets -->
</div>
<!-- end Page Content -->