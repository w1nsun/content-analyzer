<?php

namespace app\components\socials;

use yii\authclient\OAuthToken;
use app\components\trends\Twitter as TwitterTrends;

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
        $params = \Yii::$app->params;

        // Создаем OAuthToken
        $token = new OAuthToken([
            'token' => $params['twitter_api']['access_token'],
            'tokenSecret' => $params['twitter_api']['token_secret']
        ]);
        $socialClient = new \yii\authclient\clients\Twitter([
            'accessToken'    => $token,
            'consumerKey'    => $params['twitter_api']['consumer_key'],
            'consumerSecret' => $params['twitter_api']['consumer_secret'],
        ]);
        $twitterTrends = new TwitterTrends($this->httpClient, $socialClient);
        $result = $twitterTrends->find($url);

        if (!isset($result['statuses']) || empty($result['statuses'])) {
            return null;
        }

        $likes = 0;
        foreach ($result['statuses'] as $status) {
            $likes += (int) $status['retweet_count'] + (int) $status['favorite_count'];
        }

        return $likes;
    }
}