var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');

server.listen(8890);

io.listen(server);
io.on('connection', function (socket) {
  
  var redisClient = redis.createClient(6379, '127.0.0.1');
  redisClient.subscribe('checklist');
  console.log(redisClient);
  console.log("redis setup");


  redisClient.on("message", function(channel, checklist) {
    socket.emit(channel, checklist);
  });
 
  socket.on('disconnect', function() {
    console.log("quit");
    redisClient.quit();
  });
 
});
