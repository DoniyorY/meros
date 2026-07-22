<?php
/**
 * @var common\models\User $model
 */
?>

<table class="table table-sm table-bordered table-striped table-hover">
    <thead>
    <tr>
        <th> #</th>
        <th>Course Name</th>
        <th>Plan</th>
        <th>Created At</th>
        <th>Period</th>
        <th>Amount</th>
        <th>Payment Provider</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 1;
    foreach ($model->subscriptions as $item): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= $item->plan->course->name_en ?? "Not Set" ?></td>
            <td><?= $item->plan->name_en ?? 'Not Set' ?></td>
            <td><?= date('d.m.Y', $item->created_at) ?></td>
            <td>
               <?php $start = date('d.m.Y', $item->start_date);
               $end = date('d.m.Y', $item->expires_date);
               echo "$start - $end";
               ?>
            </td>
            <td>
               <?= Yii::$app->formatter->asDecimal($item->amount) ?>
            </td>
            <td>
                <?=$item->paymentProvider->name ?? "Not Set"?>
            </td>
            <td>
               <?= Yii::$app->params['status'][$item->status] ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>