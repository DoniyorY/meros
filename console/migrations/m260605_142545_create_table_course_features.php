<?php

use yii\db\Migration;

class m260605_142545_create_table_course_features extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->createTable('course_features', [
            'id' => $this->primaryKey(),
            'course_id'=> $this->integer()->notNull(),
            'name_en'=>$this->string()->notNull(),
            'name_ru'=>$this->string()->notNull(),
            'name_uz'=>$this->string()->notNull(),
            'desc_en'=>$this->text()->notNull(),
            'desc_ru'=>$this->text()->notNull(),
            'desc_uz'=>$this->text()->notNull(),
         ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropTable('course_features');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260605_142545_create_table_course_features cannot be reverted.\n";

        return false;
    }
    */
}
