var Config  = require('./config/config.json');
var Cheerio = require('cheerio');
var Request = require('request');
var Events  = require('events');
var Zlib    = require('zlib');

var App = function () {

    //vars
    var read_resources_options = {
        url     : 'http://' + Config.serviceDomain + '/api/resource',
        timeout : 15000,
        headers : {
            'Authorization' : 'Bearer ' + Config.serviceAccessToken
        }
    };

    var event_emitter = new Events.EventEmitter();

    //functions
    var getRandomInt = function (min, max) {
        return Math.floor(Math.random() * (max - min)) + min;
    };

    this.init = function () {
        event_emitter.on('resources_read', this.readResourceFeed);
        event_emitter.on('feed_read', this.feedRead);
        event_emitter.on('feed_item_parse', this.feedItemRead);
        event_emitter.on('feed_item_read', this.writeArticle);
    };

    this.run = function () {
        this.readResourcesFromApi();
    };

    this.readResourceFeed = function (resources) {
        for(var i = 0; i < resources.items.length; i++){

            (function() {

                var resource_id = resources.items[i].id;
                var options     = {
                                    uri      : resources.items[i].url,
                                    method   : 'GET',
                                    encoding : 'utf8',
                                    timeout  : 15000,
                                    headers  : {
                                        'User-Agent' : 'Mozilla/5.0 (Windows NT 6.3; WOW64) ' +
                                                        'AppleWebKit/537.36 (KHTML, like Gecko) ' +
                                                        'Chrome/45.0.2454.85 Safari/537.36',
                                    }
                                };

                Request(options, function (err, res, body) {
                    if (!err && res.statusCode == 200) {

                        event_emitter.emit('feed_read', body, resource_id);

                    }else{

                        console.log('Parse rss feed error: ' + err);
                        return 0;

                    }
                });

            })();
        }
    };

    this.readResourcesFromApi = function () {
        Request(read_resources_options, function (error, response, body) {
            if (!error && response.statusCode == 200) {
                var resources = JSON.parse(body);

                event_emitter.emit('resources_read', resources);
            } else {
                console.log(error);
                return 1;
            }
        });
    };

    this.feedRead = function (body, resource_id) {

        function read() {

            var $ = Cheerio.load(body, { xmlMode: true });

            $('item').each(function (i, element) {

                var article = {};

                article.resource_id = resource_id;
                article.image       = null;
                article.tags        = [];

                article.title       = $(element)
                                            .children('title')
                                            .text()
                                            .replace(/<!\[CDATA\[([^\]]+)]\]>/ig, "$1");

                article.description = $(element)
                                            .children('description')
                                            .text()
                                            .replace(/<!\[CDATA\[([^\]]+)]\]>/ig, "$1")
                                            .replace(/<blockquote.*?>(.*)<\/blockquote>/ig, '')
                                            .replace(/(?:\r\n|\r|\n|\t)/g, ' ');

                article.url         = $(element)
                                            .children('link')
                                            .text();

                $(element).children('category').each(function (idx, category) {
                    var res = $(category)
                                .text()
                                .replace(/<!\[CDATA\[([^\]]+)]\]>/ig, "$1");

                    article.tags.push(res);
                });

                event_emitter.emit('feed_item_parse', article);
            });
        }

        setTimeout(read, getRandomInt(getRandomInt(100, 2000), 5000));
    };

    this.feedItemRead = function (article) {

        var url      = article.url;
        var options  = {
                            uri      : url,
                            method   : 'GET',
                            encoding : 'binary',
                            timeout  : 10000,
                            followAllRedirects : true,
                            maxRedirects : 10,
                            headers: {
                                'User-Agent'     : 'Mozilla/5.0 (Windows NT 6.3; WOW64) ' +
                                                    'AppleWebKit/537.36 (KHTML, like Gecko) ' +
                                                    'Chrome/45.0.2454.85 Safari/537.36'
                            }
                        };

        function read(err, res, body) {
            if (!err && res.statusCode == 200) {
                var result = '';
                var encoding = res.headers['content-encoding'];
                if (encoding && encoding == 'gzip') {
                    Zlib.gunzip(body, function(err, decoded) {

                        if (err){
                            console.log(err);
                            return;
                        }

                        result = decoded.toString();
                    });
                } else {
                    result = body;
                }

                var $ = Cheerio.load(result);

                var imageObj = $('meta[property="og:image"]');
                if (imageObj.length) {
                    article.image = imageObj.attr('content');
                }

                //original URI, after all redirects
                article.url = res.request.uri.href;

                event_emitter.emit('feed_item_read', article);
            } else {
                console.log('feedItemRead Error:');
                console.log(err);
                return 0;
            }

        }

        Request(options, read);
    };

    this.writeArticle = function (article) {
        var options = {
            url: 'http://' + Config.serviceDomain + '/api/article/create',
            formData: {
                'Article[title]'       : article.title,
                'Article[url]'         : article.url,
                'Article[description]' : article.description,
                'Article[resource_id]' : article.resource_id,
                'image'                : article.image ? article.image : '',
                'tags'                 : JSON.stringify(article.tags)
            },
            headers: {
                'Authorization': 'Bearer ' + Config.serviceAccessToken
            }
        };

        function callback(error, response, body){
            if (!error && response.statusCode == 200) {

                console.log('Written ' + article.url);
                JSON.parse(body);

            }else{
                return 0;
            }
        }

        Request.post(options, callback);
    };

};

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