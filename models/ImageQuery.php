<?php

namespace app\models;

use app\components\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Image]].
 *
 * @see Image
 */
class ImageQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Image[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Image|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}