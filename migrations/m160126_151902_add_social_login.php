<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_151902_add_social_login extends Migration
{
    public function up()
    {
        $this->execute(
            'ALTER TABLE `user`
              ADD COLUMN `social_name` VARCHAR(255) NOT NULL DEFAULT \'\' AFTER `status`,
	          ADD COLUMN `social_id` VARCHAR(255) NOT NULL DEFAULT \'\' AFTER `social_name`;'
        );
    }

    public function down()
    {
        echo "m160126_151902_add_social_login cannot be reverted.\n";

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
