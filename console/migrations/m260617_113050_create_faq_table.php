<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%faq}}`.
 */
class m260617_113050_create_faq_table extends Migration
{
   /**
    * {@inheritdoc}
    */
   public function safeUp()
   {
      $this->createTable('{{%faq}}', [
         'id' => $this->primaryKey(),
         'course_id' => $this->integer()->notNull(),
         'page_id' => $this->integer(),
         'question_ru' => $this->string()->notNull(),
         'question_en' => $this->string()->notNull(),
         'question_uz' => $this->string()->notNull(),
         'answer_ru' => $this->text()->notNull(),
         'answer_en' => $this->text()->notNull(),
         'answer_uz' => $this->text()->notNull(),
         'created_at' => $this->integer()->notNull(),
         'updated_at' => $this->integer()->notNull(),
         'user_id' => $this->integer()->notNull(),
      ]);
   }
   
   /**
    * {@inheritdoc}
    */
   public function safeDown()
   {
      $this->dropTable('{{%faq}}');
   }
}
