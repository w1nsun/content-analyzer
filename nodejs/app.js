var Config = require('./config/config.json');
var Cheerio = require('cheerio');
var Iconv = require('iconv-lite');
var Request = require('request');
var Feed = require('feed-read');

/**
 * Read RSS
 * @param url
 * @param resource_id
 */
function readRss(url, resource_id){
    // Each article has the following properties:
    //
    //   * "title"     - The article title (String).
    //   * "author"    - The author's name (String).
    //   * "link"      - The original article link (String).
    //   * "content"   - The HTML content of the article (String).
    //   * "published" - The date that the article was published (Date).
    //   * "feed"      - {name, source, link}
    //
    Feed(url, function(err, articles) {
        if (err) throw err;

        for(var i=0; i<articles.length; i++){

            Request(
                {
                    uri:articles[i].link,
                    method:'GET',
                    encoding:'binary'
                },
                (
                    function () {

                        var article         = articles[i];
                        article.resource_id = resource_id;
                        article.url         = articles[i].link;
                        article.title       = articles[i].title.replace(/<!\[CDATA\[([^\]]+)]\]>/ig, "$1");
                        article.description = articles[i].content.replace(/<blockquote.*?>(.*)<\/blockquote>/ig, '');
                        article.image       = null;

                        return function (err, res, body) {
                            //Получили текст страницы, теперь исправляем кодировку и
                            //разбираем DOM с помощью Cheerio.
                            var $=Cheerio.load(Iconv.encode(Iconv.decode(new Buffer(body,'binary'), 'win1251'), 'utf8'));

                            if ($('meta[property="og:image"]').length) {
                                article.image = $('meta[property="og:image"]').attr('content');
                            }

                            writeArticle(article);
                        }
                    }
                )()
            );

        }
    });
}

/**
 *
 * @param article
 */
function writeArticle(article){
    var options = {
        url: Config.serviceDomain+'/api/article/create',
        formData: {
            'Article[title]'       : article.title,
            'Article[url]'         : article.url,
            'Article[description]' : article.description,
            'Article[resource_id]' : article.resource_id,
            'Article[image]'       : article.image
        },
        headers: {
            'Authorization': 'Bearer '+Config.serviceAccessToken
        }
    };

    function callback(error, response, body){
        if (!error && response.statusCode == 200) {
            //var resources = JSON.parse(body);
        }else{
            return;
        }
    }

    Request.post(options, callback);
}

/**
 * Read Resources from API
 */
function readResources(){
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
}


/**
 * Main
 * @returns {*}
 */
function main()
{
    return readResources();
}
main();