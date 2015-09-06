<?php

namespace app\components;

/**
 * Class AdditionalImages
 * @package app\components
 */
trait AdditionalImages
{
    protected static $repositoryClassName = 'Image';
    public $additional_images = [];

    public function addImage($src)
    {
        /**
         * @var \app\models\Image $image
         */
        $image = new self::$repositoryClassName();
        $image->owner = get_class($this);
        $image->owner_id = $this->id;
        $image->src = $src;
        if ($image->save()) {
            $this->additional_images[] = $image;
            return true;
        }

        return false;
    }
}