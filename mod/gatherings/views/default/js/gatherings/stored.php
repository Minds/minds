<?php if(0){?><script><?php } ?>
elgg.provide('minds.conversations');

minds.conversations.init = function() {

	/**
	 * Listen to when a live search user is selected
	 */
	$('input[name=u]').on('minds-ac-select', function(e, params){
		window.location.href = elgg.get_site_url() + 'gatherings/conversation/'+ params.username;
	});

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
		
		
		var participants = [];
		$(this).find('input[name="participants[]"]').each(function() { participants.push($(this).val()); });
	
	
		_this = this;
		elgg.post(elgg.get_site_url() + 'gatherings/conversation', {
			data: elgg.security.addToken({
				user_guid: user_guid, 
				message: message,
				participants: participants,
			}),
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
		
		
		//currently we can not support live group chat
		if(participants.length > 2)
			return true;
		
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
	
	$('.elgg-form-conversation textarea').on('keyup', function(e){
	
		if($(this).val().length >= 320){
			$('.system-messages-output').html( (320 - $(this).val().length) + ' characters remaining').css({color:'red', 'font-weight':'bold', 'float':'left', margin:'6px'});
			if ((e.which != 8) && (e.which != 13)) { 
			    return false;
			}
		} else {
			$('.system-messages-output').html( (320 - $(this).val().length) + ' characters remaining').css({color:'#333', 'font-weight':'bold', 'float':'left', margin:'6px'});
		}
		
		//submit form on enter key
		if(e.which == 13){
			 e.preventDefault();
			 $(this).parent().submit();
		}

		
	});
	
	
	/**
	 * Remove a message
	 */
	$(document).on('click', '.message .actions .delete', function(){
		guid = $(this).parents('li').attr('id');
		that = this;
		elgg.ajax(elgg.get_site_url() + 'gatherings/conversation/'+guid, {
			method: 'DELETE',
			//contentType : 'application/json',
			success : function(output) {
				$(that).parents('li').remove();
			}
		});
	});
	
	
	/**
	 * Load earlier messages
	 */
	lock = false;
	$('.conversation-messages .load-more').on('click', function(e){
		if(lock)
			return false;
		lock = true;
		guid = $(this).next().attr('id');
		elgg.get(window.location.href, {
			data: elgg.security.addToken({
				offset:guid,
				view: 'json'
			}),
			//contentType : 'application/json',
			success : function(output) {
				messages = output.object.message;
				$.each(messages.reverse(), function(i,message){
					var msg_view = $('<li class="clearfix">' + window["obj_template_"+ message.ownerObj.guid] + "</li>");
					msg_view.find('.message-content').html(message.message + '<span class="time">'+message.friendly_ts+'</span>');
					msg_view.attr('id', message.guid);
					
					$('.conversation-messages').prepend(msg_view);
				});
				
				lock=false;
			}
		});
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
