<?php

namespace app\components;

use app\components\socials\Facebook;
use app\models\Article;
use GuzzleHttp\Client;
use yii\di\ServiceLocator;

class ArticleScoreWorker
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
            Facebook::getName() => Facebook::getClassName()
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
        $this->initSocialContainer();

        $numberLikes = [];

        while (true) {
            $articles = Article::find()->active()->offset($this->offset)->limit($this->limit)->all();

            if (!count($articles)) {
                break;
            }

            /** @var \app\models\Article $article */
            foreach ($articles as $article) {
                foreach (self::parseSocialList() as $socialName => $socialClass) {
                    /** @var \app\components\socials\PageLikesInterface $social */
                    $social = $this->serviceLocator->get($this->makeServiceName($socialName));

                    $numberLikes[$article->id][$socialName] = $social->getLikes($article->url);
                }
            }

            $articles = null;
            unset($articles);

            $this->offset += $this->limit;
        }

        $this->log($numberLikes);
    }

    /**
     * @param $numberLikes
     */
    protected function log($numberLikes)
    {
        foreach ($numberLikes as $articleId => $numberLikesBySocial) {
            $data = array_merge(['article_id' => $articleId], $numberLikesBySocial);
            $this->likesLog->log($data);
        }
    }
}