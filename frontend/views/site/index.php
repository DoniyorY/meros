<?php

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
$base = Yii::$app->request->baseUrl;
$params = Yii::$app->params;
?>
<!-- Homepage Slider -->
<section id="homepage-slider">
    <div class="flexslider">
        <ul class="slides">
            <li class="slide">
                <figure>
                    <div class="slide-wrapper">
                        <div class="inner">
                            <div class="container">
                                <h2>Business Course</h2>
                                <h1>Be a marketing guru</h1>
                                <div><a href="course-detail-v1.html" class="btn">View Course Details</a></div>
                            </div>
                        </div><!-- /.inner -->
                    </div><!-- /.wrapper -->
                </figure>
                <img src="<?= "$base/" ?>img/landing-page-background.jpg">
            </li>
            <li class="slide">
                <figure>
                    <div class="slide-wrapper">
                        <div class="inner">
                            <div class="container">
                                <h2>Art and design</h2>
                                <h1>Drawing for Everyone</h1>
                                <div><a href="course-detail-v1.html" class="btn">View Course Details</a></div>
                            </div>
                        </div><!-- /.inner -->
                    </div><!-- /.wrapper -->
                </figure>
                <img src="<?= "$base/" ?>img/slider-slide-02.jpg">
            </li>
        </ul>
    </div>
</section>
<!-- end Homepage Slider -->
<div id="page-content">
    <section id="right-sidebar">
        <div class="block">
            <div class="container">
                <div class="text-center">
                    <h2>About Meros</h2>
                </div>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris et lacus sit amet libero blandit
                    ullamcorper. Aliquam iaculis purus interdum, bibendum eros vitae, semper urna. Vivamus placerat ac
                    ante nec adipiscing. Nam vel luctus libero. Ut scelerisque dui eu nisl aliquam, ornare imperdiet
                    augue tincidunt. Vivamus blandit sed dolor tristique consequat. Maecenas vel aliquet ligula. Nunc
                    viverra nisl vel vulputate lobortis. Suspendisse id lobortis diam. Cras ornare, sem non cursus
                    iaculis, felis leo egestas ante, ac rutrum lorem mauris at leo. Curabitur risus turpis, egestas at
                    euismod vitae, vulputate et eros. Sed erat orci, facilisis id risus et, dictum sollicitudin eros.
                    Donec vestibulum tempus molestie. Curabitur purus felis, molestie a quam ut, dignissim cursus massa.
                </p>
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
                            <figure class="author-picture"><img src="<?= "$base/" ?>img/student-testimonial.jpg" alt="">
                            </figure>
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
                    <div class="author has-dark-background">
                        <blockquote>
                            <figure class="author-picture"><img src="<?= "$base/" ?>img/student-testimonial.jpg" alt="">
                            </figure>
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
                <header><h2>Course List</h2></header>
                <div class="table-responsive">
                    <table class="table table-hover course-list-table tablesorter">
                        <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Course Type</th>
                            <th class="starts">Starts</th>
                            <th class="length">Length</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th class="course-title"><a href="course-detail-v1.html">Introduction to modo 701</a></th>
                            <th class="course-category"><a href="#">Graphic Design and 3D</a></th>
                            <th>01-03-2014</th>
                            <th>3 months</th>
                        </tr>
                        <tr>
                            <th class="course-title"><a href="course-detail-v1.html">Become self marketer</a></th>
                            <th class="course-category"><a href="#">Marketing</a></th>
                            <th>03-03-2014</th>
                            <th>2 lessons</th>
                        </tr>
                        <tr>
                            <th class="course-title"><a href="course-detail-v1.html">How to find long term customers</a>
                            </th>
                            <th class="course-category"><a href="#">Marketing</a></th>
                            <th>06-03-2014</th>
                            <th>1 month</th>
                        </tr>
                        <tr>
                            <th class="course-title"><a href="course-detail-v1.html">Neuroscience and the future</a>
                            </th>
                            <th class="course-category"><a href="#">Science</a></th>
                            <th>21-03-2014</th>
                            <th>3 weeks</th>
                        </tr>
                        <tr>
                            <th class="course-title"><a href="course-detail-v1.html">History in complex view</a></th>
                            <th class="course-category"><a href="#">History and Psychology</a></th>
                            <th>06-04-2014</th>
                            <th>1 lesson</th>
                        </tr>
                        <tr>
                            <th class="course-title"><a href="course-detail-v1.html">Become self marketer</a></th>
                            <th class="course-category"><a href="#">Marketing</a></th>
                            <th>03-03-2014</th>
                            <th>2 lessons</th>
                        </tr>
                        <tr>
                            <th class="course-title"><a href="course-detail-v1.html">How to find long term customers</a>
                            </th>
                            <th class="course-category"><a href="#">Marketing</a></th>
                            <th>06-03-2014</th>
                            <th>1 month</th>
                        </tr>
                        <tr>
                            <th class="course-title"><a href="course-detail-v1.html">Neuroscience and the future</a>
                            </th>
                            <th class="course-category"><a href="#">Science</a></th>
                            <th>21-03-2014</th>
                            <th>3 weeks</th>
                        </tr>
                        <tr>
                            <th class="course-title"><a href="course-detail-v1.html">History in complex view</a></th>
                            <th class="course-category"><a href="#">History and Psychology</a></th>
                            <th>06-04-2014</th>
                            <th>1 lesson</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <a href="course-listing.html" class="btn btn-framed btn-color-grey pull-right">All Courses</a>
            </div>
        </div>
    </section><!-- /.course-list -->

    <!-- Partners, Become a Partner -->
    <div class="block">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <section id="partners">
                        <header>
                            <h2>Partners & Donors</h2>
                        </header>
                        <div class="section-content">
                            <div class="logos">
                                <div class="logo"><a href=""><img src="<?= "$base/" ?>img/logo-partner-01.png"
                                                                  alt=""></a></div>
                                <div class="logo"><a href=""><img src="<?= "$base/" ?>img/logo-partner-02.png"
                                                                  alt=""></a></div>
                                <div class="logo"><a href=""><img src="<?= "$base/" ?>img/logo-partner-03.png"
                                                                  alt=""></a></div>
                                <div class="logo"><a href=""><img src="<?= "$base/" ?>img/logo-partner-04.png"
                                                                  alt=""></a></div>
                                <div class="logo"><a href=""><img src="<?= "$base/" ?>img/logo-partner-05.png"
                                                                  alt=""></a></div>
                            </div>
                        </div>
                    </section>
                </div><!-- /.col-md-9 -->
                <div class="col-md-3">
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


