<?php

namespace app\components\socials;

use yii\authclient\OAuthToken;

class Twitter extends Social implements PageLikesInterface
{
    const NAME = 'twitter';

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

        printf("go\r\n");

        $token = new OAuthToken([
            'token'       => \Yii::$app->params['twitter_api']['access_token'],
            'tokenSecret' => \Yii::$app->params['twitter_api']['token_secret']
        ]);

        $t = new \yii\authclient\clients\Twitter([
            'accessToken'    => $token,
            'consumerKey'    => \Yii::$app->params['twitter_api']['consumer_key'],
            'consumerSecret' => \Yii::$app->params['twitter_api']['consumer_secret']
        ]);
        printf("go2\r\n");
        $res = $t->api('https://stream.twitter.com/1.1/statuses/filter.json', 'GET', ['track'=>'twitter']);

        var_dump($res);exit;


        $response = $this->httpClient->request('GET', $this->makePageLikesUrl($url));

        if ($response->getStatusCode() !== 200) {
            return null;
        }


        var_dump($response->getBody()); exit;

        return json_decode($response->getBody())->data[0]->total_count;
    }

    /**
     * @param $url
     * @return string
     */
    protected function makePageLikesUrl($url)
    {
        return "http://cdn.api.twitter.com/1/urls/count.json?url={$url}";
    }
}