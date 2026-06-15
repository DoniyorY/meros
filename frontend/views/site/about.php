<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

function translate($key)
{
   $lang = Yii::$app->language;
   return Yii::$app->params[$key][$lang];
}
$this->title = translate('about_meros_international_institute');
$params = Yii::$app->params;
$base = Yii::$app->request->baseUrl;
$lang = Yii::$app->language;


?>
<!-- Breadcrumb -->
<div class="container">
    <ol class="breadcrumb flex-wrap">
        <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?=translate('home')?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($this->title) ?></li>
    </ol>
</div>
<!-- end Breadcrumb -->


<!-- Page Content -->
<div id="page-content">
    <div class="container">
        <div class="row g-4">
            <!--MAIN Content-->
            <div class="col-lg-8 col-md-12">
                <div id="page-main">
                    <section id="about">
                        <header><h1><?= Html::encode($this->title) ?></h1></header>
                        <img src="<?= "$base/images/meros_hospital.jpg" ?>"
                             class="img-fluid rounded about-hero-image" alt="Meros International Hospital">
                        <?= translate('about_content') ?>
                        <h2><?=translate('gallery')?></h2>
                        <div>
                            <ul class="gallery-list">
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-01.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-02.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-03.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-04.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-05.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-06.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-07.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-08.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-09.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-10.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-11.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-12.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-13.jpg" alt=""></a></li>
                                <li><a href="<?= "$base/" ?>img/gallery-big-image.jpg" class="image-popup"><img
                                                src="<?= "$base/" ?>img/image-14.jpg" alt=""></a></li>
                            </ul>
                            <a href="" class="read-more"><?=translate('go_to_gallery')?></a>
                        </div>
                    </section>
                </div><!-- /#page-main -->
            </div><!-- /.col-md-8 -->

            <!--SIDEBAR Content-->
            <div class="col-lg-4 col-md-12">
                <div id="page-sidebar" class="sidebar">
                    <aside class="news-small" id="news-small">
                        <header>
                            <h2><?=translate('latest_news')?></h2>
                        </header>
                        <div class="section-content">
                            <?php foreach ($posts as $item):?>
                            <article>
                                <figure class="date"><i class="fa fa-file-o"></i><?=date('d.m.Y',$item->created_at)?></figure>
                                <header>
                                    <a href="<?=Url::to(['post/view','id'=>$item->id])?>">
                                        <?=$item->{"name_$lang"}?>
                                    </a>
                                </header>
                            </article><!-- /article -->
                            <?php endforeach;?>
                      
                        </div><!-- /.section-content -->
                        <a href="<?=Url::to(['post/index'])?>" class="read-more"><?=translate('all_news')?></a>
                    </aside><!-- /.news-small -->
                    <aside id="our-professors">
                        <header>
                            <h2><?=translate('our_professors')?></h2>
                        </header>
                        <div class="section-content">
                            <div class="professors">
                                <article class="professor-thumbnail">
                                    <figure class="professor-image"><a href="member-detail.html"><img
                                                    src="<?= "$base/" ?>img/professor.jpg" alt=""></a></figure>
                                    <aside>
                                        <header>
                                            <a href="member-detail.html">Prof. Mathew Davis</a>
                                            <div class="divider"></div>
                                            <figure class="professor-description">Applied Science and Engineering
                                            </figure>
                                        </header>
                                        <a href="member-detail.html" class="show-profile">Show Profile</a>
                                    </aside>
                                </article><!-- /.professor-thumbnail -->
                                <article class="professor-thumbnail">
                                    <figure class="professor-image"><a href="member-detail.html"><img
                                                    src="<?= "$base/" ?>img/professor-02.jpg" alt=""></a></figure>
                                    <aside>
                                        <header>
                                            <a href="member-detail.html">Prof. Jane Stairway</a>
                                            <div class="divider"></div>
                                            <figure class="professor-description">Applied Science and Engineering
                                            </figure>
                                        </header>
                                        <a href="member-detail.html" class="show-profile">Show Profile</a>
                                    </aside>
                                </article><!-- /.professor-thumbnail -->
                                <a href="" class="read-more">All Professors</a>
                            </div><!-- /.professors -->
                        </div><!-- /.section-content -->
                    </aside><!-- /.our-professors -->
                </div><!-- /#sidebar -->
            </div><!-- /.col-md-4 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</div>
<!-- end Page Content -->