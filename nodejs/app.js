var config = require('./config/config.json');
var request = require('request');


request('http://habrahabr.ru/', function (error, response, body) {
    if (!error && response.statusCode == 200) {
        //console.log(body) // Show the HTML for the Google homepage.
        console.log(config)
    }
})
