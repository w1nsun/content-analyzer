<?php

namespace app\components;

use yii\db\Connection;
use yii\db\Query;

class LikesLog
{
    /**
     * @var string
     */
    protected static $tableName = 'likes_log';

    /**
     * @var Connection
     */
    protected $dbConnection;

    /**
     * @var Query
     */
    protected $query;

    /**
     * @param Connection $dbConnection
     * @param Query $query
     */
    public function __construct(Connection $dbConnection, Query $query)
    {
        $this->dbConnection = $dbConnection;
        $this->query = $query;
    }

    /**
     * @param array $data
     * @throws \yii\db\Exception
     */
    public function log(array $data)
    {
        $this->dbConnection->createCommand()->insert(self::$tableName, [
            'article_id'  => $data['article_id'],
            'created_at'  => time(),
            'facebook'    => isset($data['facebook']) ? $data['facebook'] : 0,
            'twitter'     => isset($data['twitter']) ? $data['twitter'] : 0,
            'pinterest'   => isset($data['pinterest']) ? $data['pinterest'] : 0,
            'linkedin'    => isset($data['linkedin']) ? $data['linkedin'] : 0,
            'google_plus' => isset($data['google_plus']) ? $data['google_plus'] : 0,
            'vkontakte'   => isset($data['vkontakte']) ? $data['vkontakte'] : 0,
        ])->execute();
    }
}