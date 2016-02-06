<?php

namespace app\components\socials;

use yii\base\ErrorException;

class Vkontakte extends Social implements PageLikesInterface
{
    const NAME = 'vkontakte';

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param $url
     * @return null
     */
    public function getLikes($url)
    {

        //todo:for Vkontakte URI with slash ant without are different
        $url  = rtrim($url, '/');
        $urls = [$url, $url . '/'];

        $likesNum = 0;
        foreach ($urls as $url) {
            usleep(1500000);
            $response = false;

            do {
                try {
                    $response = file_get_contents($this->makePageLikesUrl($url));
                } catch (ErrorException $e) {
                    \Yii::error('Vkontakte parser error: ' . $e->getMessage());
                    \Yii::trace(__METHOD__ . 'sleep...');
                    sleep(7);
                }
            } while (!$response);

            preg_match('/^VK.Share.count\(1, (\d+)\);$/i',$response, $temp);

            if (!isset($temp[1])) {
                \Yii::error('Vkontakte. Error parse count likes!');
                return null;
            }

            $likesNum += (int) $temp[1];
        }

        return $likesNum;
    }

    /**
     * @param $url
     * @return string
     */
    protected function makePageLikesUrl($url)
    {
        return "https://vk.com/share.php?act=count&index=1&url={$url}&format=json";
    }
}