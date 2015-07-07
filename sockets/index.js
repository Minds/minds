var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var _ = require('lodash-node');
var twilio = require('twilio')('AC2e0a25157a4e150f3a87fd6e2f21730c', 'add6d113b8a62e1111c4795e375071de');

var users = [];
var call_queue = [];

app.get('/', function (req, res){
  res.sendfile('index.html');
});

io.on('connection', function (socket) {
  socket.on('register', function (guid) {
    console.log('connect emited');
    // if this socket is already connected,
    // send a failed login message
    if (_.findIndex(users, { socket: socket.id }) !== -1) {
      socket.emit('login_error', 'You are already connected.');
    }

    // if this name is already registered,
    // send a failed login message
    if (_.findIndex(users, { guid: guid }) !== -1) {
      socket.emit('connect_error', 'This guid already exists.');
      return; 
    }

    if(!guid){
        socket.emit('connect_error', 'Guid must be set..');
        return;
    }

    users.push({ 
      guid: guid,
      socket: socket.id
    });

    socket.emit('connect_successful', _.pluck(users, 'guid'));
    socket.broadcast.emit('online', guid);

    io.to(socket).emit('connect', guid);

    console.log(guid + ' logged in');

  });

  socket.on('sendMessage', function (guid, message) {
    var currentUser = _.find(users, { socket: socket.id });
    if (!currentUser) { 
        console.log("could not find current user.. logged in?");
        return; 
    }

    var contact = _.find(users, { guid: guid });
    if (!contact) { 
        console.log("could not find " + guid);
        return;
    }
    
    io.to(contact.socket)
      .emit('messageReceived', currentUser.guid, message);
    console.log(guid + " was sent a " + message.type);
  });

  socket.on('turnToken', function(guid){
    var currentUser = _.find(users, { socket: socket.id });
    if(!currentUser) {
        console.log("could not find current user.. logged in?");
        return;
    }

    twilio.tokens.create(function(err, response){
        if(err){
            console.log(err);
        } else {
            io.to(currentUser.socket).emit('turnToken', response);
            console.log(response);
            console.log(currentUser.guid + ' sent a twillio token');
        }
    });
  });

  socket.on('queue', function(guid){
    var currentUser = _.find(users, { socket: socket.id });
    if (!currentUser) {
        console.log("could not find current user.. logged in?");
        return;
    }
  
    if(!guid){
        var queue = _.find(call_queue, { receiver: currentUser.guid });
        io.to(currentUser.socket)
              .emit('messageReceived', currentUser.guid, {type:'queue', queue: queue});
        console.log('sending queue to ' + currentUser.guid);
        return;
    }
    
    call_queue.push({
        caller: currentUser.guid, 
        receiver: guid
    });
    console.log("Added " + guid + " to the caller queue");   
  });

  socket.on('disconnect', function () {
    var index = _.findIndex(users, { socket: socket.id });
    if (index !== -1) {
      socket.broadcast.emit('offline', users[index].guid);
      console.log(users[index].guid + ' disconnected');

      users.splice(index, 1);
    }
  });
});

http.listen(3000, function(){
  console.log('listening on *:3000');
});
