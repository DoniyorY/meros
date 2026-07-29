<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%site_visit}}`.
 */
class m260729_121141_create_site_visit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
   public function safeUp()
   {
      $this->createTable('{{%site_visit}}', [
         'id' => $this->bigPrimaryKey()->unsigned(),
         'user_id' => $this->integer()->null(),
         'session_id' => $this->string(128)->null(),
         'ip_address' => $this->string(45)->null(),
         'user_agent' => $this->text()->null(),
         'url' => $this->string(1000)->null(),
         'referrer' => $this->string(1000)->null(),
         'visited_at' => $this->integer()->notNull(),
      ]);
      
      $this->createIndex(
         'idx-site_visit-visited_at',
         '{{%site_visit}}',
         'visited_at'
      );
      
      $this->createIndex(
         'idx-site_visit-session_id',
         '{{%site_visit}}',
         'session_id'
      );
      
      $this->createIndex(
         'idx-site_visit-user_id',
         '{{%site_visit}}',
         'user_id'
      );
   }
   
   public function safeDown()
   {
      $this->dropTable('{{%site_visit}}');
   }
}
