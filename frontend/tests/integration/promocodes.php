<?php
/** Run: php frontend/tests/integration/promocodes.php
 * Uses connection-local temporary tables; persistent application data is untouched.
 */
error_reporting(E_ALL & ~E_DEPRECATED);
$root = dirname(__DIR__, 3);
require $root . '/vendor/autoload.php';
require $root . '/vendor/yiisoft/yii2/Yii.php';
require $root . '/common/config/bootstrap.php';
$config = require $root . '/common/config/main-local.php';
new yii\console\Application([
    'id' => 'promocode-integration', 'basePath' => $root . '/frontend',
    'params' => ['click' => ['merchant_id'=>'test', 'service_id'=>'test', 'secret_key'=>'test']],
    'components' => ['db' => $config['components']['db']],
]);

use common\models\Billing;
use common\models\Promocodes;
use common\services\PromocodeService;

function verify($condition, string $message): void {
    if (!$condition) throw new RuntimeException($message);
}
function rejects(callable $call, string $class = DomainException::class): void {
    try { $call(); } catch (Throwable $e) {
        if ($e instanceof $class) return;
        throw $e;
    }
    throw new RuntimeException('Expected ' . $class);
}
$db = Yii::$app->db;
$db->open();
$tables = [
    'billing' => 'id INT PRIMARY KEY, user_id INT, subscription_id INT, amount INT, no_discount_amount INT NULL, promocode_id INT NULL, status INT DEFAULT 0, payment_status INT NULL, payment_transaction_id VARCHAR(100) NULL, payment_provider INT NULL, created_at INT, updated_at INT, start_date INT NULL, expires_date INT NULL',
    'promocodes' => 'id INT AUTO_INCREMENT PRIMARY KEY, code VARCHAR(50) UNIQUE, discount INT, status INT DEFAULT 0, user_id INT NULL, created_at INT, updated_at INT',
    'subscription_plans' => 'id INT PRIMARY KEY, duration_days INT',
    'user_subscriptions' => 'id INT AUTO_INCREMENT PRIMARY KEY, subscription_key VARCHAR(100), plan_id INT, user_id INT, status INT, start_date INT, expires_date INT, amount INT, currency_code INT, payment_transaction_id VARCHAR(100), payment_provider VARCHAR(20), created_at INT, updated_at INT',
];
foreach ($tables as $table => $columns) {
    $db->createCommand("CREATE TEMPORARY TABLE $table ($columns) ENGINE=InnoDB")->execute();
}
// Fail before any model writes if its table name does not match a shadow table.
verify(common\models\SubscriptionPlans::tableName() === 'subscription_plans', 'Unexpected plans table');
verify(common\models\UserSubscriptions::tableName() === 'user_subscriptions', 'Unexpected subscriptions table');
$db->createCommand()->insert('subscription_plans', ['id'=>1, 'duration_days'=>30])->execute();
foreach (range(1, 8) as $id) {
    $db->createCommand()->insert('billing', ['id'=>$id,'user_id'=>10,'subscription_id'=>1,'amount'=>10001,'status'=>0,'created_at'=>time(),'updated_at'=>time()])->execute();
}
foreach ([['CODE25',25,0],['CODE50',50,0],['EXPIRED',20,2],['USED',20,1],['FREE',100,0],['CLICK',10,0],['PAYME',15,0],['ROLLBACK',20,0]] as [$code,$discount,$status]) {
    $db->createCommand()->insert('promocodes', compact('code','discount','status') + ['created_at'=>time(),'updated_at'=>time()])->execute();
}
$b = PromocodeService::apply(1,10,' code25 ');
verify((int)$b->amount === 7501 && (int)$b->no_discount_amount === 10001, 'Discount / rounding');
verify((int)PromocodeService::apply(1,10,'CODE25')->amount === 7501, 'Repeated application compounded discount');
verify((int)PromocodeService::apply(1,10,'CODE50')->amount === 5001, 'Replacement compounded discount');
verify((int)Promocodes::findOne(['code'=>'CODE50'])->status === 0, 'Consumed before payment');
rejects(fn()=>PromocodeService::apply(2,10,'CODE50'));
rejects(fn()=>PromocodeService::apply(1,11,'CODE25'), yii\web\NotFoundHttpException::class);
foreach (['MISSING','EXPIRED','USED','FREE'] as $code) rejects(fn()=>PromocodeService::apply(1,10,$code));
verify((int)Billing::findOne(1)->amount === 5001, 'Invalid code changed invoice');
verify((int)PromocodeService::apply(1,10,'')->amount === 10001, 'Removal did not restore original');
PromocodeService::apply(2,10,'CODE50');
Billing::updateAll(['payment_transaction_id'=>'in-progress'], ['id'=>2]);
rejects(fn()=>PromocodeService::apply(2,10,''));
Billing::updateAll(['payment_status'=>1], ['id'=>3]);
rejects(fn()=>PromocodeService::apply(3,10,'CODE25'));

foreach (['click'=>frontend\controllers\PaymentController::class, 'payme'=>frontend\controllers\PaymeController::class] as $provider=>$class) {
    $id = $provider === 'click' ? 4 : 5;
    $b = PromocodeService::apply($id,10,strtoupper($provider));
    $controller = new $class($provider, Yii::$app);
    $success = new ReflectionMethod($class, 'markBillingSuccess');
    $db->transaction(function () use ($success,$controller,$b,$provider) {
        PromocodeService::lockBilling((int)$b->id);
        $success->invoke($controller,$b,'test-'.$provider);
    });
    $promo = Promocodes::findOne($b->promocode_id);
    verify((int)$promo->status === 1 && (int)$promo->user_id === 10, $provider.' did not consume code');
    verify((int)Billing::findOne($id)->payment_status === 1, $provider.' did not mark invoice paid');
    $db->transaction(fn()=>PromocodeService::consume(Billing::findOne($id)));
    rejects(fn()=>PromocodeService::apply(6,10,strtoupper($provider)));
}
$b = PromocodeService::apply(7,10,'ROLLBACK');
rejects(function () use ($db,$b) {
    $db->transaction(function () use ($b) {
        PromocodeService::consume($b);
        throw new RuntimeException('Simulated payment failure');
    });
}, RuntimeException::class);
verify((int)Promocodes::findOne($b->promocode_id)->status === 0, 'Failed payment consumed code');
verify(Promocodes::findOne($b->promocode_id)->user_id === null, 'Failed payment assigned user');
$form = new backend\models\PromocodesCreateForm(['quantity'=>1,'discount'=>100]);
verify(!$form->validate(), 'Admin accepted 100% discount');
echo "OK: pricing, repeat/replace/remove, ownership, invalid/used/expired/100% codes, reservation, payment lock, Click/Payme success, idempotency and rollback.\n";
