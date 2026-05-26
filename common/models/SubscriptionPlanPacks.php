<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscription_plan_packs".
 *
 * @property int $id
 * @property int $plan_id
 * @property int $pack_id
 */
class SubscriptionPlanPacks extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscription_plan_packs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_id', 'pack_id'], 'required'],
            [['plan_id', 'pack_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_id' => 'Plan ID',
            'pack_id' => 'Pack ID',
        ];
    }

    public function getPlan()
    {
        return $this->hasOne(SubscriptionPlans::className(), ['id' => 'plan_id']);
    }

    public function getPack()
    {
        return $this->hasOne(CoursePacks::className(), ['id' => 'pack_id']);
    }

}
