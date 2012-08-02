<?php
/**
 * Chat JavaScript extension for elgg.js
 */
?>
elgg.provide('elgg.notify');

elgg.notify.init = function() {
	if (elgg.is_logged_in()) {
		//setInterval(elgg.chat.markMessageRead, 2000);
		
		//elgg.notify.getNotifications();
		//setInterval(elgg.notify.getNotifications, 10000);
        
        $("#notify_button").bind("click", elgg.notify.getNotifications);
	}
};

/**
 * Change the color of new messages.
 */
/*elgg.notify.markMessageRead = function() {
	var activeMessages = $('.elgg-chat-messages .elgg-chat-unread');
	var message = $(activeMessages[0]);
	message.animate({backgroundColor: '#ffffff'}, 1000).removeClass('elgg-chat-unread');
};*/

/**
 * Get notifications via AJAX.
 * 
 */
elgg.notify.getNotifications = function() {
   
    console.log('triggered');
 	var url = elgg.normalize_url("mod/notifications/pages/notifications.php");
    	
  	 $.get(url, function(data) {
      		$('#notification').html(data);
            //$('#notification').append(data);
     });

}

elgg.register_hook_handler('init', 'system', elgg.notify.init);
