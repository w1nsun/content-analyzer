<?php

use yii\db\Schema;
use yii\db\Migration;

class m151023_200918_add_user extends Migration
{
    public function up()
    {
         $this->createTable('{{%user}}', [
             'id'           => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
             'email'        => 'VARCHAR(255) NOT NULL',
             'password'     => 'VARCHAR(255) NOT NULL',
             'auth_key'     => 'VARCHAR(255) NOT NULL',
             'access_token' => 'VARCHAR(512) NOT NULL',
             'status'       => 'TINYINT UNSIGNED DEFAULT 0',
         ], 'ENGINE=INNODB');
    }

    public function down()
    {
        echo "m151023_200918_add_user cannot be reverted.\n";

        return false;
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
