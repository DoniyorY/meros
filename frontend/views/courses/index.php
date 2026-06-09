<?php
$this->registerMetaTag(['name' => 'description', 'content' => "Meros English Courses"]);
$this->registerMetaTag(['name' => 'keywords', 'content' => "Meros English Courses"]);
$this->title = 'Meros English Courses';
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
?>

<!-- Page Content -->
<div id="page-content">
    <div class="container-fluid course-listing-container">
        <div class="row g-4">
            <!--MAIN Content-->
            <div class="col-lg-9 col-md-12">
                <div id="page-main">
                    <section class="course-listing" id="courses">
                        <header><h1>Courses Lessons</h1></header>
                        <div class="row g-4">
                            <div class="col-md-6 col-12">
                                <article class="course-thumbnail">
                                    <figure class="image">
                                        <div class="image-wrapper"><a href="course-detail-v1.html"><img
                                                        src="<?= "$base/" ?>img/course-01.jpg"></a></div>
                                    </figure>
                                    <div class="description">
                                        <a href="course-detail-v1.html"><h3>Character Drawing: Basic and Advanced
                                                Principles</h3></a>
                                        <a href="#" class="course-category">Art and Design</a>
                                        <hr>
                                        <div class="course-meta">
                                                <span class="course-date"><i
                                                            class="fa fa-calendar-o"></i>01-03-2014</span>
                                            <span class="course-length"><i class="fa fa-clock-o"></i>3 months</span>
                                        </div>
                                        <div class="stick-to-bottom"><a href="course-detail-v1.html"
                                                                        class="btn btn-framed btn-color-grey btn-small">View
                                                Details</a></div>
                                    </div>
                                </article><!-- /.featured-course -->
                            </div><!-- /.col-md-3 -->
                            <div class="col-md-6 col-12">
                                <article class="course-thumbnail">
                                    <figure class="image">
                                        <div class="image-wrapper"><a href="course-detail-v1.html"><img
                                                        src="<?= "$base/" ?>img/course-02.jpg"></a></div>
                                    </figure>
                                    <div class="description">
                                        <a href="course-detail-v1.html"><h3>Architecture Photography: Power of Light
                                                and Shadows</h3></a>
                                        <a href="#" class="course-category">Photography</a>
                                        <hr>
                                        <div class="course-meta">
                                                <span class="course-date"><i
                                                            class="fa fa-calendar-o"></i>01-03-2014</span>
                                            <span class="course-length"><i class="fa fa-clock-o"></i>3 months</span>
                                        </div>
                                        <div class="stick-to-bottom"><a href="course-detail-v1.html"
                                                                        class="btn btn-framed btn-color-grey btn-small">View
                                                Details</a></div>
                                    </div>
                                </article><!-- /.featured-course -->
                            </div><!-- /.col-md-3 -->
                            <div class="col-md-6 col-12">
                                <article class="course-thumbnail">
                                    <figure class="image">
                                        <div class="image-wrapper"><a href="course-detail-v1.html"><img
                                                        src="<?= "$base/" ?>img/course-03.jpg"></a></div>
                                    </figure>
                                    <div class="description">
                                        <a href="course-detail-v1.html"><h3>How to find long term customers</h3></a>
                                        <a href="#" class="course-category">Marketing</a>
                                        <hr>
                                        <div class="course-meta">
                                                <span class="course-date"><i
                                                            class="fa fa-calendar-o"></i>01-03-2014</span>
                                            <span class="course-length"><i class="fa fa-clock-o"></i>3 months</span>
                                        </div>
                                        <div class="stick-to-bottom"><a href="course-detail-v1.html"
                                                                        class="btn btn-framed btn-color-grey btn-small">View
                                                Details</a></div>
                                    </div>
                                </article><!-- /.featured-course -->
                            </div><!-- /.col-md-3 -->
                        </div><!-- /.row -->
                    </section><!-- /.course-listing -->
                    <div class="center">
                        <ul class="pagination justify-content-center flex-wrap">
                            <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                        </ul>
                    </div>
                </div><!-- /#page-main -->
            </div><!-- /.col-md-8 -->

            <!--SIDEBAR Content-->
            <div class="col-lg-3 col-md-12">
                <div id="page-sidebar" class="sidebar">
                    <aside class="news-small" id="news-small">
                        <header>
                            <h2>Related News</h2>
                        </header>
                        <div class="section-content">
                            <article>
                                <figure class="date"><i class="fa fa-file-o"></i>08-24-2014</figure>
                                <header><a href="#">U-M School of Public Health, Detroit partners aim to improve air
                                        quality in the city</a></header>
                            </article><!-- /article -->
                            <article>
                                <figure class="date"><i class="fa fa-file-o"></i>08-24-2014</figure>
                                <header><a href="#">At 50, Center for the Education of Women celebrates a wider
                                        mission</a></header>
                            </article><!-- /article -->
                            <article>
                                <figure class="date"><i class="fa fa-file-o"></i>08-24-2014</figure>
                                <header><a href="#">Three U-Michigan scientists receive Sloan fellowships</a>
                                </header>
                            </article><!-- /article -->
                        </div><!-- /.section-content -->
                        <a href="" class="read-more">All News</a>
                    </aside><!-- /.news-small -->
                    <aside id="archive">
                        <header>
                            <h2>Course Archive</h2>
                            <ul class="list-links">
                                <li><a href="#">February 2014</a></li>
                                <li><a href="#">January 2014</a></li>
                                <li><a href="#">November 2013</a></li>
                                <li><a href="#">October 2013</a></li>
                                <li><a href="#">August 2013</a></li>
                                <li><a href="#">July 2013</a></li>
                                <li><a href="#">June 2013</a></li>
                                <li><a href="#">May 2013</a></li>
                            </ul>
                        </header>
                    </aside><!-- /archive -->
                </div><!-- /#sidebar -->
            </div><!-- /.col-md-4 -->
            <!-- end SIDEBAR Content-->
        </div><!-- /.row -->
    </div><!-- /.container -->
</div>
<!-- end Page Content -->