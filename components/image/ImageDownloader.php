<?php

namespace app\components\image;

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
     * @var string
     */
    public $tmpAlias = '@app/runtime/files';

    /**
     * @param ImageValidator $validator
     * @param array $config
     */
    public function __construct(ImageValidator $validator, $config = [])
    {
        $this->validator = $validator;

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
     * @param $to
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
            $result = file_put_contents($this->to, file_get_contents($this->runtimeFile)) === false ? false : true;
            chmod($this->to, 0777);
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
        $this->runtimeFile = \Yii::getAlias($this->tmpAlias) . '/' . uniqid('image_downloader_') . '.' . $ext;

        return $this->runtimeFile;
    }

    /**
     * @param $src
     * @return bool
     */
    public function delete($src)
    {
        if (file_exists($src)) {
            return unlink($src);
        }

        return false;
    }
}