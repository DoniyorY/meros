<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "course_pack_items".
 *
 * @property int $id
 * @property int $pack_id
 * @property int $course_category_id
 * @property int $course_id
 */
class CoursePackItems extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course_pack_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['pack_id', 'course_category_id', 'course_id'], 'required'],
            [['pack_id', 'course_category_id', 'course_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pack_id' => 'Pack ID',
            'course_category_id' => 'Course Category ID',
            'course_id' => 'Course ID',
        ];
    }

    public function getPack()
    {
        return $this->hasOne(CoursePacks::class, ['id' => 'pack_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(CourseCategory::class, ['id' => 'course_category_id']);
    }

    public function getCourse()
    {
        return $this->hasOne(Courses::class, ['id' => 'course_id']);
    }
}
