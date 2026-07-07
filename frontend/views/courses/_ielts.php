<?php

use yii\helpers\Html;

$base = Yii::$app->request->baseUrl;

?>

<div class="col-md-6 mt-5">
   <img src="<?= "$base/images/images_for_doctors.png" ?>" alt="<?= Html::encode(translate('english_for_doctors')) ?>"
        class="package-image">
</div>
<div class="col-md-6">
   <h2> <?=translate('Unlock your IELTS success now!')?></h2>
    <p>
        <?=translate('This online course teaches you the language, the techniques and the strategies you need to maximise your score.')?>
    </p>
    <p>
        <?=translate('It includes 75 hours of study and 2 full, timed practice tests.')?>
    </p>
    <p>
        <strong><?=translate('Optimised for all devices, and available offline, so giving you maximum convenience to study when you want and where you want.')?></strong>
    </p>
    <div class="mt-3">
        
        <?php if ($courses->syllabus_file):?>
            <a href="<?="https://merosedu.uz/uploads/course_docs/$courses->syllabus_file"?>" class="btn btn-primary meros-primary-btn" download="<?="https://merosedu.uz/uploads/course_docs/$courses->syllabus_file"?>">
               <?=translate('Download Syllabus')?> <i class="bi bi-download"></i>
            </a>
        <?php endif;?>
    </div>
</div>