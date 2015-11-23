<?php

namespace app\components;

use yii\base\Component;

//https://gist.github.com/jonathanmoore/2640302
//http://sigov.ru/2012/12/15/shares/
class ArticleScore extends Component
{
    const SOCIAL_FACEBOOK    = 'facebook';
    const SOCIAL_TWITTER     = 'twitter';
    const SOCIAL_PINTEREST   = 'pinterest';
    const SOCIAL_LINKEDIN    = 'linkedin';
    const SOCIAL_GOOGLE_PLUS = 'google-plus';
    const SOCIAL_VKONTAKTE   = 'vkontakte'; //https://vk.com/share.php?act=count&index=1&url=http://promorepublic.com/&format=json&callback=?

    /**
     * @return array
     */
    public static function enumSocials()
    {
        return [
            self::SOCIAL_FACEBOOK,
            self::SOCIAL_TWITTER,
            self::SOCIAL_PINTEREST,
            self::SOCIAL_LINKEDIN,
            self::SOCIAL_GOOGLE_PLUS,
            self::SOCIAL_VKONTAKTE,
        ];
    }


    protected static function enumSocialUrlPattern($social, $url)
    {
        return [
            self::SOCIAL_FACEBOOK    => "http://graph.facebook.com/?id={$url}",
            self::SOCIAL_TWITTER     => "http://cdn.api.twitter.com/1/urls/count.json?url={$url}",
            self::SOCIAL_PINTEREST   => "http://api.pinterest.com/v1/urls/count.json?callback%20&url={$url}",
            self::SOCIAL_LINKEDIN    => "http://www.linkedin.com/countserv/count/share?url={$url}&format=json",
            self::SOCIAL_GOOGLE_PLUS => "", //post only, need API KEY
            self::SOCIAL_VKONTAKTE   => "https://vk.com/share.php?act=count&index=1&url={$url}&format=json&callback=?",
        ];
    }

    public function makeUrl($social, $articleUrl)
    {
    }
}