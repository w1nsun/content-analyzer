<?php

namespace app\components\socials;


class GooglePlus extends Social implements PageLikesInterface
{
    const NAME = 'google_plus';

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
        $response = $this->httpClient->request('POST', $this->makePageLikesUrl(), [
            'content-type' => 'application/json',
            'body' => json_encode([
                'method'     => 'pos.plusones.get',
                'id'         => 'p',
                'params'     => [
                    'nolog'   => true,
                    'id'      => $url,
                    'source'  => 'widget',
                    'userId'  => '@viewer',
                    'groupId' => '@self'
                ],
                'jsonrpc'    => '2.0',
                'key'        => 'p',
                'apiVersion' => 'v1'
            ]),
            'debug' => true
        ]);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        if (!isset($result['result']['metadata']['globalCounts']['count'])) {
            return null;
        }

        return (int) $result['result']['metadata']['globalCounts']['count'];
    }

    /**
     * @return string
     */
    protected function makePageLikesUrl()
    {
        return "https://clients6.google.com/rpc";
    }
}