var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var _ = require('lodash-node');

var users = [];

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

    users.push({ 
      guid: guid,
      socket: socket.id
    });

    socket.emit('connect_successful', _.pluck(users, 'guid'));
    socket.broadcast.emit('online', guid);

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
    console.log(guid + "was sent a " + message.type);
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
