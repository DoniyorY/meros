<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "course_category".
 *
 * @property int $id
 * @property string $slug
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_uz
 * @property int|null $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $user_id
 */
class CourseCategory extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course_category';
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'name_en',
                'slugAttribute' => 'slug',
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 1],
            [['name_ru', 'name_en', 'name_uz', 'created_at', 'updated_at', 'user_id'], 'required'],
            [['status', 'created_at', 'updated_at', 'user_id'], 'integer'],
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
            'slug' => 'Slug',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'name_uz' => 'Name Uz',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
