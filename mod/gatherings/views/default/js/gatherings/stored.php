<?php if(0){?><script><?php } ?>
elgg.provide('minds.conversations');

minds.conversations.init = function() {

	/**
 	 * Default to bottom of divs
	 */
	minds.conversations.scrollFix();
	
	/**
	 * Check to see if we have a connection to the user on the other side
	 */
	$('.elgg-form-conversation').on('submit', function(e){
		e.preventDefault();
		
		var user_guid = $(this).find('input[name="user_guid"]').val();
		var message = $(this).find('textarea').val();
		
		_this = this;
		elgg.post(elgg.get_site_url() + 'gatherings/conversation', {
			data: elgg.security.addToken({user_guid: user_guid, message: message}),
			//contentType : 'application/json',
			success : function(output) {
				$(_this).find('textarea').val('');
				
				data = JSON.parse(output);
				console.log(data);
				item = $('<li class="clearfix">'+data.output+'</li>');
				$('ul.conversation-messages').append(item);
				minds.conversations.scrollFix();
			}
		});
		
		function encrypt(guid, message){
			var jse = new JSEncrypt();
		 	var pub = JSON.parse(window.localStorage.getItem('publickey:'+guid));
			jse.setPublicKey(pub);
			
			return jse.encrypt(message);
		}
		
		encrypted = encrypt(user_guid, message);
		own = encrypt(elgg.get_logged_in_user_guid(), message);
		
		var data = { to_guid: user_guid, message: 'encrypted...', from_name:elgg.get_logged_in_user_entity().name, from_username:elgg.get_logged_in_user_entity().username, from_stored: true};
		data["message:"+ user_guid] = encrypted;
		data["message:"+ elgg.get_logged_in_user_guid()] = own;
		
		portal.find().send("message", data);
	});
	
	$('.elgg-form-conversation textarea').on('keypress', function(e){
		//submit form on enter key
		if(e.which == 13){
			 e.preventDefault();
			 $(this).parent().submit();
		}
	});
		
}

minds.conversations.scrollFix = function(){
	var conversation   = $('.conversation-wrapper');
	if(conversation.length > 0){
		var height = conversation[0].scrollHeight;
		conversation.scrollTop(height);
	}
}


elgg.register_hook_handler('init', 'system', minds.conversations.init);