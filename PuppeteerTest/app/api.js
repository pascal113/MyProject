const axios = require('axios');
const helper = require('../app/helper');
//const FormData = require('form-data');

let api = {

    base_url: 'http://recruiting.jungwild.io.devbox/api/v1',

    auth: null,
    user: null,

    default_options: {
        success: () => {},
        complete: () => {},
        error: () => {}
    },
    default_axios_options: {
        headers: {}
    },

    init: (callback) => {
        api.login('admin@admin.com', 'password', callback);
    },

    login: (user, password, callback) => {
        api.post('/login',{
            email: user,
            password: password
        },{
            success: (response) => {
                api.auth = response.auth;
                api.user = response.user;
                api.setToken(api.auth.access_token);
                callback();
            },
            error: (err) => {
                console.log(err.message);
                console.log(err.response);
            }
        });
    },

    get: (uri, options) => {

        options = helper.merge_options([api.default_options, options]);

        if(options.axios_options === undefined) {
            options.axios_options = api.default_axios_options;
        }
        else {
            options.axios_options = helper.merge_options([api.default_axios_options, options.axios_options]);
        }

        axios.get(api.base_url + uri, options.axios_options)
            .then((response) => {
                options.success(response.data);
            })
            .catch((error) => {
                options.error(error);
            })
            .finally(() => {
                options.complete();
            });
    },

    post: (uri, data, options) => {

        let params = new URLSearchParams();

        for ( let key in data ) {
            params.append(key, data[key]);
        }

        options = helper.merge_options([api.default_options,options]);

        if(options.axios_options === undefined) {
            options.axios_options = api.default_axios_options;
        }
        else {
            options.axios_options = helper.merge_options([api.default_axios_options, options.axios_options]);
        }

        axios.post(api.base_url + uri, params, options.axios_options)
        .then((response) => {
            options.success(response.data);
        })
        .catch((error) => {
            options.error(error);
        })
        .finally(() => {
            options.complete();
        });
    },

    setToken: (token) => {
        api.default_axios_options.headers.Authorization = 'Bearer ' + token;
    },
};

exports.api = api;