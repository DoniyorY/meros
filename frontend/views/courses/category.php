<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\CourseCategory $category */
/** @var common\models\Courses[] $courses */

$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;
$localized = static function ($model, $attribute) use ($lang) {
   $localizedAttribute = $attribute . '_' . $lang;
   $englishAttribute = $attribute . '_en';

   return $model->{$localizedAttribute} ?: $model->{$englishAttribute};
};
$categoryName = $localized($category, 'name');
$categoryDescription = $localized($category, 'desc');
$courseCount = count($courses);
$levels = array_values(array_unique(array_filter(array_map(static function ($course) {
   return trim((string)$course->lvl);
}, $courses))));

$this->title = $categoryName;
$this->registerMetaTag([
   'name' => 'description',
   'content' => mb_substr(trim(strip_tags((string)$categoryDescription)), 0, 160),
]);
$this->params['hideBreadcrumbs'] = true;
?>

<section class="meros-category-hero" aria-labelledby="category-title">
   <div class="container">
      <div class="meros-category-hero__content">
         <span class="meros-kicker"><?= Html::encode(Yii::t('app', 'Course category')) ?></span>
         <h1 id="category-title"><?= Html::encode($categoryName) ?></h1>
         <p><?= Html::encode(Yii::t('app', 'Practical learning programmes created for confident professional communication.')) ?></p>
         <dl class="meros-category-facts">
            <div>
               <dt><?= Html::encode(Yii::t('app', 'Courses')) ?></dt>
               <dd><?= $courseCount ?></dd>
            </div>
            <div>
               <dt><?= Html::encode(Yii::t('app', 'Levels')) ?></dt>
               <dd><?= Html::encode($levels ? implode(' · ', $levels) : Yii::t('app', 'For all levels')) ?></dd>
            </div>
            <div>
               <dt><?= Html::encode(Yii::t('app', 'Format')) ?></dt>
               <dd><?= Html::encode(Yii::t('app', 'Online')) ?></dd>
            </div>
         </dl>
      </div>
   </div>
</section>

<main id="page-content" class="meros-modern-page meros-category-page">
   <section class="meros-section meros-category-intro reveal-section" aria-labelledby="category-about-title">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
               <span class="meros-kicker"><?= Html::encode(Yii::t('app', 'About the category')) ?></span>
               <h2 id="category-about-title"><?= Html::encode($categoryName) ?></h2>
               <?php if (trim(strip_tags((string)$categoryDescription)) !== ''): ?>
                  <div class="meros-category-intro__text">
                     <?= Html::purifier($categoryDescription) ?>
                  </div>
               <?php else: ?>
                  <p class="meros-category-intro__text"><?= Html::encode(Yii::t('app', 'Choose a course that matches your goals and current level.')) ?></p>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </section>

   <section class="meros-section meros-category-courses reveal-section" aria-labelledby="category-courses-title">
      <div class="container">
         <div class="meros-section-heading">
            <span class="meros-kicker"><?= Html::encode(Yii::t('app', 'Learning programmes')) ?></span>
            <h2 id="category-courses-title"><?= Html::encode(Yii::t('app', 'Courses in this category')) ?></h2>
         </div>

         <?php if ($courses): ?>
            <div class="row g-4">
               <?php foreach ($courses as $course):
                  $courseName = $localized($course, 'name');
                  $courseDescription = trim(strip_tags((string)$localized($course, 'desc')));
                  $courseImage = $course->course_image
                     ? "$base/uploads/courses/courseImage/$course->course_image"
                     : "$base/images/med_institute.jpg";
                  $courseUrl = Url::to(['courses/index', 'category' => $category->slug, 'slug' => $course->slug]);
               ?>
                  <div class="col-lg-4 col-md-6">
                     <article class="meros-category-card h-100">
                        <a class="meros-category-card__image" href="<?= Html::encode($courseUrl) ?>">
                           <img src="<?= Html::encode($courseImage) ?>" alt="<?= Html::encode($courseName) ?>" loading="lazy">
                           <?php if ($course->lvl): ?>
                              <span><?= Html::encode($course->lvl) ?></span>
                           <?php endif; ?>
                        </a>
                        <div class="meros-category-card__body">
                           <span class="meros-category-card__category"><?= Html::encode($categoryName) ?></span>
                           <h3><a href="<?= Html::encode($courseUrl) ?>"><?= Html::encode($courseName) ?></a></h3>
                           <?php if ($courseDescription !== '' && $courseDescription !== '-'): ?>
                              <p><?= Html::encode(mb_strlen($courseDescription) > 180 ? mb_substr($courseDescription, 0, 177) . '...' : $courseDescription) ?></p>
                           <?php endif; ?>
                           <a class="meros-category-card__link" href="<?= Html::encode($courseUrl) ?>">
                              <?= Html::encode(Yii::t('app', 'View course')) ?>
                              <i class="bi bi-arrow-right" aria-hidden="true"></i>
                           </a>
                        </div>
                     </article>
                  </div>
               <?php endforeach; ?>
            </div>
         <?php else: ?>
            <div class="meros-category-empty text-center">
               <i class="bi bi-journal-bookmark" aria-hidden="true"></i>
               <h3><?= Html::encode(Yii::t('app', 'New courses are coming soon')) ?></h3>
               <p><?= Html::encode(Yii::t('app', 'We are preparing new learning programmes for this category.')) ?></p>
            </div>
         <?php endif; ?>
      </div>
   </section>
</main>
