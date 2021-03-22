$( document ).ready(function() {
    var socket = io.connect('http://192.168.20.20:3000');
    var x = document.getElementById("myAudio");
    socket.on('notif', function (data) {
    	x.play();
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