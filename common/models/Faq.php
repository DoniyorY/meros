<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property int $id
 * @property int $course_id
 * @property int|null $page_id
 * @property string $question_ru
 * @property string $question_en
 * @property string $question_uz
 * @property string $answer_ru
 * @property string $answer_en
 * @property string $answer_uz
 * @property int $created_at
 * @property int $updated_at
 * @property int $user_id
 */
class Faq extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id'], 'default', 'value' => null],
            [['course_id',  'question_en', 'answer_en', 'created_at', 'updated_at', 'user_id'], 'required'],
            [['course_id', 'page_id', 'created_at', 'updated_at', 'user_id'], 'integer'],
            [['answer_ru', 'answer_en', 'answer_uz'], 'string'],
            [['question_ru', 'question_en', 'question_uz'], 'string', 'max' => 255],
            [['question_ru','question_en','answer_ru','answer_uz'], 'default', 'value' => '-'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_id' => 'Course ID',
            'page_id' => 'Page ID',
            'question_ru' => 'Question Ru',
            'question_en' => 'Question En',
            'question_uz' => 'Question Uz',
            'answer_ru' => 'Answer Ru',
            'answer_en' => 'Answer En',
            'answer_uz' => 'Answer Uz',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
        ];
    }

    public function getCourse()
    {
        return $this->hasOne(Courses::class,['id' => 'course_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class,['id' => 'user_id']);
    }

}
