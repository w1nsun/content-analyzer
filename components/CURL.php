<?php

namespace app\components;

use yii\base\Object;

class CURL extends Object
{
    /**
     * @var
     */
    protected static $curl;

    /**
     * Get CURl instance
     * @return resource
     */
    public static function getInstance()
    {
        if (self::$curl === null) {
            self::$curl = curl_init();
        }

        return self::$curl;
    }

    /**
     * Close CURl connection
     */
    public static function close()
    {
        curl_close(self::$curl);
        self::$curl = null;
    }

    /**
     * Execute request
     * @return mixed
     */
    public function exec()
    {
        return curl_exec(self::$curl);
    }

    /**
     * Set CURl option
     *
     * @param $option
     * @param $value
     * @return mixed
     */
    public function setOption($option, $value)
    {
        curl_setopt(self::$curl, $option, $value);
        return self::$curl;
    }

    /**
     * Download file to
     *
     * @param $fileUrl
     * @param $saveTo
     */
    public static function downloadFile($fileUrl, $saveTo)
    {
        set_time_limit(0);

        self::getInstance()
            ->setOption(CURLOPT_URL, str_replace(" ", "%20", $fileUrl))
            ->setOption(CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36')
            ->setOption(CURLOPT_TIMEOUT, 50)
            ->setOption(CURLOPT_FILE, $saveTo)
            ->setOption(CURLOPT_FOLLOWLOCATION, true)
            ->exec();

        self::close();
    }
}