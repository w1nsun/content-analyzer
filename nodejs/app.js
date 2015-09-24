var Config  = require('./config/config.json');
var Cheerio = require('cheerio');
var Iconv   = require('iconv-lite');
var Request = require('request');
var Events  = require('events');


var App = function () {

    //vars

    var read_resources_options = {
        url     : Config.serviceDomain+'/api/resource',
        timeout : 15000,
        headers : {
            'Authorization': 'Bearer '+Config.serviceAccessToken
        }
    };

    var event_emmiter = new Events.EventEmitter();

    var getRandomInt = function (min, max) {
        return Math.floor(Math.random() * (max - min)) + min;
    }


    //functions

    this.init = function () {
        event_emmiter.on('resources_read', this.readResourceFeed);

    };

    this.run = function () {
        this.readResourcesFromApi();
    };

    this.readResourceFeed = function (resources) {
        for(var i = 0; i < resources.items.length; i++){
            console.log(resources.items[i].url);

            //readRss(resources.items[i].url, resources.items[i].id);
            (function() {

                var url = resources.items[i].url;
                var options = {
                    uri: url,
                    method: 'GET',
                    encoding: 'binary',
                    timeout: 15000,
                    headers: {
                        'User-Agent': 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36'
                    }
                };

                setTimeout(function() {

                    Request(options, function (err, res, body) {

                        if (!err && res.statusCode == 200) {
                            var $=Cheerio.load(Iconv.encode(Iconv.decode(new Buffer(body,'binary'), 'win1251'), 'utf8'));

                            var title = $('item').find('title').text();
                            console.log(title);
                        }else{
                            console.log('Parse rss feed error: ' + err);
                            console.log(url);
                            return;
                        }
                    });


                }, getRandomInt(400, 2500));

            })();





        }
    };

    this.readResourcesFromApi = function () {
        Request(read_resources_options, function (error, response, body) {
            if (!error && response.statusCode == 200) {
                var resources = JSON.parse(body);

                event_emmiter.emit('resources_read', resources);
            }else{
                console.log(error);
                return;
            }
        });
    };

    this.readResources = function () {
        var options = {
            url: Config.serviceDomain+'/api/resource',
            headers: {
                'Authorization': 'Bearer '+Config.serviceAccessToken
            }
        };

        function callback(error, response, body) {
            if (!error && response.statusCode == 200) {
                var resources = JSON.parse(body);

                for(var i=0; i<resources.items.length; i++){
                    readRss(resources.items[i].url, resources.items[i].id);
                }

            }else{
                console.log(error);
                return;
            }
        }

        Request(options, callback);
    };

}

//
//
//
///**
// * Read RSS
// * @param url
// * @param resource_id
// */
//function readRss(url, resource_id){
//    // Each article has the following properties:
//    //
//    //   * "title"     - The article title (String).
//    //   * "author"    - The author's name (String).
//    //   * "link"      - The original article link (String).
//    //   * "content"   - The HTML content of the article (String).
//    //   * "published" - The date that the article was published (Date).
//    //   * "feed"      - {name, source, link}
//    //
//    Feed(url, function(err, articles) {
//        if (err) throw err;
//
//        for(var i=0; i<articles.length; i++){
//
//            Request(
//                {
//                    uri      : articles[i].link,
//                    method   : 'GET',
//                    encoding : 'binary',
//                    timeout  : 10,
//                    headers: {
//                        'User-Agent' : 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36'
//                    }
//                },
//                (
//                    function () {
//
//                        var article         = articles[i];
//                        article.resource_id = resource_id;
//                        article.url         = articles[i].link;
//                        article.title       = articles[i].title.replace(/<!\[CDATA\[([^\]]+)]\]>/ig, "$1");
//                        article.description = articles[i].content.replace(/<blockquote.*?>(.*)<\/blockquote>/ig, '');
//                        article.image       = null;
//
//                        return function (err, res, body) {
//                            //Получили текст страницы, теперь исправляем кодировку и
//                            //разбираем DOM с помощью Cheerio.
//                            var $=Cheerio.load(Iconv.encode(Iconv.decode(new Buffer(body,'binary'), 'win1251'), 'utf8'));
//
//                            if ($('meta[property="og:image"]').length) {
//                                article.image = $('meta[property="og:image"]').attr('content');
//                            }
//
//                            writeArticle(article);
//                        };
//                    }
//                )()
//            );
//
//        }
//    });
//}
//
///**
// *
// * @param article
// */
//function writeArticle(article){
//    var options = {
//        url: Config.serviceDomain+'/api/article/create',
//        formData: {
//            'Article[title]'       : article.title,
//            'Article[url]'         : article.url,
//            'Article[description]' : article.description,
//            'Article[resource_id]' : article.resource_id,
//            'Article[image]'       : article.image
//        },
//        headers: {
//            'Authorization': 'Bearer '+Config.serviceAccessToken
//        }
//    };
//
//    function callback(error, response, body){
//        if (!error && response.statusCode == 200) {
//            //var resources = JSON.parse(body);
//        }else{
//            return;
//        }
//    }
//
//    Request.post(options, callback);
//}
//
///**
// * Read Resources from API
// */
//function readResources(){
//    var options = {
//        url: Config.serviceDomain+'/api/resource',
//        headers: {
//            'Authorization': 'Bearer '+Config.serviceAccessToken
//        }
//    };
//
//    function callback(error, response, body) {
//        if (!error && response.statusCode == 200) {
//            var resources = JSON.parse(body);
//
//            for(var i=0; i<resources.items.length; i++){
//                readRss(resources.items[i].url, resources.items[i].id);
//            }
//
//        }else{
//            console.log(error);
//            return;
//        }
//    }
//
//    Request(options, callback);
//}
//

/**
 * Main
 * @returns {*}
 */
function main()
{
    var app = new App();
    app.init();
    app.run();

}
main();