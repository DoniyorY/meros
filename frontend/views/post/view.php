<?php
$lang = Yii::$app->language;
$params = Yii::$app->params;
$base = Yii::$app->request->baseUrl;

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $model->name_en;
?>
<!-- Breadcrumb -->
<div class="container">
    <ol class="breadcrumb flex-wrap">
        <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
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
                    <section id="blog-detail">
                        <header><h1>News</h1></header>
                        <article class="blog-detail">
                            <header class="blog-detail-header">
                                <img src="<?= "$base/uploads/posts/$model->image" ?>" class="img-fluid" alt="<?= Html::encode($model->{"name_$lang"}) ?>">
                                <h2><?= Html::encode($model->name_en) ?></h2>
                                <div class="blog-detail-meta">
                                    <span class="date"><span
                                                class="fa fa-file-o"></span><?= date('d.m.Y', $model->created_at) ?></span>
                                </div>
                            </header>
                            <hr>
                            <?= $model->{"content_$lang"} ?>
                        </article>
                    </section><!-- /.blog-detail -->
                    <hr>
                    <section id="related-articles">
                        <header><h2>Related News</h2></header>
                        <div class="row g-4">
                            <?php foreach ($related as $item): ?>
                                <div class="col-md-6 col-12" style="min-height: 390px;">
                                    <article class="blog-listing-post">
                                        <figure class="blog-thumbnail">
                                            <figure class="blog-meta"><span
                                                        class="fa fa-file-text-o"></span><?= date('d.m.Y', $item->created_at) ?>
                                            </figure>
                                            <div class="image-wrapper">
                                                <a href="<?= Url::to(['post/view', 'id' => $item->id]) ?>">
                                                    <img src="<?= "$base/uploads/posts/$item->image" ?>"
                                                         style="height: 330px; object-fit: cover">
                                                </a>
                                            </div>
                                        </figure>
                                        <aside>
                                            <header>
                                                <a href="<?= Url::to(['post/view', 'id' => $item->id]) ?>">
                                                    <h3>
                                                        <?= $item->{"name_$lang"} ?>
                                                    </h3>
                                                </a>
                                            </header>
                                            <a href="<?= Url::to(['post/view', 'id' => $item->id]) ?>"
                                               class="read-more stick-to-bottom">
                                                Read More
                                            </a>
                                        </aside>
                                    </article><!-- /article -->
                                </div><!-- /.col-md-6 -->
                            <?php endforeach; ?>
                        </div><!-- /.row -->
                    </section><!-- /related articles -->

                    <hr>
                </div><!-- /#page-main -->
            </div><!-- /.col-md-8 -->

            <!--SIDEBAR Content-->
            <div class="col-lg-4 col-md-12">
                <div id="page-sidebar" class="sidebar">
                    <aside class="news-small" id="news-small">
                        <header>
                            <h2>Related News</h2>
                        </header>
                        <div class="section-content">
                            <?php foreach ($related as $item): ?>
                                <article>
                                    <figure class="date"><i
                                                class="fa fa-file-o"></i><?= date('d.m.Y', $item->created_at) ?>
                                    </figure>
                                    <header>
                                        <a href="<?= Url::to(['view', 'id' => $item->id]) ?>"><?= $item->{"name_$lang"} ?></a>
                                    </header>
                                </article><!-- /article -->
                            <?php endforeach; ?>
                        </div><!-- /.section-content -->
                        <a href="<?= Url::to(['index']) ?>" class="read-more">All News</a>
                    </aside><!-- /.news-small -->
                </div><!-- /#sidebar -->
            </div><!-- /.col-md-4 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</div>
<!-- end Page Content -->
