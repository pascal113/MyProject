const api = require('./app/api').api;

let theinterval = null;
let interval_runtime = 60000;

const updateMessages = () => {

    api.get('/browser-proxy/watchdog/runlist', {
        success: (response) => {

            response.tasks.forEach((task) => {
                api.post('/browser-proxy/command/' + task.port + '/messages',{since: '2019-01-01'},{
                    success: (messages) => {
                        console.log(messages);
                    }
                });
            });

        }
    });

};

api.init(() => {
    console.log('api auth ready!');

    updateMessages();


});