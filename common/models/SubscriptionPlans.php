<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscription_plans".
 *
 * @property int $id
 * @property int $course_id
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_uz
 * @property float $price
 * @property int $duration_days
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class SubscriptionPlans extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscription_plans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 0],
            [['name_ru', 'name_en', 'name_uz', 'price', 'duration_days', 'created_at', 'updated_at'], 'required'],
            [['price'], 'number'],
            [['duration_days', 'status', 'created_at', 'updated_at','course_id',], 'integer'],
            [['name_ru', 'name_en', 'name_uz'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'name_uz' => 'Name Uz',
            'price' => 'Price',
            'duration_days' => 'Duration Days',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItems()
    {
        return $this->hasMany(SubscriptionPlanItems::className(), ['plan_id' => 'id']);
    }
   public function getCourse(){
       return $this->hasOne(Courses::className(), ['id' => 'course_id']);
   }
}
