<?php

use yii\db\Schema;
use yii\db\Migration;

class m160105_090901_resources extends Migration
{
    public function up()
    {
        $columns = [
            'title',
            'url',
            'type',
            'lang',
            'country',
            'updated_at',
            'created_at',
            'status'
        ];
        $rows = [
            ['TimeCom', 'http://time.com/feed/', 'rss', 'EN', 'GB', time(), time(), \app\models\Resource::STATUS_ACTIVE]
        ];


        $this
            ->getDb()
            ->createCommand()
            ->batchInsert(\app\models\Resource::tableName(), $columns, $rows)
            ->execute();
    }

    public function down()
    {
        echo "m160105_090901_resources cannot be reverted.\n";

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
