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
            ['course_id', 'exist', 'targetClass' => Courses::class, 'targetAttribute' => ['course_id' => 'id']],
            ['page_id', 'in', 'range' => array_keys(Yii::$app->params['faq_page_id']), 'skipOnEmpty' => true],
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
            'course_id' => 'Course',
            'page_id' => 'Page',
            'question_ru' => 'Question',
            'question_en' => 'Question',
            'question_uz' => 'Question',
            'answer_ru' => 'Answer',
            'answer_en' => 'Answer',
            'answer_uz' => 'Answer',
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
