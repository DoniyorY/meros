<?php

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
$base = Yii::$app->request->baseUrl;
$params = Yii::$app->params;
?>

<section id="featured-courses">
    <div class="block">
        <div class="container">
            <header><h2>Featured Courses</h2></header>
            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <article class="featured-course">
                        <figure class="image">
                            <div class="image-wrapper"><a href="course-detail-v1.html"><img src="<?="$base/"?>img/course-01.jpg"></a></div>
                        </figure>
                        <div class="description">
                            <a href="course-detail-v1.html"><h3>Character Drawing: Basic and Advanced Principles</h3></a>
                            <a href="#" class="course-category">Art and Design</a>
                            <hr>
                            <div class="course-meta">
                                <span class="course-date"><i class="fa fa-calendar-o"></i>01-03-2014</span>
                                <span class="course-length"><i class="fa fa-clock-o"></i>3 months</span>
                            </div>
                            <div class="stick-to-bottom"><a href="course-detail-v1.html" class="btn btn-framed btn-color-grey btn-small">View Details</a></div>
                        </div>
                    </article><!-- /.featured-course -->
                </div><!-- /.col-md-3 -->
                <div class="col-md-3 col-sm-3">
                    <article class="featured-course">
                        <figure class="image">
                            <div class="image-wrapper"><a href="course-detail-v1.html"><img src="<?="$base/"?>img/course-02.jpg"></a></div>
                        </figure>
                        <div class="description">
                            <a href="course-detail-v1.html"><h3>Architecture Photography: Power of Light and Shadows</h3></a>
                            <a href="#" class="course-category">Photography</a>
                            <hr>
                            <div class="course-meta">
                                <span class="course-date"><i class="fa fa-calendar-o"></i>01-03-2014</span>
                                <span class="course-length"><i class="fa fa-clock-o"></i>3 months</span>
                            </div>
                            <div class="stick-to-bottom"><a href="course-detail-v1.html" class="btn btn-framed btn-color-grey btn-small">View Details</a></div>
                        </div>
                    </article><!-- /.featured-course -->
                </div><!-- /.col-md-3 -->
                <div class="col-md-3 col-sm-3">
                    <article class="featured-course">
                        <figure class="image">
                            <div class="image-wrapper"><a href="course-detail-v1.html"><img src="<?="$base/"?>img/course-03.jpg"></a></div>
                        </figure>
                        <div class="description">
                            <a href="course-detail-v1.html"><h3>How to find long term customers</h3></a>
                            <a href="#" class="course-category">Marketing</a>
                            <hr>
                            <div class="course-meta">
                                <span class="course-date"><i class="fa fa-calendar-o"></i>01-03-2014</span>
                                <span class="course-length"><i class="fa fa-clock-o"></i>3 months</span>
                            </div>
                            <div class="stick-to-bottom"><a href="course-detail-v1.html" class="btn btn-framed btn-color-grey btn-small">View Details</a></div>
                        </div>
                    </article><!-- /.featured-course -->
                </div><!-- /.col-md-3 -->
                <div class="col-md-3 col-sm-3">
                    <article class="featured-course clearfix">
                        <figure class="image">
                            <div class="image-wrapper"><a href="course-detail-v1.html"><img src="<?="$base/"?>img/course-04.jpg"></a></div>
                        </figure>
                        <div class="description">
                            <a href="course-detail-v1.html"><h3>Neuroscience for the Beginners: Complete Course</h3></a>
                            <a href="#" class="course-category">Science</a>
                            <hr>
                            <div class="course-meta">
                                <span class="course-date"><i class="fa fa-calendar-o"></i>01-03-2014</span>
                                <span class="course-length"><i class="fa fa-clock-o"></i>3 months</span>
                            </div>
                            <div class="stick-to-bottom"><a href="course-detail-v1.html" class="btn btn-framed btn-color-grey btn-small">View Details</a></div>
                        </div>
                    </article><!-- /.featured-course -->
                </div><!-- /.col-md-3 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
        <div class="background background-color-grey-background"></div>
    </div><!-- /.block -->
