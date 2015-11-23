<?php

namespace app\components\socials;

use GuzzleHttp\Client;

/*
 * todo: реализовать функционал парсинга лайков по симлинкам (искать short ссылки)
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