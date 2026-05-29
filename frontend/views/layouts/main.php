<?php

/** @var \yii\web\View $this */

/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use common\models\CourseCategory;
use common\models\Courses;

AppAsset::register($this);
$base = Yii::$app->request->baseUrl;
$lang = Yii::$app->language;
$category = CourseCategory::findAll(['status' => 1]);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="page-homepage-carousel">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <!-- Header -->
        <div class="navigation-wrapper">
            <div class="secondary-navigation-wrapper">
                <div class="container">
                    <div class="navigation-contact pull-left">Call Us: <span class="opacity-70">000-123-456-789</span>
                    </div>
                    <ul class="secondary-navigation list-unstyled pull-right">
                        <li><a href="my-account.html#tab-profile"><i class="fa fa-user"></i>My Profile</a></li>
                        <li><a href="my-account.html#tab-my-courses">My Courses</a></li>
                        <li><a href="my-account.html#tab-change-password">Change Password</a></li>
                        <li><a href="index.html">Log Out</a></li>
                    </ul>
                </div>
            </div><!-- /.secondary-navigation -->
            <div class="primary-navigation-wrapper">
                <header class="navbar" id="top" role="banner">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button class="navbar-toggle" type="button" data-toggle="collapse"
                                    data-target=".bs-navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <div class="navbar-brand nav" id="brand">
                                <a href="<?= Yii::$app->homeUrl ?>">
                                    <img src="<?= "$base/" ?>img/logo.png" alt="brand">
                                </a>
                            </div>
                        </div>
                        <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right mr-5" role="navigation">
                            <ul class="nav navbar-nav">
                                <?php foreach ($category as $item): $courses = Courses::findAll(['category_id' => $item->id, 'status' => 1]) ?>
                                    <li>
                                        <a href="#" class=" has-child no-link"><?= $item->{"name_$lang"} ?></a>
                                        <ul class="list-unstyled child-navigation">
                                            <?php foreach ($courses as $value):?>
                                            <li><a href="<?=\yii\helpers\Url::to(['course/view','alias'=>$value->slug])?>"><?=$item->{"name_$lang"}?></a></li>
                                            <?php endforeach;?>
                                        </ul>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </nav><!-- /.navbar collapse-->
                    </div><!-- /.container -->
                </header><!-- /.navbar -->
            </div><!-- /.primary-navigation -->
            <div class="background">
                <img src="<?= "$base/" ?>img/background-city.png" alt="background">
            </div>
        </div>
        <!-- end Header -->
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
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>

        <!-- Footer -->
        <footer id="page-footer">
            <section id="footer-top">
                <div class="container">
                    <div class="footer-inner">
                        <div class="footer-social">
                            <figure>Follow us:</figure>
                            <div class="icons">
                                <a href=""><i class="fa fa-twitter"></i></a>
                                <a href=""><i class="fa fa-facebook"></i></a>
                                <a href=""><i class="fa fa-pinterest"></i></a>
                                <a href=""><i class="fa fa-youtube-play"></i></a>
                            </div><!-- /.icons -->
                        </div><!-- /.social -->
                        <div class="search pull-right">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search">
                                <span class="input-group-btn">
                        <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                    </span>
                            </div><!-- /input-group -->
                        </div><!-- /.pull-right -->
                    </div><!-- /.footer-inner -->
                </div><!-- /.container -->
            </section><!-- /#footer-top -->

            <section id="footer-content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <aside class="logo">
                                <img src="<?= "$base/" ?>img/logo-white.png" class="vertical-center">
                            </aside>
                        </div><!-- /.col-md-3 -->
                        <div class="col-md-3 col-sm-4">
                            <aside>
                                <header><h4>Contact Us</h4></header>
                                <address>
                                    <strong>University of Universo</strong>
                                    <br>
                                    <span>4877 Spruce Drive</span>
                                    <br><br>
                                    <span>West Newton, PA 15089</span>
                                    <br>
                                    <abbr title="Telephone">Telephone:</abbr> +1 (734) 123-4567
                                    <br>
                                    <abbr title="Email">Email:</abbr> <a href="#">questions@youruniversity.com</a>
                                </address>
                            </aside>
                        </div><!-- /.col-md-3 -->
                        <div class="col-md-3 col-sm-4">
                            <aside>
                                <header><h4>Important Links</h4></header>
                                <ul class="list-links">
                                    <li><a href="#">Future Students</a></li>
                                    <li><a href="#">Alumni</a></li>
                                    <li><a href="#">Give a Donation</a></li>
                                    <li><a href="#">Professors</a></li>
                                    <li><a href="#">Libary & Health</a></li>
                                    <li><a href="#">Research</a></li>
                                </ul>
                            </aside>
                        </div><!-- /.col-md-3 -->
                        <div class="col-md-3 col-sm-4">
                            <aside>
                                <header><h4>About Universo</h4></header>
                                <p>Aliquam feugiat turpis quis felis adipiscing, non pulvinar odio lacinia.
                                    Aliquam elementum pharetra fringilla. Duis blandit, sapien in semper vehicula,
                                    tellus elit gravida odio, ac tincidunt nisl mi at ante. Vivamus tincidunt nunc nibh.
                                </p>
                                <div>
                                    <a href="" class="read-more">All News</a>
                                </div>
                            </aside>
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->
                </div><!-- /.container -->
                <div class="background"><img src="<?= "$base/" ?>img/background-city.png" class="" alt=""></div>
            </section><!-- /#footer-content -->

            <section id="footer-bottom">
                <div class="container">
                    <div class="footer-inner">
                        <div class="copyright">© Theme Starz, All rights reserved</div><!-- /.copyright -->
                    </div><!-- /.footer-inner -->
                </div><!-- /.container -->
            </section><!-- /#footer-bottom -->

        </footer>
        <!-- end Footer -->
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
