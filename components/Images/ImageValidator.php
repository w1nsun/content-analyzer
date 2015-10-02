<?php

namespace app\components\Images;

use yii\base\Object;

class ImageValidator extends Object
{
    /**
     * @var array php constants: IMAGETYPE_GIF, IMAGETYPE_JPEG
     */
    protected $allowedTypes;

    /**
     * @var int px
     */
    protected $minWidth;

    /**
     * @var int px
     */
    protected $minHeight;

    /**
     * @var int px
     */
    protected $maxWidth;

    /**
     * @var int px
     */
    protected $maxHeight;

    /**
     * @var int bytes
     */
    protected $maxSize;

    /**
     * @var array
     */
    protected $errors;

    /**
     * @param array $allowedTypes
     * @param array $config
     */
    public function __construct(array $allowedTypes = [], array $config = [])
    {
        $this->allowedTypes = $allowedTypes;

        parent::__construct($config);
    }


    /**
     * @param $src
     * @return bool
     */
    public function validate($src)
    {
        return ($this->isValidType($src) && $this->isValidWidthHeight($src));
    }

    protected function isValidType($src)
    {
        if (empty($this->allowedTypes)) {
            return true;
        }

        if (in_array(exif_imagetype($src), $this->allowedTypes)) {
            return true;
        }

        $this->addError(\Yii::t('app', 'Разрешены только {types} изображения', ['types' => $this->getAllowedTypesAsStr()]));

        return false;
    }

    /**
     * @param $src
     * @return bool
     */
    protected function isValidWidthHeight($src)
    {
        list($width, $height) = getimagesize($src);

        if ($width) {

            if ($this->maxWidth && $width > $this->maxWidth) {
                $this->addError(\Yii::t('app', 'Ширина изображения должна быть не больше {width} px', ['width' => $this->maxWidth]));
                return false;
            }

            if ($this->minWidth && $width < $this->minWidth) {
                $this->addError(\Yii::t('app', 'Ширина изображения должна быть не меньше {width} px', ['width' => $this->minWidth]));
                return false;
            }

        }

        if ($height) {

            if ($this->maxHeight && $width > $this->maxHeight) {
                $this->addError(\Yii::t('app', 'Высота изображения должна быть не больше {height} px', ['width' => $this->maxHeight]));
                return false;
            }

            if ($this->minHeight && $width < $this->minHeight) {
                $this->addError(\Yii::t('app', 'Высота изображения должна быть не меньше {height} px', ['width' => $this->minHeight]));
                return false;
            }

        }

        return true;
    }

    /**
     * @param $errorMsg string
     */
    protected function addError($errorMsg)
    {
        $this->errors = array_merge($this->errors, [$errorMsg]);
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

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
