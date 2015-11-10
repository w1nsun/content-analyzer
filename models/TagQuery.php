<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Tag]].
 *
 * @see Tag
 */
class TagQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Tag[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Tag|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param array $tags
     * @return int
     * @throws \yii\db\Exception
     */
    public function batchAdd(array $tags)
    {
        $values = [];
        foreach ($tags as $tag) {
            $values[] = '(\'' . addslashes($tag) . '\' , 0, ' . Tag::STATUS_ACTIVE . ')';
        }

        $sql = 'INSERT IGNORE INTO `' . Tag::tableName() . '` (tag, category_id, status) ' .
            'VALUES ' . implode(', ' , $values) . ';';

        return \Yii::$app->db->createCommand($sql)->execute();
    }
}