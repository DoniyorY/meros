<?php

use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\bootstrap5\LinkPager;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\User[] $customers */
/** @var yii\data\Pagination $customerPagination */
/** @var string $customerSort */
$base = Yii::$app->request->baseUrl;

$this->title = 'Meros Admin Panel';
$visitChartUrl = Url::to(['site/visit-chart']);
$host = $_SERVER['HTTP_HOST'];
$customerSortLabels = [
    'subscribed' => 'Subscribed',
    'newest' => 'Newest',
    'oldest' => 'Oldest',
];
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
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Course
                                            Count</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                                                              data-target="<?= $course_count ?>"><?= $course_count ?></span>
                                        </h4>
                                        <a href="<?= Url::to(['courses/index']) ?>" class="text-decoration-underline">All
                                            Courses</a>
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
                                        <span class="fw-semibold text-uppercase fs-12">Sort by:</span>
                                        <span class="text-muted">
                                            <span id="visitChartPeriodLabel">Current Week</span>
                                            <i class="mdi mdi-chevron-down ms-1"></i>
                                        </span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item visit-chart-filter" href="#" data-period="today">
                                            Today
                                        </a>
                                        <a class="dropdown-item visit-chart-filter active" href="#"
                                           data-period="current_week">
                                            Current Week
                                        </a>

                                        <a class="dropdown-item visit-chart-filter" href="#" data-period="last_week">
                                            Last Week
                                        </a>

                                        <a class="dropdown-item visit-chart-filter" href="#" data-period="last_month">
                                            Last Month
                                        </a>

                                        <a class="dropdown-item visit-chart-filter" href="#" data-period="current_year">
                                            Current Year
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                            <a href="<?= Url::to(['contacts/index']) ?>" class="btn btn-soft-primary btn-sm">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div data-simplebar class="mx-n3 px-3" style="height: 440px;">
                            <div class="vstack gap-3">
                               <?php foreach ($contacts as $item): ?>

                                   <div class="d-flex gap-3">
                                       <img src="<?= "$base/" ?>images/users/user-dummy-img.jpg" alt=""
                                            class="avatar-sm rounded flex-shrink-0">
                                       <div class="flex-shrink-1">
                                           <a href="<?= Url::to(['contacts/view', 'id' => $item->id]) ?>">
                                               <h6 class="mb-2"><?= $item->fullname ?> <span
                                                           class="text-muted"><?= date('d.m.Y H:i:s', $item->created_at) ?></span>
                                               </h6>
                                           </a>
                                           <p class="text-muted mb-0">" <?= $item->subject ?> "</p>
                                       </div>
                                   </div>
                               <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <?php Pjax::begin([
                    'id' => 'customers-pjax',
                    'timeout' => 10000,
                ]); ?>
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Customers</h4>
                        <div class="flex-shrink-0">
                            <div class="dropdown card-header-dropdown">
                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                   data-pjax="0" aria-haspopup="true" aria-expanded="false">
                                    <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span
                                            class="text-muted"><?= Html::encode($customerSortLabels[$customerSort]) ?> <i class="mdi mdi-chevron-down ms-1"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <?php foreach ($customerSortLabels as $sort => $label): ?>
                                        <a class="dropdown-item<?= $customerSort === $sort ? ' active' : '' ?>"
                                           href="<?= Url::current(['customerSort' => $sort, 'customerPage' => null]) ?>">
                                            <?= Html::encode($label) ?>
                                        </a>
                                    <?php endforeach; ?>
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
                                    <th scope="col">Username</th>
                                    <th scope="col">Fullname</th>
                                    <th scope="col">Created at</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($customers as $index => $customer): ?>
                                    <tr>
                                        <td><?= $customerPagination->offset + $index + 1 ?></td>
                                        <td><?= Html::encode($customer->username) ?></td>
                                        <td><?= Html::encode($customer->fullname) ?></td>
                                        <td><?= Yii::$app->formatter->asDatetime($customer->created_at, 'php:d.m.Y H:i:s') ?></td>
                                        <td><?= Html::mailto(Html::encode($customer->email), $customer->email) ?></td>
                                        <td><?= Html::encode($customer->phone) ?></td>
                                        <td>
                                            <?= Html::a('View', ['user/view', 'id' => $customer->id], [
                                                'class' => 'btn btn-soft-primary btn-sm',
                                                'data-pjax' => '0',
                                            ]) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (!$customers): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No customers found.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody><!-- end tbody -->
                            </table><!-- end table -->
                        </div>
                        <div class="align-items-center mt-3 row g-3 text-center text-sm-start">
                            <div class="col-sm">
                                <div class="text-muted">Showing <span class="fw-semibold"><?= count($customers) ?></span> of <span
                                            class="fw-semibold"><?= $customerPagination->totalCount ?></span> Results
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <?= LinkPager::widget([
                                    'pagination' => $customerPagination,
                                    'options' => [
                                        'class' => 'pagination pagination-separated pagination-sm justify-content-center justify-content-sm-start mb-0',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php Pjax::end(); ?>
            </div><!--end col-->
            <div class="col-xxl-4">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">News Feed</h4>
                        <div>
                            <a href="<?=Url::to(['posts/index'])?>" class="btn btn-soft-primary btn-sm">
                                View all
                            </a>
                        </div>
                    </div><!-- end card-header -->

                    <div class="card-body">
                        <?php $i=0; foreach ($posts as $post):?>
                        <div class="d-flex <?=($i==0)?"align-middle":"mt-4"?>">
                            <div class="flex-shrink-0">
                                <img src="<?= "/uploads/posts/$post->image" ?>" class="rounded img-fluid"
                                     style="height: 60px;" alt="">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 lh-base">
                                    <a href="<?=Url::to(['posts/view','id'=>$post->id])?>" class="text-reset"><?=$post->name_en?></a>
                                </h6>
                                <p class="text-muted fs-12 mb-0"><?=date('d.m.Y',$post->created_at)?> <i
                                            class="mdi mdi-circle-medium align-middle mx-1"></i><?=date('H:i',$post->created_at)?></p>
                            </div>
                        </div><!-- end -->
                        <?php $i++; endforeach;?>
                        <div class="mt-3 text-center">
                            <a href="<?=Url::to(['posts/index'])?>" class="text-muted text-decoration-underline">View all News</a>
                        </div>

                    </div><!-- end card body -->
                </div><!-- end card -->
            </div>
        </div><!--end row-->

    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->
<!-- apexcharts -->
<?php
$this->registerJsFile("$base/libs/apexcharts/apexcharts.min.js");
$this->registerJsFile("$base/js/site.js");
?>
