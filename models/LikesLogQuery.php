<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[LikesLog]].
 *
 * @see LikesLog
 */
class LikesLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return LikesLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LikesLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}