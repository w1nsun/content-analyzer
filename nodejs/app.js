var Config = require('./config/config.json');
var Cheerio = require('cheerio');
var Iconv = require('iconv-lite');
var Request = require('request');
var Feed = require('feed-read');



function getResources(){
    var options = {
        url: Config.serviceDomain+'/api/resource',
        headers: {
            'Authorization': 'Bearer '+Config.serviceAccessToken
        }
    };

    function callback(error, response, body) {
        if (!error && response.statusCode == 200) {
            var resources = JSON.parse(body);
            console.log(body);
        }else{
            console.log(error);
        }
    }

    Request(options, callback);
}

return getResources();





/*

var RESOURCES_TABLE_NAME = 'resource';

function resourcesFindAll(resourceHandler){

    mysqlConnection.connect();

    var query = function(limit, offset, resourceHandler, nextPageHandler){

        var sql = 'SELECT * FROM '+RESOURCES_TABLE_NAME+' WHERE status=1 ORDER BY id ASC LIMIT '+limit+' offset '+offset;

        mysqlConnection.query(sql, function(err, rows, fields) {
            if (err){
                mysqlConnection.end();
                throw err;
            }

            if( ! rows.length){
                mysqlConnection.end();
                return;
            }

            for(var i=0; i<=rows.length-1; i++){
                resourceHandler(rows[i]);
            }

            offset+=limit;
            nextPageHandler(limit, offset, resourceHandler, nextPageHandler);
        });
    }

    query(1, 0, resourceHandler, query);
}


var ARTICLE_TABLE_NAME = 'articles';

function articlesSave(article){
    mysqlConnection.connect();


    var sql = 'SELECT COUNT(id) as count FROM '+ARTICLE_TABLE_NAME+' WHERE url='+mysqlConnection.escape(article.url);

    mysqlConnection.query(sql, function(err, rows, fields) {
        if (err){
            mysqlConnection.end();
            throw err;
        }


        //тут обработка
        console.log(rows[0].count + ' rows');
    });




    mysqlConnection.end();
}





resourcesFindAll(function(resource){

    // Each article has the following properties:
    //
    //   * "title"     - The article title (String).
    //   * "author"    - The author's name (String).
    //   * "link"      - The original article link (String).
    //   * "content"   - The HTML content of the article (String).
    //   * "published" - The date that the article was published (Date).
    //   * "feed"      - {name, source, link}
    //
    Feed(resource.url, function(err, articles) {
        if (err) throw err;

        for(var i=0; i<articles.length-1; i++){

            var articleLink = articles[i].link;
            var articleDate = articles[i].published;
            var articleTitle = articles[i].title.replace(/<!\[CDATA\[([^\]]+)]\]>/ig, "$1");

            Request(
                {
                    uri:articles[i].link,
                    method:'GET',
                    encoding:'binary'
                },
                (
                    function(){
                        //'передавать все параметры что нужно сохранить в БД'
                        var rssArticle = articles[i];

                        return function (err, res, body) {
                                //Получили текст страницы, теперь исправляем кодировку и
                                //разбираем DOM с помощью Cheerio.
                                var $=Cheerio.load(Iconv.encode(Iconv.decode(new Buffer(body,'binary'), 'win1251'), 'utf8'));


                            //console.log(rssArticle);
                            console.log(resource.id);

                                //$('meta[property="og:image"]').each(function(){
                                //    console.log($(this).attr('content'));
                                //});


                        }
                    }
                )()
            );

        }
    });

});
    */