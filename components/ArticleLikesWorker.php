<?php

namespace app\components;

use app\components\socials\Facebook;
use app\components\socials\GooglePlus;
use app\components\socials\Linkedin;
use app\components\socials\Pinterest;
use app\components\socials\Twitter;
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
            Twitter::NAME    => Twitter::getClassName(),
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

        while (true) {
            $likesByArticle = [];
            $articles = Article::find()
                            ->recentActive()
                            ->offset($this->offset)
                            ->limit($this->limit)
                            ->all();

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

                    $likesByArticle[$article->id][$socialName] = $num;
                }
            }

            $articles = null;
            unset($articles);

            $this->offset += $this->limit;
            $this->updateCountLikes($likesByArticle);
            $this->log($likesByArticle);

            $likesByArticle = null;
            unset($likesByArticle);
        }
    }

    /**
     * @param $likesByArticle
     */
    protected function log($likesByArticle)
    {
        foreach ($likesByArticle as $articleId => $likesBySocial) {
            $data = array_merge(['article_id' => $articleId], $likesBySocial);
            $this->likesLog->log($data);
        }
    }

    /**
     * @param array $likesByArticle
     * @throws \Exception
     */
    protected function updateCountLikes(array $likesByArticle)
    {
        foreach ($likesByArticle as $articleId => $likesBySocial) {
            /** @var Article $article */
            $article = Article::findOne($articleId);
            if (!$article) {
                \Yii::warning(sprintf('Article with ID: %s not found!', $articleId));
                continue;
            }

            $article->likes_facebook    = $likesBySocial[Facebook::NAME];
            $article->likes_google_plus = $likesBySocial[GooglePlus::NAME];
            $article->likes_pinterest   = $likesBySocial[Pinterest::NAME];
            $article->likes_linkedin    = $likesBySocial[Linkedin::NAME];
            $article->likes_twitter     = $likesBySocial[Twitter::NAME];
            $article->likes_vkontakte   = $likesBySocial[Vkontakte::NAME];

            $article->update(
                false,
                [
                    'likes_facebook',
                    'likes_google_plus',
                    'likes_pinterest',
                    'likes_linkedin',
                    'likes_twitter',
                    'likes_vkontakte'
                ]
            );
        }
    }
}