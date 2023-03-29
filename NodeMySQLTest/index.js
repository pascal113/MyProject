const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '../admin/.env')  });
let bodyParser = require('body-parser');
let app = require('express')();
let http = require('http').createServer(app);
let handler = require('./app/handler').handler;
let logger = require('./app/logger').logger;
let io = require('socket.io')(http,{
  path: '/suckit'
});
app.use(bodyParser.json()); // support json encoded bodies
app.use(bodyParser.urlencoded({ extended: true })); // support encoded bodies

handler.init(io);

const port = 8000;

app.get('/test', (req, res) => {
  logger.log('/test');
  res.send({});
});

io.on('connection', (socket) => {

  /*
   * set initial Data for User
   */
  socket.on('set_client', (data) => {
    handler.setClient(socket, data);
  });


  /*
   * disconnect
   * Beim abmelden socket id entfernen
   */
  socket.on('disconnect', () => {
    logger.log('disconnect');
    logger.logSocketCount(handler.usersockets);
    handler.disconnect(socket);
  });


  /*
   * broadcast
   * Sende Daten an alle Clients
   */
  app.post('/broadcast', (req, res) => {

    let socket_ids = handler.getAllSocketIds(req);

    socket_ids.forEach((socket_id) => {
      handler.msgUser(socket_id, req.body);
    });

    res.sendStatus(200);
  });

  /*
   * broadcast
   * Emitte Daten an alle Clients
   */
  app.post('/action', (req, res) => {

    let socket_ids = handler.getAllSocketIds(req);

    socket_ids.forEach((socket_id) => {
      handler.actionUser(socket_id, req.body);
    });

    res.sendStatus(200);
  });


  /*
   * msguser
   * Sende Info-Nachricht an einen User
   */
  app.post('/msguser/:user_id', (req, res) => {

    let user_id = req.params.user_id;
    let socket_ids = handler.getUserSocketIds(req, user_id);

    logger.log('Sende Nachricht an einen User');
    logger.log(user_id);
    logger.log(socket_ids);
    logger.log(req.body);
    logger.line();

    socket_ids.forEach((socket_id) => {
      handler.msgUser(socket_id, req.body);
    });

    res.sendStatus(200);
  });


  /*
   * msgusers
   * Sende Info-Nachricht an viele User
   */
  app.post('/msgusers', (req, res) => {

    let user_ids = req.body.user_ids;

    logger.log('Sende Nachricht an viele User');
    logger.log(user_ids);
    logger.log(req.body);
    logger.line();

    user_ids.forEach((user_id) => {
      let socket_ids = handler.getUserSocketIds(req, user_id);

      socket_ids.forEach((socket_id) => {
        handler.msgUser(socket_id, req.body);
      });
    });

    res.sendStatus(200);
  });


  /*
   * msgallusers
   * Sende Info-Nachricht an ALLE User
   */
  app.post('/msgallusers', (req, res) => {

    let socket_ids = handler.getAllSocketIds(req);

    logger.log('Sende Nachricht an Alle User in Agency');
    logger.log(socket_ids);
    logger.log(req.body);
    logger.line();

    socket_ids.forEach((socket_id) => {
      handler.msgUser(socket_id, req.body);
    });

    res.sendStatus(200);
  });
});


/*
 * Starte Server
 */
http.listen(port, () => {
  logger.line();
  console.log('listening on *:' + port);
  logger.line();
});
