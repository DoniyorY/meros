<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "subscription_plans".
 *
 * @property int $id
 * @property int $course_id
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_uz
 * @property string $sku_id
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

   public function behaviors()
   {
      return [TimestampBehavior::class];
   }
   
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
         [['course_id', 'name_en', 'price', 'duration_days'], 'required'],
         [['price'], 'number', 'min' => 0],
         [['duration_days'], 'integer', 'min' => 1],
         [['duration_days', 'status', 'created_at', 'updated_at', 'course_id',], 'integer'],
         ['status', 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],
         ['course_id', 'exist', 'targetClass' => Courses::class, 'targetAttribute' => ['course_id' => 'id']],
         [['name_ru', 'name_en', 'name_uz'], 'string', 'max' => 255],
         [['name_ru', 'name_uz'], 'default', 'value' => '-']
      ];
   }
   
   /**
    * {@inheritdoc}
    */
   public function attributeLabels()
   {
      return [
         'id' => 'ID',
         'course_id' => 'Course',
         'name_ru' => 'Plan name',
         'name_en' => 'Plan name',
         'name_uz' => 'Plan name',
         'price' => 'Price',
         'duration_days' => 'Access duration (days)',
         'status' => 'Status',
         'created_at' => 'Created At',
         'updated_at' => 'Updated At',
      ];
   }
   
   public function getItems()
   {
      return $this->hasMany(SubscriptionPlanItems::className(), ['plan_id' => 'id']);
   }
   
   public function getCourse()
   {
      return $this->hasOne(Courses::className(), ['id' => 'course_id']);
   }
   
   public function getCourseName()
   {
      return $this->course->name_en;
   }
}
