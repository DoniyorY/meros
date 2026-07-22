<?php

use yii\helpers\Url;

$base = Yii::$app->request->baseUrl;

?>

<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="<?= Yii::$app->homeUrl ?>" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?= $base . '/' ?>images/logo-sm.png" alt="" height="22">
                    </span>
            <span class="logo-lg">
                        <img src="<?= $base . '/logo.png' ?>" alt="" height="45">
                    </span>
        </a>
        <!-- Light Logo-->
        <a href="<?= Yii::$app->homeUrl ?>" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="<?= $base . '/' ?>images/logo-sm.png" alt="" height="22">
                    </span>
            <span class="logo-lg">
                        <img src="<?= $base . '/logo-white.png' ?>" alt="" height="45">
                    </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarCourses" data-bs-toggle="collapse" role="button"
                       aria-expanded="false" aria-controls="sidebarCourses">
                        <i class="ri-dashboard-2-line"></i> <span>Course Settings</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarCourses">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?= Url::to(['course-category/index']) ?>" class="nav-link"
                                   data-key="t-analytics">
                                    Course Categories </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= Url::to(['courses/index']) ?>" class="nav-link" data-key="t-crm">
                                    Courses </a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= Url::to(['mentors/index']) ?>">
                        <i class="ri-apps-2-line"></i> <span>Mentors</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= Url::to(['subscription-plans/index']) ?>">
                        <i class="ri-money-dollar-box-line"></i> <span>Subscriptions</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarNews" data-bs-toggle="collapse" role="button"
                       aria-expanded="false" aria-controls="sidebarNews">
                        <i class="ri-layout-3-line"></i> <span>News</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarNews">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?=Url::to(['post-category/index'])?>" class="nav-link"
                                   data-key="t-horizontal">News Category</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?=Url::to(['posts/index'])?>" class="nav-link">News</a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSettings" data-bs-toggle="collapse" role="button"
                       aria-expanded="false" aria-controls="sidebarSettings">
                        <i class="ri-account-circle-line"></i> <span>Settings</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSettings">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?=Url::to(['banner/index'])?>" class="nav-link"> Banner </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?=Url::to(['gallery/index'])?>" class="nav-link"> Gallery</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?=Url::to(['user/index'])?>" class="nav-link"> User</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?=Url::to(['contacts/index'])?>" class="nav-link"> Contact</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?=Url::to(['events/index'])?>" class="nav-link"> Events</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?=Url::to(['faq/index'])?>" class="nav-link"> FAQ</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span>Finance</span>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?=Url::to(['billing/index'])?>">
                        <i class="ri-honour-line"></i> <span>Billing</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->