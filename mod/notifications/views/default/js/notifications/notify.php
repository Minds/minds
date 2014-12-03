<?php if(0){ ?><script><?php } ?>
elgg.provide('elgg.notify');

elgg.notify.init = function() {
	if (elgg.is_logged_in()) {
        $(document).on("click",'#notify_button', elgg.notify.getNotifications);
        	
    	elgg.get(elgg.get_site_url() + 'notifications/count',
    		{
    			success: function(data){
    				if(data > 0){
    					var icon  = $('#notify_button');
    					var count = icon.find('.notification-new');
    					
    					if(count.length === 0){
    						count.html(data);
    					} else {
    						icon.find('.notifier').append('<span class="notification-new">1</span>');
    					}
    				}
    			}
    		
    		});
	}

};


/**
 * Get notifications via AJAX.
 * 
 */
elgg.notify.getNotifications = function(e) {
   
     var url = elgg.get_site_url() + "notifications";
	console.log('polling for new notifications');    	
     $.get(url, function(data) {
      		$('#notification').html(data);
      		$('#notification .load-more').show();
            //$('#notification').append(data);
     });

     //reset the counter to 0
     $(".notification-new").hide();

}

elgg.register_hook_handler('init', 'system', elgg.notify.init);