<?php
$host = $_SERVER['HTTP_HOST'];
?>

<div class="row col-md-12">
    <video width="700" height="500" controls>
        <source src="<?="http://$host/uploads/lessons/$model->video_link"?>" type="video/mp4">
        Ваш браузер не поддерживает видео.
    </video>
</div>
