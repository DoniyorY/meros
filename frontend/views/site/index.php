<?php

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
$base = Yii::$app->request->baseUrl;
$params = Yii::$app->params;
$lang = Yii::$app->language;
function translate($key)
{
    $lang = Yii::$app->language;
    return Yii::$app->params[$key][$lang];
}

?>
<!-- Homepage Slider -->
<section id="homepage-slider">
    <div class="flexslider">
        <ul class="slides">
            <?php foreach ($banner as $item):?>
            <li class="slide">
                <figure>
                    <div class="slide-wrapper">
                        <div class="inner">
                            <div class="container" style="display: none">
                                <h2><?=$item->{"name_$lang"}?></h2>
                                <h1><?=$item->{"desc_$lang"}?></h1>
                                <?php if ($item->link):?>
                                <div><a href="<?=\yii\helpers\Url::to([$item->link])?>" class="btn">View Details</a></div>
                                <?php endif;?>
                            </div>
                        </div><!-- /.inner -->
                    </div><!-- /.wrapper -->
                </figure>
                <img src="<?= "$base/uploads/banners/$item->image" ?>">
            </li>
            <?php endforeach;?>
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
                <?= translate('about_content_index') ?>
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
                <div class="row">
                    <!--MAIN Content-->
                    <div class="col-md-12">
                        <div id="page-main">
                            <section class="blog-listing" id="blog-listing">
                                <header><h1>Blog / News</h1></header>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6" style="min-height: 390px;">
                                        <article class="blog-listing-post">
                                            <figure class="blog-thumbnail">
                                                <figure class="blog-meta"><span class="fa fa-file-text-o"></span>08-24-2014</figure>
                                                <div class="image-wrapper"><a href="blog-detail.html"><img src="<?="$base/"?>img/blog-01.jpg"></a></div>
                                            </figure>
                                            <aside>
                                                <header>
                                                    <a href="blog-detail.html"><h3>Conservatory Exhibit: The garden of india a country and culture revealed</h3></a>
                                                </header>
                                                <div class="description">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et urna fringilla
                                                        volutpat elit non, tristique lectus. Nam blandit odio nisl, ac malesuada lacus fermentum sit amet.
                                                        Vestibulum vitae aliquet felis, ornare feugiat elit. Nulla varius condimentum elit.
                                                    </p>
                                                </div>
                                                <a href="blog-detail.html" class="read-more stick-to-bottom">Read More</a>
                                            </aside>
                                        </article><!-- /article -->
                                    </div><!-- /.col-md-6 -->
                                    <div class="col-md-6 col-sm-6" style="min-height: 390px;">
                                        <article class="blog-listing-post">
                                            <figure class="blog-thumbnail">
                                                <figure class="blog-meta"><span class="fa fa-file-text-o"></span>08-24-2014</figure>
                                                <div class="image-wrapper"><a href="blog-detail.html"><img src="<?="$base/"?>img/blog-02.jpg"></a></div>
                                            </figure>
                                            <aside>
                                                <header>
                                                    <a href="blog-detail.html"><h3>Pellentesque dignissim fermentum nunc vel ultricies. Vivamus nec</h3></a>
                                                </header>
                                                <div class="description">
                                                    <p>Nulla in mi sed tellus porta mollis vitae ut libero. Nam id tempor augue, id scelerisque nunc.
                                                        Mauris varius tortor in nibh dictum auctor. Cum sociis natoque penatibus et magnis dis parturient
                                                        montes, nascetur ridiculus mus. Proin scelerisque eros mi, et convallis mi pretium id.
                                                    </p>
                                                </div>
                                                <a href="blog-detail.html" class="read-more stick-to-bottom">Read More</a>
                                            </aside>
                                        </article><!-- /article -->
                                    </div><!-- /.col-md-6 -->
                                </div><!-- /.row -->
                            </section><!-- /.blog-listing -->
                        </div><!-- /#page-main -->
                    </div><!-- /.col-md-8 -->
                </div>
            </div>
            <div class="background background-color-grey-background"></div>
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


