<?php

use yii\db\Migration;

class m260521_111143_create_table_posts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post_category}}', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string()->notNull(),
            'name_en' => $this->string()->notNull(),
            'name_uz' => $this->string()->notNull(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1),
        ]);
        $this->createTable('{{%posts}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'slug' => $this->string()->notNull(),
            'name_ru' => $this->string()->notNull(),
            'name_en' => $this->string()->notNull(),
            'name_uz' => $this->string()->notNull(),
            'desc_ru' => $this->text()->notNull(),
            'desc_en' => $this->text()->notNull(),
            'desc_uz' => $this->text()->notNull(),
            'content_ru' => $this->text()->notNull(),
            'content_en' => $this->text()->notNull(),
            'content_uz' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1),
            'user_id' => $this->integer()->notNull(),
            'image' => $this->string()->notNull(),
        ]);
        $this->createTable('{{%post_images}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'name_ru' => $this->string(),
            'name_en' => $this->string()->notNull(),
            'name_uz' => $this->string(),
            'image' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%post_images}}');
        $this->dropTable('{{%posts}}');
        $this->dropTable('{{%post_category}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260521_111143_create_table_posts cannot be reverted.\n";

        return false;
    }
    */
}
