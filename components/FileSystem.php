<?php

namespace app\components;

use yii\base\Component;


//todo: упростить

class FileSystem extends Component
{
    const FILE_TYPE_IMAGE = 'image';

    /**
     * @var string
     */
    protected $imagesDirAlias;

    /**
     * @var int
     */
    protected $maxDirs;

    /**
     * @var int
     */
    protected $levelSubDirs;

    /**
     * @var string
     */
    protected $dir;

    /**
     * @var string
     */
    protected $fileType;

    /**
     * @var string
     */
    protected $file;

    /**
     * @param string $imagesDirAlias
     */
    public function setImagesDirAlias($imagesDirAlias)
    {
        $this->imagesDirAlias = $imagesDirAlias;
    }

    /**
     * @return int
     */
    public function getMaxDirs()
    {
        return $this->maxDirs;
    }

    /**
     * @param int $maxDirs
     */
    public function setMaxDirs($maxDirs)
    {
        $this->maxDirs = $maxDirs;
    }

    /**
     * @return int
     */
    public function getLevelSubDirs()
    {
        return $this->levelSubDirs;
    }

    /**
     * @param int $levelSubDirs
     */
    public function setLevelSubDirs($levelSubDirs)
    {
        $this->levelSubDirs = $levelSubDirs;
    }

    /**
     * @return string
     */
    public function getDir()
    {
        $this->validate();

        return $this->dir;
    }

    protected function validate()
    {
        if (!$this->fileType) {
            throw new \RuntimeException(\Yii::t('component', 'Необходимо выбрать тип файла'));
        }
    }

    /**
     * @return $this
     */
    public function image()
    {
        $this->reset();

        $this->fileType = self::FILE_TYPE_IMAGE;
        $this->dir      = \Yii::getAlias($this->imagesDirAlias);

        return $this;
    }

    /**
     * @param $file
     * @return $this
     */
    public function file($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Save file $fromFile as $saveFileName
     *
     * @param $saveFile
     * @return bool
     */
    public function saveAs($saveFile)
    {
        $this->beforeSave();

        $saveFile = str_replace('\\\\', '/', $saveFile);

        if (substr($this->dir, -1) !== '/' && substr($saveFile, 0, 1) !== '/') {
            $this->dir .= '/';
        }

        $result = file_put_contents($saveFile, file_get_contents($this->file)) ? true : false;

        if ($result === false) {
            throw new \RuntimeException(\Yii::t('component', 'Ошибка при сохранении файла: {file}', ['file' => $saveFile]));
        }

        return $this;
    }

    /**
     * Get file size in bytes
     *
     * @return int
     */
    public function getFileSize()
    {
        if (!file_exists($this->file)) {
            throw new \RuntimeException(\Yii::t('component', 'Файл {file} не существует', ['file' => $this->file]));
        }

        $result = filesize($this->file);

        $this->reset();

        return $result;
    }


    /**
     * Check exists directory
     */
    protected function beforeSave()
    {
        if ($this->file === null) {
            throw new \RuntimeException(\Yii::t('component', 'Необходимо указать файл для сохранения'));
        }

        if (!file_exists($this->file)) {
            throw new \RuntimeException(\Yii::t('component', 'Файл {file} не существует', ['file' => $this->file]));
        }

        if (!file_exists($this->dir)) {
            mkdir($this->dir, 0775, true);
        }

        if (!is_writable($this->dir)) {
            chmod($this->dir, 0775);
        }
    }

    /**
     * Reset data
     */
    protected function reset()
    {
        $this->fileType = null;
        $this->dir      = null;
        $this->file     = null;
    }


    /**
     * Create new subdirectories
     * @param string $id
     * @return string
     */
    public function createSubDirs($id)
    {
        $this->validate();

        $crc32Id	 = abs(crc32($id));
        $crc32AsDirs = (string) ($crc32Id % $this->maxDirs);
        $dirsLength  = strlen($crc32AsDirs);

        if ($dirsLength < $this->levelSubDirs) {
            while ($dirsLength < $this->levelSubDirs) {
                $crc32AsDirs = '0' . $crc32AsDirs;
                $dirsLength++;
            }
        }

        $subDir = '';
        foreach (str_split($crc32AsDirs) as $dirFragment) {
            $subDir .= '/' . $dirFragment;
        }

        $directory = $this->dir . '/' . $subDir;

        if (file_exists($directory)) {
            return $subDir;
        }

        if (!mkdir($directory, 0775, true)) {
            throw new \RuntimeException(\Yii::t('component', 'Невозможно создать директорию {dir}', ['dir' => $directory]));
        }

        return ltrim($subDir, '/');
    }

    /**
     * Get image width
     * @return mixed
     */
    public function getWidth()
    {
        $this->validate();

        if ($this->fileType !== self::FILE_TYPE_IMAGE) {
            throw new \RuntimeException(\Yii::t('component', 'Выбраный файл должен быть картинкой'));
        }

        list($width) = getimagesize($this->file);

        return $width;
    }

    /**
     * Get image height
     * @return mixed
     */
    public function getHeight()
    {
        $this->validate();

        if ($this->fileType !== self::FILE_TYPE_IMAGE) {
            throw new \RuntimeException(\Yii::t('component', 'Выбраный файл должен быть картинкой'));
        }

        list(, $height) = getimagesize($this->file);

        return $height;
    }
}