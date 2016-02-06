<?php

namespace app\components\socials;

class Linkedin extends Social implements PageLikesInterface
{
    const NAME = 'linkedin';

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

        $result = json_decode($response->getBody());

        return isset($result->count) ? (int) $result->count : null;
    }

    /**
     * @param $url
     * @return string
     */
    protected function makePageLikesUrl($url)
    {
        return "https://www.linkedin.com/countserv/count/share?url={$url}&format=json";
    }
}