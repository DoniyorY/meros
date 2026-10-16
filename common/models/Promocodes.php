<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "promocodes".
 *
 * @property int $id
 * @property string $code
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 * @property int|null $user_id
 * @property int|null $discount
 */
class Promocodes extends \yii\db\ActiveRecord
{
    public const STATUS_PENDING = 0;
    public const STATUS_USED = 1;
    public const STATUS_EXPIRED = 2;

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'В ожидании',
            self::STATUS_USED => 'Использовано',
            self::STATUS_EXPIRED => 'Просрочено',
        ];
    }

    public function getStatusLabel(): string
    {
        return self::statuses()[$this->status] ?? 'Неизвестно';
    }



    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promocodes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'default', 'value' => null],
            ['status', 'default', 'value' => self::STATUS_PENDING],
            [['code', 'discount', 'status'], 'required'],
            ['discount', 'integer', 'min' => 1, 'max' => 99],
            ['status', 'in', 'range' => array_keys(self::statuses())],
            [['created_at', 'updated_at', 'status', 'user_id', 'discount'], 'integer'],
            [['code'], 'string', 'max' => 50],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Промокод',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'status' => 'Статус',
            'user_id' => 'ID пользователя',
            'discount' => 'Скидка, %',
        ];
    }

}
