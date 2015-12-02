<?php

namespace app\components;

use yii\base\Component;

class FileSystem extends Component
{
    /**
     * @var string The base directory alias for storing files
     */
    protected $fsDir;

    /**
     * @return string
     */
    public function getFsDir()
    {
        return \Yii::getAlias($this->fsDir);
    }

    /**
     * @return string
     */
    public function setFsDir($fsDir)
    {
        return $this->fsDir = $fsDir;
    }

    /**
     * @param string $id
     * @param int $level
     * @param int $maxDir
     * @return string
     */
    public function generateDir($id = null, $level = 3, $maxDir = 1000)
    {
        if (!$id) {
            $id = uniqid('', true);
        }

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

        $directory = \Yii::getAlias($this->fsDir) . '/' . $subDir;

        if (file_exists($directory)) {
            return $subDir;
        }

        if (!mkdir($directory, 0775, true)) {
            throw new \RuntimeException(\Yii::t('component', 'Невозможно создать директорию {dir}', ['dir' => $directory]));
        }

        return ltrim($subDir, '/');
    }

    /**
     * @param $from
     * @param $to
     * @return bool
     */
    public function copy($from, $to)
    {
        if (!file_exists($from)) {
            \Yii::error(
                \Yii::t(
                    'component',
                    '{method} Файл {file} не существует.',
                    ['file' => $from, 'method' => __METHOD__]
                )
            );

            return false;
        }

        if (!copy($from, $to)) {
            \Yii::error(
                \Yii::t(
                    'component',
                    '{method} невозможно скопировать файл {file}.',
                    ['file' => $from, 'method' => __METHOD__]
                )
            );

            return false;
        }

        return true;
    }

    /**
     * @param $file
     * @return bool
     */
    public function delete($file)
    {
        if (!unlink($file)) {
            \Yii::error(
                \Yii::t(
                    'component',
                    '{method} невозможно удалить файл {file}.',
                    ['file' => $file, 'method' => __METHOD__]
                )
            );

            return false;
        }

        return true;
    }
}