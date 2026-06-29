<?php

use yii\db\Migration;

class m260629_101134_add_readmore_table_to_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->createTable('read_more', [
            'id'=>$this->primaryKey(),
            'course_id'=>$this->integer()->notNull(),
            'title_en'=>$this->string()->notNull(),
            'title_ru'=>$this->string()->notNull(),
            'title_uz'=>$this->string()->notNull(),
            'content_en'=>$this->text()->notNull(),
            'content_ru'=>$this->text()->notNull(),
            'content_uz'=>$this->text()->notNull(),
         ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('read_more');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260629_101134_add_readmore_table_to_courses cannot be reverted.\n";

        return false;
    }
    */
}
