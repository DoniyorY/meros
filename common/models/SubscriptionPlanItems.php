<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscription_plan_items".
 *
 * @property int $id
 * @property int $plan_id
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_uz
 * @property string $desc_ru
 * @property string $desc_en
 * @property string $desc_uz
 */
class SubscriptionPlanItems extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscription_plan_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_id', 'name_en', 'desc_en',], 'required'],
            [['plan_id'], 'integer'],
            [['name_ru', 'name_en', 'name_uz', 'desc_ru', 'desc_en', 'desc_uz'], 'string', 'max' => 255],
            ['plan_id', 'exist', 'targetClass' => SubscriptionPlans::class, 'targetAttribute' => ['plan_id' => 'id']],
           [['name_ru','name_uz','desc_ru','desc_uz'],'default','value'=>'-']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_id' => 'Subscription plan',
            'name_ru' => 'Facility name',
            'name_en' => 'Facility name',
            'name_uz' => 'Facility name',
            'desc_ru' => 'Description',
            'desc_en' => 'Description',
            'desc_uz' => 'Description',
        ];
    }

    public function getPlan()
    {
        return $this->hasOne(SubscriptionPlans::className(), ['id' => 'plan_id']);
    }
}
