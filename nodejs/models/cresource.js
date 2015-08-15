/**
 * @param data
 * @constructor
 */
var CResource = function(data){
    this.id = data.id;
    this.title = data.title;
    this.url = data.url;
    this.type = data.type;
    this.last_run_time = data.last_run_time;
    this.status = data.status;
};

CResource.TABLE_NAME = 'resource';

CResource.STATUS_ACTIVE = 1;
CResource.STATUS_DISABLE = 0;

CResource.TYPE_RSS = 1;

CResource.dbConnection = null;

/**
 * @param limit
 * @param offset
 * @param isActiveOnly
 * @param callback function for handle result
 */
CResource.findAll = function(isActiveOnly, callback){
    isActiveOnly = isActiveOnly===undefined ? false : isActiveOnly;

    CResource.dbConnection.connect();

    var query = function(limit, offset, callback, nexCallback){
        var sql = 'SELECT * FROM '+CResource.TABLE_NAME;
        if(isActiveOnly){
            sql += ' WHERE status='+CResource.STATUS_ACTIVE;
        }
        sql += ' ORDER BY id ASC LIMIT '+limit+' offset '+offset;

        CResource.dbConnection.query(sql, function(err, rows, fields) {
            if (err){
                CResource.dbConnection.end();
                throw err;
            }

            if( ! rows.length){
                CResource.dbConnection.end();
                return;
            }

            for(var i=0; i<=rows.length-1; i++){
                callback(new CResource(rows[i]));
            }

            offset+=limit;
            nexCallback(limit, offset, callback, nexCallback);
        });
    }

    query(1, 0, callback, query);
};

module.exports = CResource;