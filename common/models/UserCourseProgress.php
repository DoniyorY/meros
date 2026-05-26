<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_course_progress".
 *
 * @property int $id
 * @property int $user_id
 * @property int $lesson_id
 * @property int|null $is_completed
 * @property int|null $watched_seconds
 * @property int $updated_at
 */
class UserCourseProgress extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_course_progress';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['watched_seconds'], 'default', 'value' => 0],
            [['user_id', 'lesson_id', 'updated_at'], 'required'],
            [['user_id', 'lesson_id', 'is_completed', 'watched_seconds', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'lesson_id' => 'Lesson ID',
            'is_completed' => 'Is Completed',
            'watched_seconds' => 'Watched Seconds',
            'updated_at' => 'Updated At',
        ];
    }

}
