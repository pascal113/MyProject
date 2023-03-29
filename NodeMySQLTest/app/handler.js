let logger = require('./logger').logger;
const database = require('./database').database;
let handler = {

    usersockets: {},
    io: null,

    init: (io) => {
        database.init();
        handler.io = io;
    },

    setClient: (socket, data) => {

        if(handler.usersockets[data.aid] === undefined) {
            handler.usersockets[data.aid] = {};
        }
        if(handler.usersockets[data.aid][data.uid] === undefined) {
            handler.usersockets[data.aid][data.uid] = {};
        }
        handler.usersockets[data.aid][data.uid][socket.id] = true;

        logger.log('Neuer Client!');
        logger.log(data);
        logger.log(handler.usersockets[data.aid][data.uid]);
        logger.line();
        database.setSocketData(data.uid, handler.usersockets[data.aid][data.uid]);
        logger.logSocketCount(handler.usersockets);
    },


    msgUser: (socket_id, options) => {
        handler.io.to(socket_id).emit('msg', options);
    },

    actionUser: (socket_id, options) => {
        handler.io.to(socket_id).emit('action', options);
    },


    disconnect: (socket) => {
        for (let agency in handler.usersockets) {
            for (let user in handler.usersockets[agency]) {
                if(handler.usersockets[agency][user][socket.id] !== undefined) {
                    delete handler.usersockets[agency][user][socket.id];
                    if(Object.keys(handler.usersockets[agency][user]).length === 0) {
                        delete handler.usersockets[agency][user];
                        database.removeSocketData(user);
                        if(Object.keys(handler.usersockets[agency]).length === 0) {
                            delete handler.usersockets[agency];
                        }
                    }
                }
            }
        }
    },

    getUserSocketIds: (request, user_id) => {

        if(handler.usersockets[request.query.aid] && handler.usersockets[request.query.aid][user_id]) {
            let out = [];
            for (let socket_id in handler.usersockets[request.query.aid][user_id]) {
                out.push(socket_id);
            }
            return out;
        }
        return [];
    },

    getAllSocketIds: (request) => {

        if(handler.usersockets[request.query.aid]) {
            let out = [];
            for (let user_id in handler.usersockets[request.query.aid]) {
                for (let socket_id in handler.usersockets[request.query.aid][user_id]) {
                    out.push(socket_id);
                }
            }
            return out;
        }
        return [];

    }
};

exports.handler = handler;