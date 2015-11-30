<?php

namespace app\components;

use yii\base\Component;


//todo: упростить
//ImageDownloader - сохранять изображение изначально в temp
//FileSystem - createDirById($id), save($from, $to), createDir()


class FileSystem extends Component
{
    /**
     * @var string The base directory alias for storing files
     */
    protected $baseDirAlias;

    /**
     * @param $id
     * @param int $level
     * @param int $maxDir
     * @return string
     */
    public function createDirById($id, $level = 3, $maxDir = 1000)
    {
        $crc32Id	 = abs(crc32($id));
        $crc32AsDirs = (string) ($crc32Id % $maxDir);
        $dirsLength  = strlen($crc32AsDirs);

        if ($dirsLength < $level) {
            while ($dirsLength < $level) {
                $crc32AsDirs = '0' . $crc32AsDirs;
                $dirsLength++;
            }
        }

        $subDir = '';
        foreach (str_split($crc32AsDirs) as $dirFragment) {
            $subDir .= '/' . $dirFragment;
        }

        $directory = \Yii::getAlias($this->baseDirAlias) . '/' . $subDir;

        if (file_exists($directory)) {
            return $subDir;
        }

        if (!mkdir($directory, 0775, true)) {
            throw new \RuntimeException(\Yii::t('component', 'Невозможно создать директорию {dir}', ['dir' => $directory]));
        }

        return ltrim($subDir, '/');
    }
}