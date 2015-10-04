<?php

namespace app\components\Images;

use Yii;

class Handler
{
    const MAX_FOLDERS          = 1000;
    const LEVEL_NESTED_FOLDERS = 3;

    /**
     * @var string
     */
    protected $imageUrl;

    /**
     * @var Downloader
     */
    protected $downloader;

    /**
     * @var array
     */
    protected $params;

    /**
     * @param Downloader $downloader
     * @param array $params
     */
    public function __construct(Downloader $downloader, array $params = [])
    {
        $this->downloader = $downloader;
        $this->params     = $params;
    }

    /**
     * @param string $imageUrl
     * @return bool|string
     */
    public function handle($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        $this->checkAndSetDirPermissions();

        return $this->download();
    }

    /**
     * Download image by url
     * @return bool|string
     */
    protected function download()
    {
        $imageName    = uniqid('article_') . '.' . pathinfo($this->imageUrl, PATHINFO_EXTENSION);
        $folder       = $this->createFolder($imageName);
        $fullImageDir = Yii::getAlias($this->params['path']) . $folder . '/' .$imageName;

        $result = $this->downloader
                        ->from($this->imageUrl)
                        ->to($fullImageDir)
                        ->save();

        if ($result) {
            return $folder . '/' . $imageName;
        }

        return false;
    }

    /**
     * Check isset folders and permissions
     */
    protected function checkAndSetDirPermissions()
    {
        if (!isset($this->params['path'])) {
            throw new \UnexpectedValueException('Not set \'path\' param.');
        }

        if (!file_exists($this->params['path'])) {
            mkdir($this->params['path'], 0775);
        }

        if (!is_writable($this->params['path'])) {
            chmod($this->params['path'], 0775);
        }
    }

    /**
     * Create new folder
     * @param string $id
     * @return string
     */
    protected function createFolder($id)
    {
        $crc32Id	    = abs(crc32($id));
        $crc32AsFolders = (string) ($crc32Id % self::MAX_FOLDERS);
        $foldersLength  = strlen($crc32AsFolders);

        if ($foldersLength < self::LEVEL_NESTED_FOLDERS) {
            while ($foldersLength < self::LEVEL_NESTED_FOLDERS) {
                $crc32AsFolders = '0' . $crc32AsFolders;
                $foldersLength++;
            }
        }

        $folder = '';
        foreach (str_split($crc32AsFolders) as $folderFragment) {
            $folder .= '/' . $folderFragment;
        }

        $directory = Yii::getAlias($this->params['path']) . $folder;

        if (file_exists($directory)) {
            return $folder;
        }

        if (!mkdir($directory, 0775, true)) {
            throw new \RuntimeException('Can\'t create directory: ' . $directory);
        }

        return $folder;
    }
}