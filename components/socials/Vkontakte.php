<?php

namespace app\components\socials;


/*
 * todo: при запросе url учитывать кол-во с слэшом вконце и без, потому как для VK это разные URL
 */
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
        $url  = rtrim($url, '/');
        $urls = [$url, $url . '/'];

        $likesNum = 0;
        foreach ($urls as $url) {
            $response = file_get_contents($this->makePageLikesUrl($url));
            if (!$response) {
                \Yii::error('Vkontakte. Error response!');
                return null;
            }

            preg_match('/^VK.Share.count\(1, (\d+)\);$/i',$response, $temp);

            if (!isset($temp[1])) {
                \Yii::error('Vkontakte. Error parse count likes!');
                return null;
            }

            $likesNum += (int) $temp[1];

            usleep(350000);
        }

        return $likesNum;
    }

    /**
     * @param $url
     * @return string
     */
    protected function makePageLikesUrl($url)
    {
        return "https://vk.com/share.php?act=count&index=1&url={$url}&format=json&callback=?";
    }
}