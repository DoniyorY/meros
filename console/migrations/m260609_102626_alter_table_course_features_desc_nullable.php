<?php

use yii\db\Migration;

class m260609_102626_alter_table_course_features_desc_nullable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('course_features', 'desc_en', $this->text());
      $this->alterColumn('course_features', 'desc_ru', $this->text());
      $this->alterColumn('course_features', 'desc_uz', $this->text());
      $this->alterColumn('course_features', 'name_ru', $this->string());
      $this->alterColumn('course_features', 'name_uz', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('course_features', 'desc_en', $this->text()->notNull());
        $this->alterColumn('course_features', 'desc_ru', $this->text()->notNull());
        $this->alterColumn('course_features', 'desc_uz', $this->text()->notNull());
        $this->alterColumn('course_features', 'name_ru', $this->string()->notNull());
        $this->alterColumn('course_features', 'name_uz', $this->string()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260609_102626_alter_table_course_features_desc_nullable cannot be reverted.\n";

        return false;
    }
    */
}
