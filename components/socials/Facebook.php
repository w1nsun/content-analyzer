<?php

namespace app\components\socials;

class Facebook extends Social implements PageLikesInterface
{
    const NAME = 'facebook';

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
        $response = $this->httpClient->request('GET', $this->makePageLikesUrl($url));

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return json_decode($response->getBody())->data[0]->total_count;
    }

    /**
     * @param $url
     * @return string
     */
    protected function makePageLikesUrl($url)
    {
        return "https://graph.facebook.com/fql?" .
        "q=SELECT like_count, total_count, share_count, click_count, comment_count" .
        " FROM link_stat WHERE url = '{$url}'";
    }

}