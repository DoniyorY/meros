<?php

use yii\helpers\Url;

/** @var yii\web\View $this */
$base = Yii::$app->request->baseUrl;

$this->title = 'Meros Admin Panel';
?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?= $this->title ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active"><?= $this->title ?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-9">
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Billing
                                            Count</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                                                              data-target="<?= Yii::$app->formatter->asDecimal($billing_count) ?>"><?= Yii::$app->formatter->asDecimal($billing_count) ?></span>
                                        </h4>
                                        <a href="<?= Url::to(['billing/index']) ?>" class="text-decoration-underline">View
                                            All Billings</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                            <i class="ri-honour-line text-primary"></i>
                                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Subscribed
                                            Users</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                                                              data-target="<?= $subs_count ?>"><?= $subs_count ?></span>
                                        </h4>
                                        <a href="<?= Url::to(['user-subscriptions/index']) ?>"
                                           class="text-decoration-underline">View User Subscriptions</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                            <i class="ri-id-card-line text-primary"></i>
                                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Customers</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                                                              data-target="<?= $client_count ?>"><?= $client_count ?></span>
                                        </h4>
                                        <a href="<?= Url::to(['user/clients']) ?>" class="text-decoration-underline">All
                                            Clients</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                            <i class="bx bx-user-circle text-primary"></i>
                                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Course Count</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                                                               data-target="<?=$course_count?>"><?=$course_count?></span>
                                        </h4>
                                        <a href="<?=Url::to(['courses/index'])?>" class="text-decoration-underline">All Courses</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                            <i class="bx bx-wallet text-primary"></i>
                                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h5 class="card-title mb-0 flex-grow-1">Site Visitors</h5>
                            <div class="flex-shrink-0">
                                <div class="dropdown card-header-dropdown">
                                    <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span
                                                class="text-muted">Current Week<i class="mdi mdi-chevron-down ms-1"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Today</a>
                                        <a class="dropdown-item" href="#">Last Week</a>
                                        <a class="dropdown-item" href="#">Last Month</a>
                                        <a class="dropdown-item" href="#">Current Year</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <div id="column_chart_datalabel" data-colors='["--vz-primary"]' class="apex-charts"
                                 dir="ltr"></div>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end col-->
            <div class="col-xl-3">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Recent Contacts</h4>
                        <div class="flex-shrink-0">
                            <a href="<?=Url::to(['contacts/index'])?>" class="btn btn-soft-primary btn-sm">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div data-simplebar class="mx-n3 px-3" style="height: 440px;">
                            <div class="vstack gap-3">
                                <?php foreach ($contacts as $item):?>
                                <div class="d-flex gap-3">
                                    <img src="<?= "$base/" ?>images/users/user-dummy-img.jpg" alt=""
                                         class="avatar-sm rounded flex-shrink-0">
                                    <div class="flex-shrink-1">
                                        <h6 class="mb-2"><?=$item->fullname?> <span class="text-muted"><?=date('d.m.Y H:i:s',$item->created_at)?></span></h6>
                                        <p class="text-muted mb-0">" <?=$item->subject?> "</p>
                                    </div>
                                </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Recent Article</h4>
                        <div class="flex-shrink-0">
                            <div class="dropdown card-header-dropdown">
                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span
                                            class="text-muted">Popular <i class="mdi mdi-chevron-down ms-1"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Popular</a>
                                    <a class="dropdown-item" href="#">Newest</a>
                                    <a class="dropdown-item" href="#">Oldest</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-hover table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                <tr class="text-muted">
                                    <th scope="col">No</th>
                                    <th scope="col">Blog Title</th>
                                    <th scope="col">Post Date</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Comment</th>
                                    <th scope="col">Like</th>
                                    <th scope="col">Shared</th>
                                    <th scope="col">Viewers</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr>
                                    <td>01</td>
                                    <td>
                                        <img src="<?= "$base/" ?>images/blog/img-2.jpg" alt="" class="me-2 rounded"
                                             height="40">
                                        <a href="#!" class="text-body fw-medium">The Evolution of Minimalism in
                                            Design</a>
                                    </td>
                                    <td>20 Sep, 2024</td>
                                    <td><span class="badge bg-success-subtle text-success p-2">MinimalDesign</span></td>
                                    <td>23</td>
                                    <td>157</td>
                                    <td>11</td>
                                    <td>2149</td>
                                </tr>
                                <tr>
                                    <td>02</td>
                                    <td>
                                        <img src="<?= "$base/" ?>images/blog/img-3.jpg" alt="" class="me-2 rounded"
                                             height="40">
                                        <a href="#!" class="text-body fw-medium">Mastering User Experience Through
                                            Storytelling</a>
                                    </td>
                                    <td>11 Feb, 2024</td>
                                    <td><span class="badge bg-success-subtle text-success p-2">UXDesign</span></td>
                                    <td>547</td>
                                    <td>1458</td>
                                    <td>317</td>
                                    <td>34978</td>
                                </tr>
                                <tr>
                                    <td>03</td>
                                    <td>
                                        <img src="<?= "$base/" ?>images/blog/img-4.jpg" alt="" class="me-2 rounded"
                                             height="40">
                                        <a href="#!" class="text-body fw-medium">Designing for Purpose: A Mindful
                                            Approach</a>
                                    </td>
                                    <td>15 Sep, 2024</td>
                                    <td><span class="badge bg-success-subtle text-success p-2">CreativeProcess</span>
                                    </td>
                                    <td>88</td>
                                    <td>649</td>
                                    <td>237</td>
                                    <td>1982</td>
                                </tr>
                                <tr>
                                    <td>04</td>
                                    <td>
                                        <img src="<?= "$base/" ?>images/blog/img-5.jpg" alt="" class="me-2 rounded"
                                             height="40">
                                        <a href="#!" class="text-body fw-medium">How to Overcome Creative Block</a>
                                    </td>
                                    <td>09 July, 2024</td>
                                    <td><span class="badge bg-success-subtle text-success p-2">CreativeBlock</span></td>
                                    <td>67</td>
                                    <td>1114</td>
                                    <td>1547</td>
                                    <td>15747</td>
                                </tr>
                                <tr>
                                    <td>05</td>
                                    <td>
                                        <img src="<?= "$base/" ?>images/blog/img-6.jpg" alt="" class="me-2 rounded"
                                             height="40">
                                        <a href="#!" class="text-body fw-medium">Building Brand Identity through
                                            Design</a>
                                    </td>
                                    <td>19 Nov, 2024</td>
                                    <td><span class="badge bg-success-subtle text-success p-2">BrandDesign</span></td>
                                    <td>8</td>
                                    <td>10</td>
                                    <td>7</td>
                                    <td>110</td>
                                </tr>
                                </tbody><!-- end tbody -->
                            </table><!-- end table -->
                        </div>
                        <div class="align-items-center mt-3 row g-3 text-center text-sm-start">
                            <div class="col-sm">
                                <div class="text-muted">Showing <span class="fw-semibold">5</span> of <span
                                            class="fw-semibold">14</span> Results
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <ul class="pagination pagination-separated pagination-sm justify-content-center justify-content-sm-start mb-0">
                                    <li class="page-item disabled">
                                        <a href="#!" class="page-link">←</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#!" class="page-link">1</a>
                                    </li>
                                    <li class="page-item active">
                                        <a href="#!" class="page-link">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#!" class="page-link">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#!" class="page-link">→</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
            <div class="col-xl-4">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <img src="<?= "$base/" ?>images/users/avatar-1.jpg" alt=""
                                             class="avatar-sm rounded-circle img-thumbnail">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1 card-title">Anna Adame</h5>
                                                <p class="mb-0 text-muted">Founder</p>
                                            </div>

                                            <div class="flex-shrink-0 dropdown ms-2">
                                                <button class="btn btn-light btn-sm" type="button"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    <i class="bx bxs-cog align-middle me-1"></i> Setting
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#">Action</a>
                                                    <a class="dropdown-item" href="#">Another action</a>
                                                    <a class="dropdown-item" href="#">Something else</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-4">
                                                <div class="border p-2 rounded border-dashed">
                                                    <p class="text-muted text-truncate mb-2">Total Post</p>
                                                    <h5 class="mb-0">26</h5>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border p-2 rounded border-dashed">
                                                    <p class="text-muted text-truncate mb-2">Subscribes</p>
                                                    <h5 class="mb-0">17k</h5>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border p-2 rounded border-dashed">
                                                    <p class="text-muted text-truncate mb-2">Viewers</p>
                                                    <h5 class="mb-0">487k</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h5 class="card-title mb-0 flex-grow-1">Used Device</h5>
                                <div class="flex-shrink-0">
                                    <div class="dropdown card-header-dropdown">
                                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                           aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted fs-16"><i
                                                        class="mdi mdi-dots-vertical align-middle"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Today</a>
                                            <a class="dropdown-item" href="#">Last Week</a>
                                            <a class="dropdown-item" href="#">Last Month</a>
                                            <a class="dropdown-item" href="#">Current Year</a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card header -->
                            <div class="card-body">
                                <div id="gradient_chart" data-colors='["--vz-primary", "--vz-success", "--vz-warning"]'
                                     class="apex-charts" dir="ltr"></div>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end col-->
        </div><!--end row-->

    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->
<!-- apexcharts -->
<?php
$this->registerJsFile("$base/libs/apexcharts/apexcharts.min.js");
$this->registerJsFile("$base/js/pages/dashboard-blog.init.js");
?>
