<?php

namespace app\components;

use app\components\socials\Facebook;
use app\components\socials\GooglePlus;
use app\components\socials\Linkedin;
use app\components\socials\Pinterest;
use app\components\socials\Vkontakte;
use app\models\Article;
use GuzzleHttp\Client;
use yii\di\ServiceLocator;

//todo: twitter treands http://chimera.labs.oreilly.com/books/1234000001583/ch01.html#tinkering-with-twitters-api
class ArticleLikesWorker
{
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var ServiceLocator
     */
    protected $serviceLocator;

    /**
     * @var int
     */
    protected $limit = 20;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var LikesLog
     */
    protected $likesLog;

    /**
     * @param Client $httpClient
     * @param ServiceLocator $serviceLocator
     * @param LikesLog $likesLog
     */
    public function __construct(Client $httpClient, ServiceLocator $serviceLocator, LikesLog $likesLog)
    {
        $this->httpClient     = $httpClient;
        $this->serviceLocator = $serviceLocator;
        $this->likesLog       = $likesLog;
    }

    /**
     * @return array
     */
    protected static function parseSocialList()
    {
        return [
            Facebook::NAME   => Facebook::getClassName(),
            Vkontakte::NAME  => Vkontakte::getClassName(),
            Pinterest::NAME  => Pinterest::getClassName(),
            Linkedin::NAME   => Linkedin::getClassName(),
            GooglePlus::NAME => GooglePlus::getClassName(),
        ];
    }

    /**
     * @param $serviceName
     * @return string
     */
    protected static function makeServiceName($serviceName)
    {
        return 'article.score.' . $serviceName;
    }


    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function initSocialContainer()
    {
        foreach (self::parseSocialList() as $socialName => $socialClass) {
            $this->serviceLocator->set(self::makeServiceName($socialName), function () use ($socialClass) {
                return new $socialClass($this->httpClient);
            });
        }
    }

    /**
     * Run worker
     */
    public function run()
    {
//        (new GooglePlus($this->httpClient))->getLikes('http://habrahabr.ru/');

        $this->initSocialContainer();

        while (true) {
            $numLikes = [];
            $articles = Article::find()->active()->offset($this->offset)->limit($this->limit)->all();

            printf("selected articles: %d\r\n", count($articles));

            if (!count($articles)) {
                break;
            }

            /** @var \app\models\Article $article */
            foreach ($articles as $article) {
                foreach (self::parseSocialList() as $socialName => $socialClass) {
                    /** @var \app\components\socials\PageLikesInterface $social */
                    $social = $this->serviceLocator->get($this->makeServiceName($socialName));
                    $num    = $social->getLikes($article->url);

                    printf("parse: %s | number of likes: %d | social: %s\r\n", $article->url, $num, $socialName);

                    if (!is_int($num)) {
                        continue;
                    }

                    $numLikes[$article->id][$socialName] = $num;
                }
            }

            $articles = null;
            unset($articles);

            $this->offset += $this->limit;
            $this->log($numLikes);

            $numLikes = null;
            unset($numLikes);
        }
    }

    /**
     * @param $numLikes
     */
    protected function log($numLikes)
    {
        foreach ($numLikes as $articleId => $numLikesBySocial) {
            $data = array_merge(['article_id' => $articleId], $numLikesBySocial);
            $this->likesLog->log($data);
        }
    }
}