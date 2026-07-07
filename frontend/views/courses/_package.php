<?php

use yii\helpers\Html;
use yii\helpers\Url;

$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;

?>

<div class="col-md-6">
    <div class="accordion meros-accordion package-accordion" id="package-accordion">
       <?php foreach ($courses->features as $item): ?>
          <?php
          $featureName = $item->{"name_$lang"};
          $featureDesc = $item->{"desc_$lang"};
          $englishDescription = trim(strip_tags((string)($item->desc_en ?? '')));
          $hasEnglishDescription = $englishDescription !== '' && $englishDescription !== '-';
          ?>
          <?php if ($hasEnglishDescription): ?>
               <div class="accordion-item meros-accordion-item">
                   <h3 class="accordion-header" id="<?= "package-heading-$item->id" ?>">
                       <button class="accordion-button collapsed" type="button"
                               data-bs-toggle="collapse"
                               data-bs-target="#<?= "package-collapse-$item->id" ?>"
                               aria-expanded="false"
                               aria-controls="<?= "package-collapse-$item->id" ?>">
                          <?= Html::encode($featureName) ?>
                       </button>
                   </h3>
                   <div id="<?= "package-collapse-$item->id" ?>" class="accordion-collapse collapse"
                        aria-labelledby="<?= "package-heading-$item->id" ?>"
                        data-bs-parent="#package-accordion">
                       <div class="accordion-body">
                          <?= $featureDesc ?>
                       </div>
                   </div>
               </div>
          <?php else: ?>
               <div class="meros-check-item">
                   <span class="fa fa-check" aria-hidden="true"></span>
                   <span><?= Html::encode($featureName) ?></span>
               </div>
          <?php endif; ?>
       <?php endforeach; ?>
    </div>
</div>
<div class="col-md-6 mt-5">
    <img src="<?= "$base/images/images_for_doctors.png" ?>" alt="<?= Html::encode(translate('english_for_doctors')) ?>"
         class="package-image">
</div>
<div class="col-md-2"></div>
<div class="col-md-8 d-flex justify-content-between">
   <?php if ($courses->syllabus_file): ?>
      <?= Html::a(
         translate('Download Syllabus') . ' <i class="bi bi-download"></i>',
         ['courses/download', 'id' => $courses->id, 'file' => 'syllabus'],
         [
            'class' => 'btn btn-primary meros-primary-btn mt-3 mt-md-0',
            'encode' => false,
            'target' => '_blank'
         ]
      ) ?>
   <?php endif; ?>
   <?php if ($courses->flyer_file): ?>
      <?= Html::a(
         translate('Download Flyer') . ' <i class="bi bi-download"></i>',
         ['courses/download', 'id' => $courses->id, 'file' => 'flyer'],
         [
            'class' => 'btn btn-primary meros-primary-btn mt-3 mt-md-0',
            'encode' => false,
            'target' => '_blank'
         ]
      ) ?>
   <?php endif; ?>
</div>
<div class="col-md-2"></div>