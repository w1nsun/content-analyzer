<?php

namespace app\components\socials;

class Pinterest extends Social implements PageLikesInterface
{
    const NAME = 'pinterest';

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
            usleep(400000);

            $response = $this->httpClient->request('GET', $this->makePageLikesUrl($url), ['timeout' => 15]);
            if ($response->getStatusCode() !== 200) {
                \Yii::error(
                    'Pinterest parser response status {status}. URL: {url}',
                    ['status' => $response->getStatusCode(), 'url' => $url]
                );
                continue;
            }

            $body = $response->getBody();
            $response = $body->getContents();
            preg_match('/^receiveCount\((.+)\)$/i', $response, $temp);

            if (!isset($temp[1])) {
                continue;
            }

            $result = json_decode($temp[1], true);
            if (!isset($result['count'])) {
                continue;
            }

            $likesNum = (int) $result['count'];
        }

        return $likesNum;
    }

    /**
     * @param $url
     * @return string
     */
    protected function makePageLikesUrl($url)
    {
        return "http://api.pinterest.com/v1/urls/count.json?url={$url}";
    }
}