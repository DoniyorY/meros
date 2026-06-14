<?php

use yii\helpers\Url;
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
?>

<!-- Breadcrumb -->
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=Yii::$app->homeUrl?>">Home</a></li>
        <li class="active">Blog</li>
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
                    <section class="blog-listing" id="blog-listing">
                        <header><h1>News</h1></header>
                        <div class="row">
                            <?php foreach ($posts as $post): ?>
                            <div class="col-md-6 col-sm-6">
                                <article class="blog-listing-post">
                                    <figure class="blog-thumbnail">
                                        <figure class="blog-meta"><span class="fa fa-file-text-o"></span><?=date('d.m.Y',$post->created_at)?></figure>
                                        <div class="image-wrapper"><a href="<?=Url::to(['view','id'=>$post->id])?>"><img src="<?="$base/uploads/posts/$post->image"?>"></a></div>
                                    </figure>
                                    <aside>
                                        <header>
                                            <a href="<?=Url::to(['view','id'=>$post->id])?>"><h3><?=$post->{"name_$lang"}?></h3></a>
                                        </header>
                                        <div class="description">
                                            <p>
                                                <?=$post->{"desc_$lang"}?>
                                            </p>
                                        </div>
                                        <a href="<?=Url::to(['view','id'=>$post->id])?>" class="read-more">Read More</a>
                                    </aside>
                                </article><!-- /article -->
                            </div><!-- /.col-md-6 -->
                            <?php endforeach;?>
                        </div><!-- /.row -->
                    </section><!-- /.blog-listing -->
                    <div class="center d-none">
                        <ul class="pagination">
                            <li class="active"><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                        </ul>
                    </div>
                </div><!-- /#page-main -->
            </div><!-- /.col-md-8 -->

            <!--SIDEBAR Content-->
            <div class="col-md-4">
                <div id="page-sidebar" class="sidebar">
                    <aside id="newsletter">
                        <header>
                            <h2>Search</h2>
                            <div class="section-content">
                                <div class="newsletter">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Your e-mail">
                                        <span class="input-group-btn">
                                        <button type="submit" class="btn"><i class="fa fa-angle-right"></i></button>
                                    </span>
                                    </div><!-- /input-group -->
                                </div><!-- /.newsletter -->
                                <p class="opacity-50">
                                    Fill the filed to search news
                                </p>
                            </div><!-- /.section-content -->
                        </header>
                    </aside><!-- /.newsletter -->
                    <aside id="archive">
                        <header>
                            <h2>Categories</h2>
                            <ul class="list-links">
                                <li><a href="#">University News</a></li>
                            </ul>
                        </header>
                    </aside><!-- /archive -->
                </div><!-- /#sidebar -->
            </div><!-- /.col-md-4 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</div>
<!-- end Page Content -->
