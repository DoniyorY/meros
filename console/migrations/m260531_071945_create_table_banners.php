<?php

use yii\db\Migration;

class m260531_071945_create_table_banners extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('banner', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string()->notNull(),
            'name_en' => $this->string()->notNull(),
            'name_uz' => $this->string()->notNull(),
            'desc_ru' => $this->string()->notNull(),
            'desc_en' => $this->string()->notNull(),
            'desc_uz' => $this->string()->notNull(),
            'image' => $this->string()->notNull(),
            'link' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('banner');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260531_071945_create_table_banners cannot be reverted.\n";

        return false;
    }
    */
}
