<?php if(0){?><script><?php } ?>
elgg.provide('minds.conversations');

minds.conversations.init = function() {

	/**
 	 * Default to bottom of divs
	 */
	$(function() {
		var conversation    = $('.conversation-wrapper');
		if(conversation.length > 0){
			var height = conversation[0].scrollHeight;
			conversation.scrollTop(height);
		}
	});
	
	
	/**
	 * Check to see if we have a connection to the user on the other side
	 */
	$('.elgg-form-conversation').on('submit', function(e){
		
		var user_guid = $(this).find('input[name="user_guid"]').val();
		var message = $(this).find('textarea').val();
		
		function encrypt(guid, message){
			var jse = new JSEncrypt();
		 	var pub = JSON.parse(window.localStorage.getItem('publickey:'+guid));
			jse.setPublicKey(pub);
			
			return jse.encrypt(message);
		}
		
		encrypted = encrypt(user_guid, message);
		own = encrypt(elgg.get_logged_in_user_guid(), message);
		
		var data = { to_guid: user_guid, message: 'encrypted...', from_name:elgg.get_logged_in_user_entity().name};
		data["message:"+ user_guid] = encrypted;
		data["message:"+ elgg.get_logged_in_user_guid()] = own;
		
		console.log(user_guid);
		portal.find().send("message", data);
	});
		
}


elgg.register_hook_handler('init', 'system', minds.conversations.init);