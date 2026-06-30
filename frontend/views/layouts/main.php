<?php

/** @var \yii\web\View $this */

/** @var string $content */

use cinghie\multilanguage\widgets\MultiLanguageWidget;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Html;
use common\models\CourseCategory;
use common\models\Courses;
use yii\helpers\Url;

AppAsset::register($this);
$base = Yii::$app->request->baseUrl;
$lang = Yii::$app->language;
$category = CourseCategory::findAll(['status' => 1]);
$params = Yii::$app->params;
$homeLabel = $params['home'][$lang] ?? $params['home']['en'] ?? 'Home';

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
       <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
       <?php $this->head() ?>
    </head>
    <body class="page-homepage-carousel">
    <?php $this->beginBody() ?>
    <div class="meros-page-loader" id="meros-page-loader" role="status" aria-live="polite" aria-label="Loading">
        <div class="meros-loader-mark">
            <span class="meros-loader-spinner" aria-hidden="true"></span>
            <span>Meros</span>
        </div>
    </div>
    <div class="wrapper">
        <div class="secondary-navigation-wrapper">
            <div class="container d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
                <div class="navigation-contact text-white"><?= $params['call_us'][$lang] ?? $params['call_us']['en'] ?>: <span><?= $params['phone'] ?></span></div>
                <ul class="secondary-navigation list-unstyled d-flex flex-wrap justify-content-center justify-content-sm-end gap-3 mb-0">
                   <?php if (!Yii::$app->user->isGuest): ?>
                       <li><a href="<?= Url::to(['site/profile']) ?>"><i class="fa fa-user"></i><?=$params['my_profile'][$lang]?></a></li>
                      <?php \yii\widgets\ActiveForm::begin(['action' => Url::to(['site/logout']),'method' => 'post', 'options' => ['class' => 'logout_form']]) ?>
                        <li>
                            <button type="submit" class="btn btn-link logout text-decoration-none" style="color: white"><?=$params['logout'][$lang]?></button>
                        </li>
                      <?php \yii\widgets\ActiveForm::end(); ?>
                   <?php else: ?>
                       <li><a href="<?= Url::to(['site/login']) ?>"><?=$params['login'][$lang]?></a></li>
                   <?php endif; ?>
                </ul>
            </div>
        </div><!-- /.secondary-navigation -->
        <!-- Header -->
        <div class="navigation-wrapper sticky-top" style="position:sticky">
            <div class="primary-navigation-wrapper">
                <header class="navbar navbar-expand-lg" id="top" role="banner">
                    <div class="container-fluid px-3 px-lg-4">
                        <div class="navbar-header d-flex align-items-center justify-content-between">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#primary-navigation" aria-controls="primary-navigation"
                                    aria-expanded="false" aria-label="<?= $params['toggle_navigation'][$lang] ?? $params['toggle_navigation']['en'] ?>">
                                <span class="visually-hidden"><?= $params['toggle_navigation'][$lang] ?? $params['toggle_navigation']['en'] ?></span>
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="navbar-brand nav" id="brand">
                                <a href="<?= Yii::$app->homeUrl ?>" class="brand-link">
                                    <img src="<?= "$base/logo-white.png" ?>" alt="brand" class="brand-logo"
                                         style="height: 50px;object-fit:contain">
                                </a>
                            </div>
                        </div>
                        <nav class="collapse navbar-collapse bs-navbar-collapse justify-content-lg-end"
                             id="primary-navigation" role="navigation">
                            <ul class="navbar-nav ms-lg-auto align-items-lg-center">
                               <?php foreach ($category as $item): $courses = Courses::findAll(['category_id' => $item->id, 'status' => 1]) ?>
                                   <li class="nav-item has-child-wrapper">
                                       <a href="#" class="nav-link has-child no-link" aria-haspopup="true"
                                          aria-expanded="false"><?= $item->{"name_$lang"} ?></a>
                                       <ul class="list-unstyled child-navigation">
                                          <?php foreach ($courses as $value): ?>
                                              <li>
                                                  <a href="<?= \yii\helpers\Url::to(['courses/index', 'category' => $item->slug, 'slug' => $value->slug]) ?>"><?= $value->{"name_$lang"} ?></a>
                                              </li>
                                          <?php endforeach; ?>
                                       </ul>
                                   </li>
                               <?php endforeach; ?>
                                <li class="nav-item has-child-wrapper">
                                    <a href="#" class="nav-link has-child no-link" aria-haspopup="true"
                                       aria-expanded="false"><?=$params['about_us'][$lang]?></a>
                                    <ul class="list-unstyled child-navigation">
                                        <li>
                                            <a href="<?= Url::to(['site/about']) ?>"> <?=$params['about_meros'][$lang]?></a>
                                        </li>
                                        <li>
                                            <a href="<?= Url::to(['site/contact']); ?>"> <?=$params['contact_us'][$lang]?></a>
                                        </li>
                                        <li>
                                            <a href="<?= Url::to(['site/team']) ?>"> <?=$params['meet_the_team'][$lang]?></a>
                                        </li>
                                        <li>
                                            <a href="<?= Url::to(['site/policy']) ?>"> <?=$params['policy'][$lang]?></a>
                                        </li>
                                        <li>
                                            <a href="<?= Url::to(['site/faq-students']) ?>"> <?=$params['faq_students'][$lang]?></a>
                                        </li>
                                    </ul>
                                </li>
                               <?= MultiLanguageWidget::widget([
                                  'addCurrentLang' => true, // add current lang
                                  'calling_controller' => $this->context,
                                  'image_type'  => 'rounded', // classic or rounded
                                  'link_home'   => true, // true or false
                                  'widget_type' => 'selector', // classic or selector
                                  //'width'       => '28'
                               ]); ?>
                            </ul>
                        </nav><!-- /.navbar collapse-->
                    </div><!-- /.container -->
                </header><!-- /.navbar -->
            </div><!-- /.primary-navigation -->
            <div class="background"></div>
        </div>
        <!-- end Header -->
       
       <?php
       $headerDropdownJs = <<<JS
