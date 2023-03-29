var mysql = require('mysql');
let logger = require('./logger').logger;

const database = {

    pool: null,
    connected: false,

    init: () => {

        logger.log('Verbinde MySql');

        database.pool = mysql.createPool({
            host: process.env.DB_HOST,
            user: process.env.DB_USERNAME,
            password: process.env.DB_PASSWORD,
            database: process.env.DB_DATABASE
        });

        /*
        database.connection.connect((err) => {
            if (err) {
                throw err;
                logger.error('mysql connect failed!');
                logger.line();
            }
            else {
                database.connected = true;
                logger.log('mysql connected!');
                logger.line();
            }
        });
         */
    },

    update: (sql, try_count) => {

        if(try_count === undefined) {
            try_count = 0;
        }

        logger.log(sql);
        logger.log('Versuch: ' + try_count);
        logger.line();

        let has_error = false;

        database.pool.query(sql, (err, result) => {
            if (err){
                has_error = true;
            }
            else {
                logger.log(result.affectedRows + " record(s) updated");
                logger.line();
                return true;
            }
        });

        if(has_error) {
            try_count++;

            if(try_count < 3) {
                setTimeout(() => {
                    database.update(sql, try_count)
                },5000);
            }
            else {
                logger.error('Zu viele Versuche!');
                logger.log(sql);
                logger.line();
            }
        }
    },

    setSocketData: (user_id, data) => {
        database.update('UPDATE users SET socketdata = \'' + JSON.stringify(data) + '\' WHERE id = ' + parseInt(user_id));
    },

    removeSocketData: (user_id) => {
        database.update('UPDATE users SET socketdata = NULL WHERE id = ' + parseInt(user_id));
    }

};

exports.database = database;