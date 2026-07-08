<?php

use yii\db\Migration;

class m260708_120449_add_table_gallery extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->createTable('{{%gallery}}', [
         'id' => $this->primaryKey(),
         'page_id' => $this->integer(),
         'image'=> $this->string(),
         'created_at' => $this->integer(),
         'updated_at' => $this->integer(),
         'user_id'=>$this->integer(),
         'status'=>$this->integer(),
      ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%gallery}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260708_120449_add_table_gallery cannot be reverted.\n";

        return false;
    }
    */
}
