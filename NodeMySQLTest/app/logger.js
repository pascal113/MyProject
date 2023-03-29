let logger = {

    enabled: true,

    log: (msg) => {
        if(logger.enabled) {
            console.log(msg);
        }
    },

    line: () => {
        if(logger.enabled) {
            console.log('====================================');
        }
    },

    error: (msq) => {
        if(logger.enabled) {
            console.error(msq);
        }
    },

    logSocketCount: (sockets) => {

        if(logger.enabled) {
            for (let agency in sockets) {
                let count_user = 0;
                let count_sockets = 0;
                for (let user in sockets[agency]) {

                    count_user++

                    for (let sock in sockets[agency][user]) {

                        count_sockets++;

                    }

                }

                logger.log('agency ' + agency + ' hat:');
                logger.log(count_user + ' verbundene User');
                logger.log(count_sockets + ' verbundene Sockets');
                logger.line();

            }
        }
    }
};

exports.logger = logger;