<?php

namespace app\models;

use app\components\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Resource]].
 *
 * @see Resource
 */
class UserQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Resource[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Resource|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}