</section>
<!-- /#featured-courses -->

<section id="latest-courses">
    <div class="block">
        <div class="container">
            <header><h2>Latest Courses</h2></header>
            <div class="row">
                <div class="col-md-4">
                    <div class="latest-course">
                        <figure class="image">
                            <div class="image-wrapper"><a href="course-detail-v1.html"><img src="<?="$base/"?>img/course-01.jpg"></a></div>
                        </figure>
                        <aside class="description">
                            <a href="course-detail-v1.html"><h4>Neuroscience for the Beginners: Complete Course</h4></a>
                            <!--<a href="#" class="course-category">Science</a>-->
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et urna fringilla</p>
                            <div class="course-meta">
                                <span class="course-date"><i class="fa fa-calendar-o"></i>01-03-2014</span>
                                <span class="course-length"><i class="fa fa-clock-o"></i>3 months</span>
                            </div>
                        </aside>
                        <hr>
                    </div><!-- /.latest-course -->
                </div><!-- /.col-md-4 -->
                <div class="col-md-4">
                    <div class="latest-course">
                        <figure class="image">
                            <div class="image-wrapper"><a href="course-detail-v1.html"><img src="<?="$base/"?>img/course-02.jpg"></a></div>
                        </figure>
                        <aside class="description">
                            <a href="course-detail-v1.html"><h4>Architecture Photography: Power of Light and Shadows</h4></a>
                            <!--<a href="#" class="course-category">Science</a>-->
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et urna fringilla</p>
                            <div class="course-meta">
                                <span class="course-date"><i class="fa fa-calendar-o"></i>01-03-2014</span>
                                <span class="course-length"><i class="fa fa-clock-o"></i>8 lessons</span>
                            </div>
                        </aside>
                        <hr>
                    </div><!-- /.latest-course -->
                </div><!-- /.col-md-4 -->
                <div class="col-md-4">
                    <div class="latest-course">
                        <figure class="image">
                            <div class="image-wrapper"><a href="course-detail-v1.html"><img src="<?="$base/"?>img/course-03.jpg"></a></div>
                        </figure>
                        <aside class="description">
                            <a href="course-detail-v1.html"><h4>Character Drawing: Basic and Advanced Principles</h4></a>
                            <!--<a href="#" class="course-category">Science</a>-->
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et urna fringilla</p>
                            <div class="course-meta">
                                <span class="course-date"><i class="fa fa-calendar-o"></i>01-03-2014</span>
                                <span class="course-length"><i class="fa fa-clock-o"></i>8 lessons</span>
                            </div>
                        </aside>
                        <hr>
                    </div><!-- /.latest-course -->
                </div><!-- /.col-md-4 -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col-md-4">
                    <div class="latest-course">
                        <figure class="image">
                            <div class="image-wrapper"><a href="course-detail-v1.html"><img src="<?="$base/"?>img/course-04.jpg"></a></div>
                        </figure>
                        <aside class="description">
                            <a href="course-detail-v1.html"><h4>Introduction to modo 701</h4></a>
                            <!--<a href="#" class="course-category">Science</a>-->
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et urna fringilla</p>
                            <div class="course-meta">
                                <span class="course-date"><i class="fa fa-calendar-o"></i>01-03-2014</span>
                                <span class="course-length"><i class="fa fa-clock-o"></i>3 months</span>
                            </div>
                        </aside>
                        <hr>
                    </div><!-- /.latest-course -->
                </div><!-- /.col-md-4 -->
                <div class="col-md-4">
                    <div class="latest-course">
                        <figure class="image">
                            <div class="image-wrapper"><a href="course-detail-v1.html"><img src="<?="$base/"?>img/course-01.jpg"></a></div>
                        </figure>
                        <aside class="description">
                            <a href="course-detail-v1.html"><h4>Become self marketer</h4></a>
                            <!--<a href="#" class="course-category">Science</a>-->
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et urna fringilla</p>
                            <div class="course-meta">
                                <span class="course-date"><i class="fa fa-calendar-o"></i>01-03-2014</span>
                                <span class="course-length"><i class="fa fa-clock-o"></i>8 lessons</span>
                            </div>
                        </aside>
                        <hr>
                    </div><!-- /.latest-course -->
                </div><!-- /.col-md-4 -->
                <div class="col-md-4">
                    <div class="latest-course">
                        <figure class="image">
                            <div class="image-wrapper"><a href="course-detail-v1.html"><img src="<?="$base/"?>img/course-02.jpg"></a></div>
                        </figure>
                        <aside class="description">
                            <a href="course-detail-v1.html"><h4>History in complex view</h4></a>
                            <!--<a href="#" class="course-category">Science</a>-->
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et urna fringilla</p>
                            <div class="course-meta">
                                <span class="course-date"><i class="fa fa-calendar-o"></i>01-03-2014</span>
                                <span class="course-length"><i class="fa fa-clock-o"></i>8 lessons</span>
                            </div>
                        </aside>
                        <hr>
                    </div><!-- /.latest-course -->
                </div><!-- /.col-md-4 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.block -->
