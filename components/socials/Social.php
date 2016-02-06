<?php

namespace app\components\socials;

use GuzzleHttp\Client;

/*
 * todo: реализовать функционал парсинга лайков по симлинкам (искать short ссылки)
 * http://cgit.drupalcode.org/shareaholic/tree/lib/social-share-counts/share_count.php?id=0af9ade973a91ee7744f61c9820e5779b9241bfc
 */
abstract class Social
{
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get social name
     * @return string
     */
    abstract public function getName();

    /**
     * Get class name with namespace
     * @return string
     */
    public static function getClassName()
    {
        return get_called_class();
    }
}