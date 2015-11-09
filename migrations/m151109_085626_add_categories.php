<?php

use yii\db\Schema;
use yii\db\Migration;

class m151109_085626_add_categories extends Migration
{
    public function up()
    {
        $this->createTable('{{%category}}', [
            'id'           => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'title'        => 'VARCHAR(255) NOT NULL',
            'slug'         => 'VARCHAR(255) NOT NULL',
            'status'       => 'TINYINT UNSIGNED NOT NULL DEFAULT 0',
        ], 'ENGINE=INNODB');

        $this->execute('ALTER TABLE `article`
	          ADD COLUMN `category_id` INT(10) UNSIGNED NOT NULL AFTER `type`;');

        $this->execute('CREATE TABLE `rss_tag_category` (
                `tag` VARCHAR(255) NOT NULL,
                `category_id` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`tag`, `category_id`)
            )
            COLLATE=\'utf8_general_ci\'
            ENGINE=InnoDB;');
    }

    public function down()
    {
        echo "m151109_085626_add_categories cannot be reverted.\n";

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
