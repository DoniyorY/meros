<?php

use yii\db\Migration;

final class m260630_000001_add_telegram_fields_and_delivery extends Migration
{
   public function safeUp(): void
   {
      $this->addColumn('{{%user}}', 'telegram_chat_id', $this->bigInteger()->null());
      $this->addColumn('{{%user}}', 'telegram_username', $this->string(255)->null());
      $this->addColumn('{{%user}}', 'telegram_language', $this->string(5)->null());
      $this->addColumn('{{%user}}', 'telegram_connected_at', $this->integer()->null());
      $this->addColumn('{{%user}}', 'telegram_link_token_hash', $this->char(64)->null());
      $this->addColumn('{{%user}}', 'telegram_link_expires_at', $this->integer()->null());

      $this->createIndex(
         'ux-user-telegram_chat_id',
         '{{%user}}',
         'telegram_chat_id',
         true
      );
      $this->createIndex(
         'ux-user-telegram_link_token_hash',
         '{{%user}}',
         'telegram_link_token_hash',
         true
      );

      $this->createTable('{{%telegram_delivery}}', [
         'id' => $this->primaryKey(),
         'event_id' => $this->string(191)->notNull(),
         'chat_id' => $this->string(32)->notNull(),
         'status' => $this->tinyInteger()->notNull()->defaultValue(0),
         'attempts' => $this->integer()->notNull()->defaultValue(0),
         'response' => $this->text()->null(),
         'error' => $this->text()->null(),
         'created_at' => $this->integer()->notNull(),
         'updated_at' => $this->integer()->notNull(),
      ]);

      $this->createIndex(
         'ux-telegram_delivery-event_id',
         '{{%telegram_delivery}}',
         'event_id',
         true
      );
      $this->createIndex(
         'ix-telegram_delivery-status',
         '{{%telegram_delivery}}',
         'status'
      );
   }

   public function safeDown(): void
   {
      $this->dropTable('{{%telegram_delivery}}');

      $this->dropIndex('ux-user-telegram_link_token_hash', '{{%user}}');
      $this->dropIndex('ux-user-telegram_chat_id', '{{%user}}');

      $this->dropColumn('{{%user}}', 'telegram_link_expires_at');
      $this->dropColumn('{{%user}}', 'telegram_link_token_hash');
      $this->dropColumn('{{%user}}', 'telegram_connected_at');
      $this->dropColumn('{{%user}}', 'telegram_language');
      $this->dropColumn('{{%user}}', 'telegram_username');
      $this->dropColumn('{{%user}}', 'telegram_chat_id');
   }
}
