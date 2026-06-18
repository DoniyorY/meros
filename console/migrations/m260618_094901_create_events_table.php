<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%events}}`.
 */
class m260618_094901_create_events_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%events}}', [
            'id' => $this->primaryKey(),
           'name_ru' => $this->string(),
           'name_en' => $this->string()->notNull(),
           'name_uz' => $this->string(),
           'desc_ru' => $this->string(),
           'desc_en' => $this->string()->notNull(),
           'desc_uz' => $this->string(),
           'content_ru'=>$this->text(),
           'content_en'=>$this->text()->notNull(),
           'content_uz'=>$this->text(),
           'image'=>$this->string(),
           'created_at'=>$this->integer()->notNull(),
           'updated_at'=>$this->integer()->notNull(),
           'user_id'=>$this->integer()->notNull(),
           'status'=>$this->integer()->notNull(),
           'video_link'=>$this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%events}}');
    }
}
