<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Inflector;

/**
 * This is the model class for table "medical_dictionary".
 *
 * @property int $id
 * @property int $category_id
 * @property int|null $type
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_uz
 * @property string $slug_ru
 * @property string $slug_en
 * @property string $slug_uz
 * @property string $desc_ru
 * @property string $desc_en
 * @property string $desc_uz
 * @property string $content_ru
 * @property string $content_en
 * @property string $content_uz
 * @property string|null $seo_title_ru
 * @property string|null $seo_title_en
 * @property string|null $seo_title_uz
 * @property string|null $seo_desc_ru
 * @property string|null $seo_desc_en
 * @property string|null $seo_desc_uz
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 */
class MedicalDictionary extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medical_dictionary';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
   
   public function beforeValidate()
   {
      if (!parent::beforeValidate()) {
         return false;
      }
      
      if (empty($this->slug_ru) && !empty($this->name_ru)) {
         $this->slug_ru = $this->generateSlug($this->name_ru);
      }
      
      if (empty($this->slug_en) && !empty($this->name_en)) {
         $this->slug_en = $this->generateSlug($this->name_en);
      }
      
      if (empty($this->slug_uz) && !empty($this->name_uz)) {
         $this->slug_uz = $this->generateSlug($this->name_uz);
      }
      
      return true;
   }
   
   private function generateSlug(string $value): string
   {
      // Нормализуем разные варианты апострофов,
      // которые часто встречаются в узбекском
      $value = str_replace(
         ['‘', '’', 'ʻ', 'ʼ', '`', '´'],
         "'",
         $value
      );
      
      return Inflector::slug($value);
   }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'seo_title_ru', 'seo_title_en', 'seo_title_uz', 'seo_desc_ru', 'seo_desc_en', 'seo_desc_uz'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 1],
            [['category_id', 'type', 'name_ru', 'name_en', 'name_uz', 'desc_ru', 'desc_en', 'desc_uz', 'content_ru', 'content_en', 'content_uz'], 'required'],
            [['category_id', 'type', 'created_at', 'updated_at', 'status'], 'integer'],
            ['category_id', 'in', 'range' => array_keys(Yii::$app->params['medical_dictionary_categories']['en'])],
            ['type', 'in', 'range' => array_keys(Yii::$app->params['medical_dictionary_types']['en'])],
            ['status', 'in', 'range' => array_keys(Yii::$app->params['status'])],
            [['content_ru', 'content_en', 'content_uz'], 'string'],
            [['name_ru', 'name_en', 'name_uz', 'slug_ru', 'slug_en', 'slug_uz', 'desc_ru', 'desc_en', 'desc_uz', 'seo_title_ru', 'seo_title_en', 'seo_title_uz', 'seo_desc_ru', 'seo_desc_en', 'seo_desc_uz'], 'string', 'max' => 255],
            [['slug_ru'], 'unique'],
            [['slug_en'], 'unique'],
            [['slug_uz'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category',
            'type' => 'Term type',
            'name_ru' => 'Term',
            'name_en' => 'Term',
            'name_uz' => 'Term',
            'slug_ru' => 'Slug Ru',
            'slug_en' => 'Slug En',
            'slug_uz' => 'Slug Uz',
            'desc_ru' => 'Short description',
            'desc_en' => 'Short description',
            'desc_uz' => 'Short description',
            'content_ru' => 'Article content',
            'content_en' => 'Article content',
            'content_uz' => 'Article content',
            'seo_title_ru' => 'SEO title',
            'seo_title_en' => 'SEO title',
            'seo_title_uz' => 'SEO title',
            'seo_desc_ru' => 'SEO description',
            'seo_desc_en' => 'SEO description',
            'seo_desc_uz' => 'SEO description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

}
