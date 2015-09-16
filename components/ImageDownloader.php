<?php

namespace app\components;

use yii\validators\Validator;

class ImageDownloader
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
     * @var Validator Validator for validate downloaded image
     */
    protected $validator;

    /**
     * @var string Runtime file name
     */
    protected $runtimeFile;

    /**
     * @var string
     */
    protected $tempPath = './../runtime/files';

    /**
     * ImageDownloader constructor.
     * @param Validator $validator
     * @param string $tempPath
     */
    public function __construct(Validator $validator, $tempPath)
    {
        $this->validator = $validator;
        $this->tempPath  = $tempPath;
    }

    public function from($url)
    {
        $this->url = $url;
        return $this;
    }

    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    public function save()
    {
    }

    protected function download()
    {
        $this->runtimeFile = $this->tempPath . '/' . uniqid('image_downloader') . '.tmp';
        $fp = fopen ($this->tempPath, 'w+');
        $ch = curl_init($this->from);

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36');
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
}