(function () {
    var mobileQuery = window.matchMedia('(max-width: 991.98px)');
    var navigation = document.getElementById('primary-navigation');

    if (!navigation) {
        return;
    }

    navigation.addEventListener('click', function (event) {
        var trigger = event.target.closest('.has-child-wrapper > .has-child');

        if (!trigger || !navigation.contains(trigger) || !mobileQuery.matches) {
            return;
        }

        event.preventDefault();

        var item = trigger.parentElement;
        var isOpen = item.classList.contains('is-open');

        navigation.querySelectorAll('.has-child-wrapper.is-open').forEach(function (openItem) {
            if (openItem !== item) {
                openItem.classList.remove('is-open');
                var openTrigger = openItem.querySelector(':scope > .has-child');
                if (openTrigger) {
                    openTrigger.setAttribute('aria-expanded', 'false');
                }
            }
        });

        item.classList.toggle('is-open', !isOpen);
        trigger.setAttribute('aria-expanded', String(!isOpen));
    });

    mobileQuery.addEventListener('change', function () {
        if (!mobileQuery.matches) {
            navigation.querySelectorAll('.has-child-wrapper.is-open').forEach(function (openItem) {
                openItem.classList.remove('is-open');
                var trigger = openItem.querySelector(':scope > .has-child');
                if (trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });
}());
JS;
       $this->registerJs($headerDropdownJs);
       ?>
       
       <?php
       $pageLoaderJs = <<<JS
(function () {
    var loader = document.getElementById('meros-page-loader');

    if (!loader) {
        return;
    }

    function hideLoader() {
        loader.classList.add('is-hidden');
        window.setTimeout(function () {
            if (loader && loader.parentNode) {
                loader.parentNode.removeChild(loader);
            }
        }, 500);
    }

    if (document.readyState === 'complete') {
        hideLoader();
    } else {
        window.addEventListener('load', hideLoader);
        window.setTimeout(hideLoader, 2500);
    }
}());
JS;
       $this->registerJs($pageLoaderJs);
       ?>
       
       <?php
       $route = Yii::$app->controller ? Yii::$app->controller->route : '';
       $showBreadcrumbs = $route !== 'site/index';
       $breadcrumbs = $this->params['breadcrumbs'] ?? [];
       if ($showBreadcrumbs && empty($breadcrumbs) && !empty($this->title)) {
          $breadcrumbs[] = $this->title;
       }
       ?>

       <?= Alert::widget() ?>
       <?php if ($showBreadcrumbs && !empty($breadcrumbs)): ?>
           <nav class="meros-breadcrumb-shell" aria-label="Breadcrumb">
               <div class="container">
                   <ol class="breadcrumb meros-breadcrumb flex-wrap">
                       <li class="breadcrumb-item meros-breadcrumb__item meros-breadcrumb__home">
                           <a href="<?= Yii::$app->homeUrl ?>">
                               <span class="fa fa-home" aria-hidden="true"></span>
                               <span><?= Html::encode($homeLabel) ?></span>
                           </a>
                       </li>
                      <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
                         <?php
                         $isLast = $index === array_key_last($breadcrumbs);
                         if (is_array($breadcrumb)) {
                            $label = $breadcrumb['label'] ?? '';
                            $url = $breadcrumb['url'] ?? null;
                         } else {
                            $label = $breadcrumb;
                            $url = null;
                         }
                         ?>
                         <?php if (!$isLast && $url): ?>
                             <li class="breadcrumb-item meros-breadcrumb__item">
                                 <a href="<?= Html::encode(Url::to($url)) ?>"><?= Html::encode($label) ?></a>
                             </li>
                         <?php else: ?>
                             <li class="breadcrumb-item meros-breadcrumb__item active" aria-current="page">
                                 <span><?= Html::encode($label) ?></span>
                             </li>
                         <?php endif; ?>
                      <?php endforeach; ?>
                   </ol>
               </div>
           </nav>
       <?php endif; ?>
       <?= $content ?>

        <!-- Footer -->
        <footer id="page-footer">
            <section id="footer-top">
                <div class="container">
                    <div class="footer-inner">
                        <div class="footer-social">
                            <figure><?=$params['follow_us'][$lang]?>:</figure>
                            <div class="icons">
                                <a href=""><i class="fa fa-twitter"></i></a>
                                <a href=""><i class="fa fa-facebook"></i></a>
                                <a href=""><i class="fa fa-pinterest"></i></a>
                                <a href=""><i class="fa fa-youtube-play"></i></a>
                            </div><!-- /.icons -->
                        </div><!-- /.social -->
                    </div><!-- /.footer-inner -->
                </div><!-- /.container -->
            </section><!-- /#footer-top -->

            <section id="footer-content">
                <div class="container">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6 col-12 footer-logo-column">
                            <aside class="logo">
                                <a href="<?= Yii::$app->homeUrl ?>">
                                    <img src="<?= "$base/logo-white.png" ?>" class="vertical-center footer-logo"
                                         alt="Meros">
                                </a>
                            </aside>
                        </div><!-- /.col-md-3 -->
                        <div class="col-lg-3 col-md-6 col-12">
                            <aside>
                                <header><h4><?=$params['contact_us'][$lang]?></h4></header>
                                <address>
                                    <strong><?= Yii::$app->name ?></strong>
                                    <br>
                                    <?=$params['address_footer'][$lang]?>
                                    <br>
                                    <abbr style="text-decoration: none; cursor: default;" title="Telephone"><?=$params['label_phone'][$lang]?>:</abbr> <?= $params['phone'] ?>
                                    <br>
                                    <abbr style="text-decoration: none; cursor: default;" title="Email"><?=$params['label_email'][$lang]?>:</abbr> <a
                                            href="mailto:<?= $params['adminEmail'] ?>"><?= $params['adminEmail'] ?></a>
                                </address>
                            </aside>
                        </div><!-- /.col-md-3 -->
                        <div class="col-lg-3 col-md-6 col-12">
                            <aside>
                                <header><h4><?=$params['important_links'][$lang]?></h4></header>
                                <ul class="list-links">
                                    <li><a href="<?= Url::to(['site/about']) ?>"><?=$params['about_meros'][$lang]?></a></li>
                                    <li><a href="#"><?=$params['faq_students'][$lang]?></a></li>
                                    <li><a href="#"><?=$params['faq_teachers'][$lang]?></a></li>
                                    <li><a href="<?= Url::to(['site/policy']) ?>"><?=$params['policy_privacy'][$lang]?></a></li>
                                </ul>
                            </aside>
                        </div><!-- /.col-md-3 -->
                        <div class="col-lg-3 col-md-6 col-12">
                            <aside>
                                <header><h4><?=$params['about_meros'][$lang]?></h4></header>
                               <?= Yii::$app->params['about_short'][$lang] ?>
                            </aside>
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->
                </div><!-- /.container -->
                <div class="background"><img src="<?= "$base/" ?>img/background-city.png" class="" alt=""></div>
            </section><!-- /#footer-content -->

            <section id="footer-bottom">
                <div class="container">
                    <div class="footer-inner">
                        <div class="copyright"><?= $params['copyright'][$lang] ?? $params['copyright']['en'] ?></div><!-- /.copyright -->
                    </div><!-- /.footer-inner -->
                </div><!-- /.container -->
            </section><!-- /#footer-bottom -->

        </footer>
        <!-- end Footer -->
    </div>
    <script src="//code.jivosite.com/widget/sAIAhsY8qj" async></script>
    <!--<script src="/js/jquery-2.1.0.min.js"></script>-->
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
