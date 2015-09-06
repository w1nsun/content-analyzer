<?php

use yii\db\Migration;

class m150906_182950_add_images extends Migration
{
    public function up()
    {
        $this->createTable('{{%image}}', [
            'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'owner' => 'VARCHAR(255) NULL DEFAULT NULL',
            'owner_id' => 'INT UNSIGNED NULL DEFAULT NULL',
            'src' => 'VARCHAR(255) NOT NULL',
            'width' => 'INT UNSIGNED NOT NULL',
            'height' => 'INT UNSIGNED NOT NULL',
            'size' => 'VARCHAR(255) NOT NULL',
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
