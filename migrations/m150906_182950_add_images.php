<?php

use yii\db\Migration;

class m150906_182950_add_images extends Migration
{
    public function up()
    {
        $this->createTable('{{%image}}', [
            'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'owner_type' => 'VARCHAR(255) NULL DEFAULT NULL',
            'owner_id' => 'INT UNSIGNED NULL DEFAULT NULL',
            'src' => 'VARCHAR(255) NULL DEFAULT NULL',
            'width' => 'INT UNSIGNED NULL DEFAULT NULL',
            'height' => 'INT UNSIGNED NULL DEFAULT NULL',
            'size' => 'VARCHAR(255) NULL DEFAULT NULL',
            'parent_id' => 'INT UNSIGNED NULL DEFAULT NULL',
            'status' => 'TINYINT UNSIGNED DEFAULT 0',
        ], 'ENGINE=INNODB');
    }

    public function down()
    {
        echo "m150906_182950_add_images cannot be reverted.\n";

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
