<?php

use yii\db\Migration;

final class m260703_000005_make_staff_bot_independent extends Migration
{
   public function safeUp(): void
   {
      $table = '{{%user}}';
      $schema = $this->db->schema->getTableSchema($table, true);

      if ($schema === null) {
         throw new RuntimeException('The user table was not found.');
      }

      $this->addColumnIfMissing(
         $table,
         'staff_telegram_id',
         $this->bigInteger()->null()
      );
      $this->addColumnIfMissing(
         $table,
         'staff_telegram_chat_id',
         $this->bigInteger()->null()
      );
      $this->addColumnIfMissing(
         $table,
         'staff_telegram_username',
         $this->string(255)->null()
      );
      $this->addColumnIfMissing(
         $table,
         'staff_telegram_connected_at',
         $this->integer()->null()
      );
      $this->addColumnIfMissing(
         $table,
         'staff_telegram_bind_token_hash',
         $this->string(64)->null()
      );
      $this->addColumnIfMissing(
         $table,
         'staff_telegram_bind_expires_at',
         $this->integer()->null()
      );

      $this->createIndexIfMissing(
         'ux-user-staff_telegram_id',
         $table,
         'staff_telegram_id',
         true
      );
      $this->createIndexIfMissing(
         'ux-user-staff_telegram_chat_id',
         $table,
         'staff_telegram_chat_id',
         true
      );
      $this->createIndexIfMissing(
         'ux-user-staff_telegram_bind_token_hash',
         $table,
         'staff_telegram_bind_token_hash',
         true
      );

      // Если v2 уже был установлен, переносим существующие значения в новые поля.
      $schema = $this->db->schema->getTableSchema($table, true);
      $map = [
         'telegram_staff_user_id' => 'staff_telegram_id',
         'telegram_staff_chat_id' => 'staff_telegram_chat_id',
         'telegram_staff_username' => 'staff_telegram_username',
         'telegram_staff_connected_at' => 'staff_telegram_connected_at',
      ];

      foreach ($map as $old => $new) {
         if (isset($schema->columns[$old])) {
            $this->update(
               $table,
               [$new => new \yii\db\Expression("[[{$old}]]")],
               [
                  'and',
                  ['not', [$old => null]],
                  [$new => null],
               ]
            );
         }
      }
   }

   public function safeDown(): void
   {
      $table = '{{%user}}';
      $schema = $this->db->schema->getTableSchema($table, true);
      if ($schema === null) {
         return;
      }

      $this->dropIndexIfExists(
         'ux-user-staff_telegram_bind_token_hash',
         $table
      );
      $this->dropIndexIfExists('ux-user-staff_telegram_chat_id', $table);
      $this->dropIndexIfExists('ux-user-staff_telegram_id', $table);

      foreach ([
         'staff_telegram_bind_expires_at',
         'staff_telegram_bind_token_hash',
         'staff_telegram_connected_at',
         'staff_telegram_username',
         'staff_telegram_chat_id',
         'staff_telegram_id',
      ] as $column) {
         $schema = $this->db->schema->getTableSchema($table, true);
         if ($schema !== null && isset($schema->columns[$column])) {
            $this->dropColumn($table, $column);
         }
      }
   }

   private function addColumnIfMissing(
      string $table,
      string $column,
      $type
   ): void {
      $schema = $this->db->schema->getTableSchema($table, true);
      if ($schema !== null && !isset($schema->columns[$column])) {
         $this->addColumn($table, $column, $type);
      }
   }

   private function createIndexIfMissing(
      string $name,
      string $table,
      string $column,
      bool $unique = false
   ): void {
      if (!$this->indexExists($table, $name)) {
         $this->createIndex($name, $table, $column, $unique);
      }
   }

   private function dropIndexIfExists(string $name, string $table): void
   {
      if ($this->indexExists($table, $name)) {
         $this->dropIndex($name, $table);
      }
   }

   private function indexExists(string $table, string $index): bool
   {
      $driver = $this->db->driverName;
      $rawTable = $this->db->schema->getRawTableName($table);

      if ($driver === 'mysql') {
         return (bool)$this->db->createCommand(
            'SELECT COUNT(*) FROM information_schema.statistics '
            . 'WHERE table_schema = DATABASE() '
            . 'AND table_name = :table AND index_name = :index',
            [':table' => $rawTable, ':index' => $index]
         )->queryScalar();
      }

      if ($driver === 'pgsql') {
         return (bool)$this->db->createCommand(
            'SELECT COUNT(*) FROM pg_indexes '
            . 'WHERE tablename = :table AND indexname = :index',
            [':table' => $rawTable, ':index' => $index]
         )->queryScalar();
      }

      return false;
   }
}
