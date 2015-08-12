var app = require('express')();
var http = require('http').Server(app);
var config = require('./config');
var io = require('socket.io')(http);
var ioRedis = require('socket.io-redis');
var redis = require("redis");
var cassandra = require('cassandra-driver');
var _ = require('lodash-node');
var msgpack = require('msgpack-js');
var twilio = require('twilio')(config.TWILIO.ID, config.TWILIO.SECRET);

var cassandraClient = new cassandra.Client({ contactPoints: config.CASSANDRA.SERVERS, keyspace: config.CASSANDRA.KEYSPACE });
var redisClient = redis.createClient(config.REDIS.PORT, config.REDIS.HOST);

//a local cache of users
var mem_users = [];
io.adapter(ioRedis({ 
    pubClient: redis.createClient(config.REDIS.PORT, config.REDIS.HOST), 
    subClient: redis.createClient(config.REDIS.PORT, config.REDIS.HOST, {detect_buffers: true}) 
}));

app.get('/', function (req, res){
  res.sendStatus(200);
});

io.on('connection', function (socket) {

  /**
   * Register and authenticates a user, based on their access token
   */
  socket.on('register', function (guid, access_token) {
    if(!guid){
        socket.emit('connect_error', 'Guid must be set..');
        return;
    }

    console.log('register from: ' + guid);

    cassandraClient.execute(
      "SELECT * FROM entities WHERE \"KEY\"=?",
      [access_token],
      {prepare: true},
      function(err, result) {
        if(err){
          socket.emit('connect_error', 'Could not authenticate..');
          return;
        }

        for(var i=0; i < result.rows.length; i++){
            var row = result.rows[i];
            if(row.column1 == "user_id" && row.value != guid){
                console.log("false login attempty from " + guid);
                socket.emit('connect_error', 'guid does not match');
                return;
            }

            if(row.column1 == "expires" && row.value <= (Date.now() - 3600) /1000){
                console.log(row.value, (Date.now() - 3600));
                console.log("expired token " + guid);
                socket.emit('connect_error', 'expired token');
                return;
            }
        }

        
        /**
         * Add our user to cache lookup
         */
        mem_users.push({
          guid: guid,
          socket: socket.id,
          ts: Date.now()
        });

        //socket.emit('connect_successful', _.pluck(users, 'guid'));
        socket.broadcast.emit('online', guid);

        io.to(socket).emit('connect', guid);

        //set two lookups, by guid and by socket
        redisClient.set('sockets:guid:'+guid, socket.id);
        redisClient.set('sockets:socket:'+socket.id, guid);

        console.log('authenticated: ' + guid);
      });

  });

  /**
   * Sends a message to a user
   */
  socket.on('sendMessage', function (guid, message) {

    redisClient.get("sockets:socket:" + socket.id, function(err, reply){
        if(err)
            return;
        var from_guid = reply;

        redisClient.get("sockets:guid:" + guid, function(err, reply){
            if(err)
                return;
            var to_socket = reply;
            if(!to_socket){
                console.log('could not find ' + guid);
                return;
            }
            console.log("got reply and now trying to emit to socket " + reply); 
            var sock = io.sockets.connected[to_socket];
            //if(sock){
                io.to(reply).emit('messageReceived', from_guid, message);
            //} else {
             //   console.log('log could not find socket on this server, sending pub event..');
             //   return;
           // }
            console.log(guid + " was sent a " + message.type);
        });
    });
  });

  socket.on('turnToken', function(guid){
    twilio.tokens.create(function(err, response){
        if(err){
            console.log(err);
        } else {
            socket.emit('turnToken', response);
            console.log('sent a twillio token: ' + socket.id);
        }
    });
  });

  socket.on('disconnect', function () {
      redisClient.get('sockets:socket:'+socket.id, function(err, reply){
        if(err)
           return;
        redisClient.del('sockets:socket:'+socket.id);
        redisClient.del('sockets:guid:'+reply);
        console.log('Logged out: ' + reply);
      });
  });

});

http.listen(3000, function(){
  console.log('listening on *:3000');
});


