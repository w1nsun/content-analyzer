<?php

use yii\db\Schema;
use yii\db\Migration;

class m150718_132240_create_resources_table extends Migration
{
    public function up()
    {
        $this->createTable('resource', [
            'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'title' => Schema::TYPE_STRING.' NOT NULL',
            'url' => Schema::TYPE_TEXT.' NOT NULL',
            'type' => 'TINYINT UNSIGNED DEFAULT NULL',
            'last_run_time' => Schema::TYPE_INTEGER.' UNSIGNED DEFAULT 0',
            'status' => 'TINYINT UNSIGNED DEFAULT 0',
        ], 'ENGINE=INNODB');
    }

    public function down()
    {
        $this->dropTable('resource');
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
