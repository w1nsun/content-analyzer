<?php

namespace app\components\image;

class ImageManager
{
    /**
     * @var string
     */
    protected $imageFile;

    /**
     * @var array
     */
    protected $sizes = [];

    /**
     * @param string $imageFile
     */
    public function __construct($imageFile)
    {
        $this->imageFile = $imageFile;
    }

    /**
     * @param $width
     * @param $height
     * @return $this
     */
    public function addSize($width, $height)
    {
        $this->sizes[] = [
            'width'  => $width,
            'height' => $height
        ];

        return $this;
    }

    public function doThumbnail()
    {
        $originalImage = new \Imagick(realpath($this->imageFile));

        foreach ($this->sizes as $size) {
            $image = clone $originalImage;
            $thumbnailWidth = $size['width'];
            $thumbnailHeight = $size['height'];

            $this->resizeImage($image, $thumbnailWidth, $thumbnailHeight);
        }
    }

    /**
     * @param \Imagick $imagick
     * @param $width
     * @param $height
     */
    protected function resizeImage(\Imagick $imagick, $width, $height)
    {
        $imageWidth = $imagick->getImageWidth();
        $imageHeight = $imagick->getImageHeight();

        if ($imageWidth > $imageHeight) {
            $imagick->resizeImage($width, 0, \Imagick::FILTER_LANCZOS, 1);
        } else {
            $imagick->resizeImage(0, $height, \Imagick::FILTER_LANCZOS, 1);
        }

        $imagick->cropThumbnailImage($width, $height);
        $pathInfo = pathinfo($this->imageFile);
        $newFileName = $width . 'x' . $height . '_' . $pathInfo['basename'];
        $imagick->writeImage($pathInfo['dirname'] . '/' . $newFileName);
    }
}