</section>
<!-- /#latest-courses -->

<!-- Testimonial -->
<section id="testimonials">
    <div class="block">
        <div class="container">
            <div class="author-carousel">
                <div class="author has-dark-background">
                    <blockquote>
                        <figure class="author-picture"><img src="<?="$base/"?>img/student-testimonial.jpg" alt=""></figure>
                        <article class="paragraph-wrapper">
                            <div class="inner">
                                <header>Morbi nec nisi ante. Quisque lacus ligula, iaculis in elit et, interdum semper quam. Fusce in interdum tortor.
                                    Ut sollicitudin lectus dolor eget imperdiet libero pulvinar sit amet.</header>
                                <footer>Claire Doe</footer>
                            </div>
                        </article>
                    </blockquote>
                </div><!-- /.author -->
                <div class="author has-dark-background">
                    <blockquote>
                        <figure class="author-picture"><img src="<?="$base/"?>img/student-testimonial.jpg" alt=""></figure>
                        <article class="paragraph-wrapper">
                            <div class="inner">
                                <header>Morbi nec nisi ante. Quisque lacus ligula, iaculis in elit et, interdum semper quam. Fusce in interdum tortor.
                                    Ut sollicitudin lectus dolor eget imperdiet libero pulvinar sit amet.</header>
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
                        <th class="course-title"><a href="course-detail-v1.html">How to find long term customers</a></th>
                        <th class="course-category"><a href="#">Marketing</a></th>
                        <th>06-03-2014</th>
                        <th>1 month</th>
                    </tr>
                    <tr>
                        <th class="course-title"><a href="course-detail-v1.html">Neuroscience and the future</a></th>
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
                        <th class="course-title"><a href="course-detail-v1.html">How to find long term customers</a></th>
                        <th class="course-category"><a href="#">Marketing</a></th>
                        <th>06-03-2014</th>
                        <th>1 month</th>
                    </tr>
                    <tr>
                        <th class="course-title"><a href="course-detail-v1.html">Neuroscience and the future</a></th>
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
                            <div class="logo"><a href=""><img src="<?="$base/"?>img/logo-partner-01.png" alt=""></a></div>
                            <div class="logo"><a href=""><img src="<?="$base/"?>img/logo-partner-02.png" alt=""></a></div>
                            <div class="logo"><a href=""><img src="<?="$base/"?>img/logo-partner-03.png" alt=""></a></div>
                            <div class="logo"><a href=""><img src="<?="$base/"?>img/logo-partner-04.png" alt=""></a></div>
                            <div class="logo"><a href=""><img src="<?="$base/"?>img/logo-partner-05.png" alt=""></a></div>
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

