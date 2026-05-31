<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \frontend\models\ContactForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contact Us';
$params = Yii::$app->params;
$lang = Yii::$app->language;
function translate($key)
{
    $lang = Yii::$app->language;
    return Yii::$app->params[$key][$lang];
}

?>
<!-- Breadcrumb -->
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?= Yii::$app->homeUrl ?>">Home</a></li>
        <li class="active">Contact Us</li>
    </ol>
</div>
<!-- end Breadcrumb -->

<!-- Page Content -->
<div id="page-content">
    <div class="container">
        <div class="row">
            <!--MAIN Content-->
            <div class="col-md-8">
                <div id="page-main">
                    <section id="contact">
                        <header><h1>Contact Us</h1></header>
                        <div class="row">
                            <div class="col-md-6">
                                <address>
                                    <h3><?= Yii::$app->name ?></h3>
                                    <br>
                                    <span>Uzbekistan Samarkand</span>
                                    <br><br>
                                    <span>Beruniy street, 1/5</span>
                                    <br>
                                    <abbr title="Telephone">Phone:</abbr> <?= $params['phone'] ?>
                                    <br>
                                    <abbr title="Email">Email:</abbr> <a
                                            href="mailto:<?= $params['adminEmail'] ?>"><?= $params['adminEmail'] ?></a>
                                </address>
                                <div class="icons">
                                    <a href=""><i class="fa fa-twitter"></i></a>
                                    <a href=""><i class="fa fa-facebook"></i></a>
                                    <a href=""><i class="fa fa-pinterest"></i></a>
                                    <a href=""><i class="fa fa-youtube-play"></i></a>
                                </div><!-- /.icons -->
                                <hr>
                                <p>
                                    If you have an enquiry about an existing course or if you wish to talk to us about
                                    your specific requirements please call or email using the details provided.
                                    Alternatively you may use the form below to get in touch.
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="map-wrapper">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3071.780816052281!2d66.91040297781956!3d39.65464709999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3f4d190002e36ddf%3A0x8e83f4ad3be4f23a!2sMeros%20International%20Hospital!5e0!3m2!1sru!2s!4v1780140649710!5m2!1sru!2s"
                                            width="100%" height="350" frameborder="0" style="border:0"></iframe>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section id="contact-form" class="clearfix">
                        <header><h2>Send Us a Message</h2></header>
                        <form class="contact-form" id="contactform" method="post" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="controls">
                                            <label for="name">Your Name</label>
                                            <input type="text" name="name" id="name" required>
                                        </div><!-- /.controls -->
                                    </div><!-- /.control-group -->
                                </div><!-- /.col-md-4 -->
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="controls">
                                            <label for="email">Your Email</label>
                                            <input type="email" name="email" id="email" required>
                                        </div><!-- /.controls -->
                                    </div><!-- /.control-group -->
                                </div><!-- /.col-md-4 -->
                            </div><!-- /.row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <div class="controls">
                                            <label for="message">Your Message</label>
                                            <textarea name="message" id="message" required></textarea>
                                        </div><!-- /.controls -->
                                    </div><!-- /.control-group -->
                                </div><!-- /.col-md-4 -->
                            </div><!-- /.row -->
                            <div class="pull-right">
                                <input type="submit" class="btn btn-color-primary" id="submit" value="Send a Message">
                            </div><!-- /.form-actions -->
                            <div id="form-status"></div>
                        </form><!-- /.footer-form -->
                    </section>
                </div><!-- /#page-main -->
            </div><!-- /.col-md-8 -->

            <!--SIDEBAR Content-->
            <div class="col-md-4">
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
                                <header><a href="#">Three U-Michigan scientists receive Sloan fellowships</a></header>
                            </article><!-- /article -->
                        </div><!-- /.section-content -->
                        <a href="" class="read-more">All News</a>
                    </aside><!-- /.news-small -->
                </div><!-- /#sidebar -->
            </div><!-- /.col-md-4 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</div>
<!-- end Page Content -->
