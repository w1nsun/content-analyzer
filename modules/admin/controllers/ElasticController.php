<?php

namespace app\modules\admin\controllers;


class ElasticController extends BaseController
{
    public function actionIndex()
    {
        //        $im = new ImageManager('/var/www/analyzer/web/files/1/0/9/article56ab3901343bd.jpg');
//        $im->addSize(300, 200)->addSize(200, 300)->addSize(2000, 1560)->doThumbnail();

//http://www.slideshare.net/AltorosBY/elasticsearch-26227465
        //https://gist.github.com/svartalf/4465752
        //https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-lang-analyzer.html
        //https://github.com/imotov/elasticsearch-analysis-morphology
        //https://www.elastic.co/guide/en/elasticsearch/guide/current/controlling-stemming.html#controlling-stemming
        /** @var Client $es */
        $es = Yii::$container->get('elasticsearch');
//        $params = [
//            'index' => 'my_index',
//            'body' => [
//                'settings' => [
////                    'number_of_shards' => 1,
////                    'number_of_replicas' => 0,
//                    'analysis' => [
//                        'filter' => [
//                            'russian_stop' => [
//                                'type' => 'stop',
//                                'stopwords' => 'а,без,более,бы,был,была,были,было,быть,в,вам,вас,весь,во,вот,все,'.
//                                                'всего,всех,вы,где,да,даже,для,до,его,ее,если,есть,еще,же,за,здесь,и,'.
//                                                'из,или,им,их,к,как,ко,когда,кто,ли,либо,мне,может,мы,на,надо,наш,не,'.
//                                                'него,нее,нет,ни,них,но,ну,о,об,однако,он,она,они,оно,от,очень,по,под,'.
//                                                'при,с,со,так,также,такой,там,те,тем,то,того,тоже,той,только,том,ты,у,'.
//                                                'уже,хотя,чего,чей,чем,что,чтобы,чье,чья,эта,эти,это,я,a,an,and,are,'.
//                                                'as,at,be,but,by,for,if,in,into,is,it,no,not,of,on,or,such,that,the'.
//                                                ',their,then,there,these,they,this,to,was,will,with'
//                            ],
////                            'russian_keywords' => [
////                                'type' => 'keyword_marker',
////                                'keywords' => []
////                            ],
//                            'russian_stemmer' => [
//                                'type' => 'stemmer',
//                                'language' => 'russian',
//                            ]
//                        ],
//                        'analyzer' => [
//                            'my_russian_analyzer' => [
//                                'tokenizer'=>  'standard',
////                                'char_filter' => ['html_strip'],
//                                'filter'=> [
//                                    'standard',
//                                    'lowercase',
//                                    'russian_stop',
////                                    'russian_morphology',
////                                    'english_morphology',
////                                    'russian_keywords',
//                                    'russian_stemmer'
//                                ]
//                            ]
//                        ]
//                    ]
//                ],
//                'mappings' => [
//                    'my_type' => [
//                        '_source' => [
//                            'enabled' => true
//                        ],
//                        'properties' => [
//                            'title' => [
//                                'type' => 'string',
//                                'analyzer' => 'my_russian_analyzer'
//                            ],
//                            'description' => [
//                                'type' => 'string',
//                                'analyzer' => 'my_russian_analyzer'
//                            ],
//                        ]
//                    ]
//                ]
//            ]
//        ];
//        $es->indices()->create($params);
//
//        $params = [
//            'index' => 'my_index',
//            'type' => 'my_type',
//            'id' => '1',
//            'body' => ['title' => 'Заголовок', 'description' => 'Маше купили футбольный мяч']
//        ];
//        $response = $es->index($params);
        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'body' => [
                'query' => [
                    'match' => [
//                        'title' => 'мяч',
                        'description' => 'футбольн',
                    ]
                ]
            ]
        ];

        $response = $es->search($params);
        vd($response);

//        $params = [
//            'index' => 'analyzer_article',
//            'type' => 'analyzer_article'
//        ];
//
//        $es->indices()->deleteMapping($params);


        $this->render('index');
    }
}