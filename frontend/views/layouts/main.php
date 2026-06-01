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
use yii\helpers\Url;

AppAsset::register($this);
$base = Yii::$app->request->baseUrl;
$lang = Yii::$app->language;
$category = CourseCategory::findAll(['status' => 1]);
$params = Yii::$app->params;

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
                    <div class="navigation-contact pull-left">Call Us: <span
                                class="opacity-70"><?= $params['phone'] ?></span>
                    </div>
                    <ul class="secondary-navigation list-unstyled pull-right">
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <li><a href="my-account.html#tab-profile"><i class="fa fa-user"></i>My Profile</a></li>
                            <li><a href="my-account.html#tab-my-courses">My Courses</a></li>
                            <li><a href="my-account.html#tab-change-password">Change Password</a></li>
                            <li><a href="index.html">Log Out</a></li>
                        <?php endif; ?>
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
                                <a href="<?= Yii::$app->homeUrl ?>" style="width: 300px;
    display: inherit;">
                                    <img src="<?= "$base/logo.png" ?>" alt="brand" style="object-fit: cover;
    width: 100%;
    height: 80px;
    padding-left: 12px;">
                                </a>
                            </div>
                        </div>
                        <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right mr-5" role="navigation">
                            <ul class="nav navbar-nav">
                                <?php foreach ($category as $item): $courses = Courses::findAll(['category_id' => $item->id, 'status' => 1]) ?>
                                    <li>
                                        <a href="#" class=" has-child no-link"><?= $item->{"name_$lang"} ?></a>
                                        <ul class="list-unstyled child-navigation">
                                            <?php foreach ($courses as $value): ?>
                                                <li>
                                                    <a href="<?= \yii\helpers\Url::to(['courses/index', 'slug' => $value->slug]) ?>"><?= $item->{"name_$lang"} ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php endforeach; ?>
                                <li>
                                    <a href="#" class="has-child no-link">ABOUT US</a>
                                    <ul class="list-unstyled child-navigation">
                                        <li>
                                            <a href="<?=Url::to(['site/about'])?>">About Meros</a>
                                        </li>
                                        <li>
                                            <a href="<?=Url::to(['site/contact']);?>">Contact Us</a>
                                        </li>
                                        <li>
                                            <a href="<?=Url::to(['site/team'])?>">Meet the Team</a>
                                        </li>
                                        <li>
                                            <a href="<?=Url::to(['site/clients'])?>">Our Clients</a>
                                        </li>
                                        <li>
                                            <a href="<?=Url::to(['site/partners'])?>">Our Partners</a>
                                        </li>
                                        <li>
                                            <a href="<?=Url::to(['site/policy'])?>">Environmental Policy</a>
                                        </li>
                                        <li>
                                            <a href="<?=Url::to(['site/faq-students'])?>">FAQ - Students</a>
                                        </li>
                                        <li>
                                            <a href="<?=Url::to(['site/faq-org'])?>">FAQs - Organisations</a>
                                        </li>
                                    </ul>
                                </li>
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


        <?= Alert::widget() ?>
        <?= $content ?>

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
                                <img src="<?= "$base/img/logo-white.png" ?>" class="vertical-center">
                            </aside>
                        </div><!-- /.col-md-3 -->
                        <div class="col-md-3 col-sm-4">
                            <aside>
                                <header><h4>Contact Us</h4></header>
                                <address>
                                    <strong><?=Yii::$app->name?></strong>
                                    <br>
                                    <span>Uzbekistan Samarkand</span>
                                    <br>
                                    <span>Beruniy street, 1/5</span>
                                    <br>
                                    <abbr title="Telephone">Phone:</abbr> <?=$params['phone']?>
                                    <br>
                                    <abbr title="Email">Email:</abbr> <a href="mailto:<?=$params['adminEmail']?>"><?=$params['adminEmail']?></a>
                                </address>
                            </aside>
                        </div><!-- /.col-md-3 -->
                        <div class="col-md-3 col-sm-4">
                            <aside>
                                <header><h4>Important Links</h4></header>
                                <ul class="list-links">
                                    <li><a href="#">About Meros</a></li>
                                    <li><a href="#">FAQ - Students</a></li>
                                    <li><a href="#">FAQ - Teachers</a></li>
                                    <li><a href="#">Policy Privacy</a></li>
                                    <li><a href="#">Libary & Health</a></li>
                                    <li><a href="#">Research</a></li>
                                </ul>
                            </aside>
                        </div><!-- /.col-md-3 -->
                        <div class="col-md-3 col-sm-4">
                            <aside>
                                <header><h4>About Meros</h4></header>
                                <?=Yii::$app->params['about_short'][$lang]?>
                            </aside>
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->
                </div><!-- /.container -->
                <div class="background"><img src="<?= "$base/" ?>img/background-city.png" class="" alt=""></div>
            </section><!-- /#footer-content -->

            <section id="footer-bottom">
                <div class="container">
                    <div class="footer-inner">
                        <div class="copyright">© Meros inc, All rights reserved</div><!-- /.copyright -->
                    </div><!-- /.footer-inner -->
                </div><!-- /.container -->
            </section><!-- /#footer-bottom -->

        </footer>
        <!-- end Footer -->
    </div>
    <script src="<?="$base/js/jquery-2.1.0.min.js"?>"></script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
