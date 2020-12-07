var app = require('express')();
app.use(function(req, res, next) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "X-Requested-With");
    res.header("Access-Control-Allow-Headers", "Content-Type");
    res.header("Access-Control-Allow-Methods", "PUT, GET, POST, DELETE, OPTIONS");
    next();
});
var http = require('http').Server(app);
var io = require('socket.io')(http);

io.on('connection', function(socket){
    console.log('new client connected');
    socket.on('disconnet', function(){
        console.log('a client disconnect'); 
    })
    socket.on('notif',function(msg){
        console.log('message: '+msg.name+ ': ' + msg.message);
        io.emit('notif', {name: msg.name, message: msg.message});
    })
});

http.listen(3000, function(){
  console.log('listening on *:3000');
})