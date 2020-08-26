function notifikasi(message, icon){
	$.toast({ 
	  text : message, 
	  showHideTransition : 'slide', 
	  icon: icon,
	  textColor : '#eee',  
	  allowToastClose : false,  
	  hideAfter : 7000,             
	  stack : 5,                     
	  textAlign : 'left',            
	  position : 'top-right'       
	})
}

function show_modal(header,msg,footer){
	$('#modal').modal('show').find('#header-info').html(header);
	$('#modal').modal('show').find('#modalContent').html(msg);
	$('#modal').modal('show').find('#footer-info').html(footer);
}

$('document').ready(function(){ 
    $('.yii-gridview').on('pjax:start', function() {
        SimpleLoading.start('gears');
    });
    $('.yii-gridview').on('pjax:end', function() {
        SimpleLoading.stop();
    });
});