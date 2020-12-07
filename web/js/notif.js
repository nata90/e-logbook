$( document ).ready(function() {
    var socket = io.connect('http://192.168.20.20:3000');
    socket.on('notif', function (data) {
        $.toast({
		    heading: data.name,
		    text: data.message,
		    showHideTransition: 'slide',
		    icon: 'success',
		    stack: 3,
		    position: 'top-right',
		    hideAfter: 10000   // in milli seconds
		});
    });
});