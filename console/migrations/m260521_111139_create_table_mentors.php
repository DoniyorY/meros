<?php

use yii\db\Migration;

class m260521_111139_create_table_mentors extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mentors}}', [
            'id' => $this->primaryKey(),
            'fullname'=>$this->string()->notNull(),
            'email'=>$this->string(),
            'phone'=>$this->string(),
            'image'=>$this->string(),
            'status'=>$this->integer(),
            'created_at'=>$this->integer()->notNull(),
            'updated_at'=>$this->integer()->notNull(),
            'user_id'=>$this->integer()->notNull(),
            'instagram_link'=>$this->string(),
            'linked_in_link'=>$this->string(),
            'facebook_link'=>$this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropTable('{{%mentors}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260521_111139_create_table_mentors cannot be reverted.\n";

        return false;
    }
    */
}
