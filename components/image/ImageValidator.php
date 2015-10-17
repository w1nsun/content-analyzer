<?php

namespace app\components\image;

use yii\validators\Validator;

class ImageValidator extends Validator
{
    /**
     * @var array php constants: IMAGETYPE_GIF, IMAGETYPE_JPEG
     */
    public $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG];

    /**
     * @var int px
     */
    public $minWidth;

    /**
     * @var int px
     */
    public $minHeight;

    /**
     * @var int px
     */
    public $maxWidth;

    /**
     * @var int px
     */
    public $maxHeight;

    /**
     * @var int bytes
     */
    public $maxSize;


    protected function validateValue($file)
    {
        return $this->validateImage($file);
    }

    /**
     * @param $file
     * @return bool
     */
    protected function validateImage($file)
    {
        if (empty($this->allowedTypes) && !in_array(exif_imagetype($file), $this->allowedTypes)) {
            return ['Разрешены только {types} изображения' , ['types' => $this->getAllowedTypesAsStr()]];
        }

        list($width, $height) = getimagesize($file);

        if ($width) {
            if ($this->maxWidth && $width > $this->maxWidth) {
                return ['Ширина изображения должна быть не больше {width} px', ['width' => $this->maxWidth]];
            }
            if ($this->minWidth && $width < $this->minWidth) {
                return ['Ширина изображения должна быть не меньше {width} px', ['width' => $this->minWidth]];
            }
        }

        if ($height) {
            if ($this->maxHeight && $width > $this->maxHeight) {
                return ['Высота изображения должна быть не больше {height} px', ['width' => $this->maxHeight]];
            }
            if ($this->minHeight && $width < $this->minHeight) {
                return ['Высота изображения должна быть не меньше {height} px', ['width' => $this->minHeight]];
            }
        }

        return null;
    }

    /**
     * @param null $type
     * @return array
     */
    protected static function enumTypeStr($type = null)
    {
        $types = [
            IMAGETYPE_GIF  => 'gif',
            IMAGETYPE_JPEG => 'jpeg',
            IMAGETYPE_PNG  => 'png',
            IMAGETYPE_SWF  => 'swf',
            IMAGETYPE_PSD  => 'psd',
            IMAGETYPE_BMP  => 'bmp',
        ];

        return $type ? $types[$type] : $types;
    }

    /**
     * @return string
     */
    protected function getAllowedTypesAsStr()
    {
        $allowedTypesAsSrt = [];

        foreach ($this->allowedTypes as $type) {
            $allowedTypesAsSrt[] = self::enumTypeStr($type);
        }

        return implode(', ', $allowedTypesAsSrt);
    }
}
