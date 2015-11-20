<?php

namespace app\components\socials;


interface SocialInterface
{
    /**
     * Get social name
     * @return string
     */
    public static function getName();

    /**
     * Get class name with namespace
     * @return string
     */
    public static function getClassName();
}