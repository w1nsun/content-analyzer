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
        $response = $this->httpClient->request('GET', $this->makePageLikesUrl($url), [
//            'headers' => [
//                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36',
//            ],
//            'allow_redirects' => true,
//            'debug' => true,
//            'verify' => false
        ]);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        var_dump(json_decode($response->getBody()));
        var_dump(file_get_contents($this->makePageLikesUrl($url)));

//        return json_decode($response->getBody())->data[0]->total_count;
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