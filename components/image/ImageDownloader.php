<?php

namespace app\components\image;

use app\components\FileSystem;
use yii\base\Component;

class ImageDownloader extends Component
{
    /**
     * @var string Link to download
     */
    protected $url;

    /**
     * @var string Path to save file, with file name
     */
    protected $to;

    /**
     * @var ImageValidator for validate downloaded image
     */
    protected $validator;

    /**
     * @var string Runtime file name
     */
    protected $runtimeFile;

    /**
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * @param ImageValidator $validator
     * @param FileSystem $fileSystem
     * @param array $config
     */
    public function __construct(ImageValidator $validator, FileSystem $fileSystem, $config = [])
    {
        $this->validator  = $validator;
        $this->fileSystem = $fileSystem;

        parent::__construct($config);
    }

    /**
     * @param $url
     * @return $this
     */
    public function from($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Save to path
     * @param string $to filename
     * @return $this
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     *
     */
    public function download()
    {
        $this->request();

        $error   = '';
        $isValid = $this->validator->validate($this->runtimeFile, $error);
        $result  = false;

        if ($isValid) {
            $result = $this->fileSystem->image()->file($this->runtimeFile)->saveAs($this->to);
        } else {
            \Yii::error($error);
        }

        $this->delete($this->runtimeFile);

        return $result;
    }

    /**
     * Download image
     */
    protected function request()
    {
        $fp = fopen ($this->generateRuntimeFileName(), 'w+');
        $ch = curl_init($this->url);

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36');
        curl_exec($ch);

        if (curl_exec($ch) === false) {
            throw new \RuntimeException(curl_error($ch));
        }

        curl_close($ch);
        fclose($fp);
    }

    /**
     * @return string
     */
    public function getRuntimeFile()
    {
        return $this->runtimeFile;
    }

    /**
     * @return string
     */
    protected function generateRuntimeFileName()
    {
        $ext = pathinfo($this->url, PATHINFO_EXTENSION);
        $dir = pathinfo($this->to, PATHINFO_DIRNAME);
        $this->runtimeFile = $dir . '/' . uniqid('runtime_') . '.' . $ext;

        return $this->runtimeFile;
    }

    /**
     * @param $src
     * @return bool
     */
    protected function delete($src)
    {
        if (file_exists($src)) {
            return unlink($src);
        }

        return false;
    }
}