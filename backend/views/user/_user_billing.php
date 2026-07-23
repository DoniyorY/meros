<?php
/**
*
 * @var common\models\User $model
 */
use yii\helpers\Url;
use yii\helpers\Html;
$base = Yii::$app->request->baseUrl;
$params = Yii::$app->params;

?>

<h4>Billings</h4>
<hr>

<table class="table table-sm table-striped table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>Course Name</th>
        <th>Subscription Name</th>
        <th>Start Date</th>
        <th>Expires Date</th>
        <th>Transaction ID</th>
        <th>Provider</th>
        <th>Payment Status</th>
        <th>Amount</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1; foreach ($model->billing as $item):?>
    <tr>
        <td><?=$i++?></td>
        <td><?=$item->subscription->course->name_en ?? "Not Set!!!"?></td>
        <td><?=$item->subscription->name_en ?? "Not Set!!!"?></td>
        <td><?=date('d.m.Y H:i:s',$item->start_date)?></td>
        <td><?=date('d.m.Y H:i:s',$item->expires_date)?></td>
        <td><?=$item->payment_transaction_id?></td>
        <td><?=$params['telegramStaffPaymentMethodMap'][$item->payment_provider] ?? "Not Set!!!"?></td>
        <td><?=$item->payment_status?></td>
        <td><?=Yii::$app->formatter->asDecimal($item->amount)?></td>
        <td>
            <span class="<?=$params['billing_status_class'][$item->status]?>"><?=$params['billing_status']['en'][$item->status]?></span>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>

