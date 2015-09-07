<?php

namespace app\components;


trait ImageTrait
{
    protected static $repositoryClassName = 'app\models\Image';

    protected static function getRepositoryObject()
    {
        return new self::$repositoryClassName;
    }

    public function addImage($src)
    {
        $image = self::getRepositoryObject();
    }
}