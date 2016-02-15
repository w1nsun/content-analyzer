var Cheerio = require('cheerio');
var Request = require('request');
var Zlib    = require('zlib');

var Habrahabr = function () {

    var url   = 'url';
    var image = 'image';

    function decode(body) {
        Zlib.gunzip(body, function(err, decoded) {
            if (err){
                console.log('Decode error:');
                console.log(err);
                return;
            }
            return decoded.toString();
        });
    }

    this.getImage = function () {
        return image;
    };

    this.getUrl = function () {
        return url;
    };

};

Habrahabr.prototype.parse = function (err, res, body) {
    if (err || res.statusCode != 200) {
        console.log('Parse error:');
        console.log(err);
        return false;
    }

    var result = '';
    var encoding = res.headers['content-encoding'];
    if (encoding && encoding == 'gzip') {
        result = this.decode(body);
    } else {
        result = body;
    }

    var $        = Cheerio.load(result);
    var imageObj = $('meta[property="og:image"]');
    var image    = null;

    if (imageObj.length) {
        image = imageObj.attr('content');
    }

    return true;
};

//Rabbit.prototype = Object.create(Animal.prototype);
//Rabbit.prototype.constructor = Rabbit;

module.exports = Habrahabr;
