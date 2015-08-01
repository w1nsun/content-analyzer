<?php

use yii\db\Schema;
use yii\db\Migration;

class m150801_083356_create_articles_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%article}}', [
            'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'resource_id' => 'INT UNSIGNED NOT NULL',
            'title' => Schema::TYPE_TEXT . ' NOT NULL',
            'description' => Schema::TYPE_TEXT . ' NOT NULL',
            'url' => Schema::TYPE_TEXT . ' NOT NULL',
            'type' => 'TINYINT UNSIGNED DEFAULT NULL',
            'lang' => Schema::TYPE_STRING . ' NOT NULL',
            'country' => Schema::TYPE_STRING . ' NOT NULL',
            'update_time' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'create_time' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'status' => 'TINYINT UNSIGNED DEFAULT 0',
        ], 'ENGINE=INNODB');

        $this->createIndex('idx_resource_id', '{{%article}}', 'resource_id');
        $this->addForeignKey('fidx_resource_article', '{{%article}}', 'resource_id', '{{%resource}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%article}}');
    }
    

//    // Use safeUp/safeDown to run migration code within a transaction
//    public function safeUp()
//    {
//        $this->createTable('{{%article}}', [
//            'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
//            'resource_id' => 'INT UNSIGNED NOT NULL',
//            'title' => Schema::TYPE_TEXT . ' NOT NULL',
//            'description' => Schema::TYPE_TEXT . ' NOT NULL',
//            'url' => Schema::TYPE_TEXT . ' NOT NULL',
//            'type' => 'TINYINT UNSIGNED DEFAULT NULL',
//            'lang' => Schema::TYPE_STRING . ' NOT NULL',
//            'country' => Schema::TYPE_STRING . ' NOT NULL',
//            'update_time' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
//            'create_time' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
//            'status' => 'TINYINT UNSIGNED DEFAULT 0',
//        ], 'ENGINE=INNODB');
//
//        $this->createIndex('idx_resource_id', '{{%article}}', 'resource_id');
//        $this->addForeignKey('fidx_resource_article', '{{%article}}', 'resource_id', '{{%resource}}', 'id', 'CASCADE', 'CASCADE');
//    }
//
//    public function safeDown()
//    {
//        $this->dropTable('{{%article}}');
//    }

}
