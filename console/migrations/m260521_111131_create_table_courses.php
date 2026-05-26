<?php

use yii\db\Migration;

class m260521_111131_create_table_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%course_category}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string()->notNull(),
            'name_ru' => $this->string()->notNull(),
            'name_en' => $this->string()->notNull(),
            'name_uz' => $this->string()->notNull(),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);
        $this->createTable('{{%courses}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'slug' => $this->string()->notNull(),
            'name_ru' => $this->string()->notNull(),
            'name_en' => $this->string()->notNull(),
            'name_uz' => $this->string()->notNull(),
            'desc_ru' => $this->text()->notNull(),
            'desc_en' => $this->text()->notNull(),
            'desc_uz' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'status' => $this->integer()->defaultValue(1),
            'user_id' => $this->integer()->notNull(),
            'mentor_id' => $this->integer()->notNull(),
            'preview_video_link' => $this->string(),
        ]);
        $this->createTable('{{%course_lessons}}', [
            'id' => $this->primaryKey(),
            'course_id' => $this->integer()->notNull(),
            'slug' => $this->string()->notNull(),
            'name_ru' => $this->string()->notNull(),
            'name_en' => $this->string()->notNull(),
            'name_uz' => $this->string()->notNull(),
            'desc_ru' => $this->text()->notNull(),
            'desc_en' => $this->text()->notNull(),
            'desc_uz' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'status' => $this->integer()->defaultValue(1),
            'user_id' => $this->integer()->notNull(),
            'sort' => $this->integer()->notNull(),
        ]);
        $this->createTable('{{%course_packs}}', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string()->notNull(),
            'name_en' => $this->string()->notNull(),
            'name_uz' => $this->string()->notNull(),
            'status' => $this->tinyInteger()->defaultValue(1),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->createTable('course_pack_items', [
            'id' => $this->primaryKey(),
            'pack_id' => $this->integer()->notNull(),
            'course_category_id' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%course_pack_items}}');
        $this->dropTable('{{%course_packs}}');
        $this->dropTable('{{%course_lessons}}');
        $this->dropTable('{{%courses}}');
        $this->dropTable('{{%course_category}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260521_111131_create_table_courses cannot be reverted.\n";

        return false;
    }
    */
}
