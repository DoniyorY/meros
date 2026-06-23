<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;

/**
 * ActiveRecord for table "payme_log".
 *
 * @property int $id
 * @property int|null $rpc_id
 * @property string|null $method
 * @property string $request_body
 * @property string $response_body
 * @property int $authorization_ok
 * @property string|null $ip
 * @property int $duration_ms
 * @property int $created_at
 */
final class PaymeLog extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%payme_log}}';
    }

    public function rules(): array
    {
        return [
            [['request_body', 'response_body', 'duration_ms', 'created_at'], 'required'],
            [['rpc_id', 'authorization_ok', 'duration_ms', 'created_at'], 'integer'],
            [['request_body', 'response_body'], 'string'],
            [['method'], 'string', 'max' => 64],
            [['ip'], 'string', 'max' => 45],
        ];
    }
}
