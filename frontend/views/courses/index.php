<?php
$params = Yii::$app->params;
$lang = Yii::$app->language;
$t = static function ($key) use ($params, $lang) {
    return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};
$this->registerMetaTag(['name' => 'description', 'content' => $t('meros_english_courses')]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $t('meros_english_courses')]);
$this->title = $t('meros_english_courses');
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
                        <header><h1><?= $t('course_lessons') ?></h1></header>
                        <div class="row g-4">
                            <div class="col-md-6 col-12">
                                <article class="course-thumbnail">
                                    <figure class="image">
                                        <div class="image-wrapper"><a href="course-detail-v1.html"><img
                                                        src="<?= "$base/" ?>img/course-01.jpg"></a></div>
                                    </figure>
                                    <div class="description">
                                        <a href="course-detail-v1.html"><h3><?= $t('demo_course_character_drawing') ?></h3></a>
                                        <a href="#" class="course-category"><?= $t('art_and_design') ?></a>
                                        <hr>
                                        <div class="course-meta">
                                                <span class="course-date"><i
                                                            class="fa fa-calendar-o"></i>01-03-2014</span>
                                            <span class="course-length"><i class="fa fa-clock-o"></i><?= $t('three_months') ?></span>
                                        </div>
                                        <div class="stick-to-bottom"><a href="course-detail-v1.html"
                                                                        class="btn btn-framed btn-color-grey btn-small"><?= $t('view_details') ?></a></div>
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
                                        <a href="course-detail-v1.html"><h3><?= $t('demo_course_architecture_photography') ?></h3></a>
                                        <a href="#" class="course-category"><?= $t('photography') ?></a>
                                        <hr>
                                        <div class="course-meta">
                                                <span class="course-date"><i
                                                            class="fa fa-calendar-o"></i>01-03-2014</span>
                                            <span class="course-length"><i class="fa fa-clock-o"></i><?= $t('three_months') ?></span>
                                        </div>
                                        <div class="stick-to-bottom"><a href="course-detail-v1.html"
                                                                        class="btn btn-framed btn-color-grey btn-small"><?= $t('view_details') ?></a></div>
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
                                        <a href="course-detail-v1.html"><h3><?= $t('demo_course_marketing') ?></h3></a>
                                        <a href="#" class="course-category"><?= $t('marketing') ?></a>
                                        <hr>
                                        <div class="course-meta">
                                                <span class="course-date"><i
                                                            class="fa fa-calendar-o"></i>01-03-2014</span>
                                            <span class="course-length"><i class="fa fa-clock-o"></i><?= $t('three_months') ?></span>
                                        </div>
                                        <div class="stick-to-bottom"><a href="course-detail-v1.html"
                                                                        class="btn btn-framed btn-color-grey btn-small"><?= $t('view_details') ?></a></div>
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
                            <h2><?= $t('related_news') ?></h2>
                        </header>
                        <div class="section-content">
                            <article>
                                <figure class="date"><i class="fa fa-file-o"></i>08-24-2014</figure>
                                <header><a href="#"><?= $t('demo_news_public_health') ?></a></header>
                            </article><!-- /article -->
                            <article>
                                <figure class="date"><i class="fa fa-file-o"></i>08-24-2014</figure>
                                <header><a href="#"><?= $t('demo_news_education_women') ?></a></header>
                            </article><!-- /article -->
                            <article>
                                <figure class="date"><i class="fa fa-file-o"></i>08-24-2014</figure>
                                <header><a href="#"><?= $t('demo_news_scientists') ?></a>
                                </header>
                            </article><!-- /article -->
                        </div><!-- /.section-content -->
                        <a href="" class="read-more"><?= $t('all_news') ?></a>
                    </aside><!-- /.news-small -->
                    <aside id="archive">
                        <header>
                            <h2><?= $t('course_archive') ?></h2>
                            <ul class="list-links">
                                <li><a href="#"><?= $t('month_february') ?> 2014</a></li>
                                <li><a href="#"><?= $t('month_january') ?> 2014</a></li>
                                <li><a href="#"><?= $t('month_november') ?> 2013</a></li>
                                <li><a href="#"><?= $t('month_october') ?> 2013</a></li>
                                <li><a href="#"><?= $t('month_august') ?> 2013</a></li>
                                <li><a href="#"><?= $t('month_july') ?> 2013</a></li>
                                <li><a href="#"><?= $t('month_june') ?> 2013</a></li>
                                <li><a href="#"><?= $t('month_may') ?> 2013</a></li>
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