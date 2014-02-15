<?php if(0){?><script><?php } ?>
elgg.provide('minds.live');

minds.live.init = function() {
	
	var user = elgg.get_logged_in_user_entity();

	if(user){

		/*//load active chats on page loads
		ls = window.localStorage;
		var activeChats = JSON.parse(ls.getItem('activeChats'));
		if(activeChats){
			$.each(activeChats, function(){
				console.log(this);		
				minds.live.openChatWindow(this.id, this.name, '', true);
			});
		}

		//store a list of the logged in users
		var availableUsers = [];

		var guid = new String(user.guid);
		
		*/
		
		var chatController = {
			type: 'chat',
			onInit: function(){
				/* --- setup --- */
				_this=this;
				console.log(this._api);
				minds.live.openChatWindow(this._api.gathering_guid, 'Test chat', '', false);
			
				$(document).on('keydown', '.minds-live-chat-userlist li input', function(e){ 
					input = $(this);
					parent = input.parents('li');
					if(e.which == 13){
						_this.sendMessage( {text: input.val()}, 
											function(e){ 
												input.val(''); 
												parent.removeClass('active');	
										});
					}
				});
			},
			onMessage: function(msg){ 
				box = $('.minds-live-chat-userlist').find('li.box#'+this._api.gathering_guid);
				box.find('.messages')
					.append('<span class="message">' + msg.user + ': ' + msg.text + '</span>')
					.animate({ scrollTop: box.find('.messages')[0].scrollHeight},0);
				console.log(msg);
			},
			onClear: function(){ console.log('clearing'); },
		}
		a = minds.live.apiInstance("281180268868407296", [chatController]);
	
		/*
		

		$(document).on('click','.minds-live-chat-userlist li h3', function (e) {
			portal.find().send("userList");
			toggles = $(this).parent();
			userlist = $(this).parents('userlist');
			if(userlist && toggles.hasClass('user')){
				box = $('.minds-live-chat-userlist').find('li.box#' + toggles.attr('id'));
				if(box.length == 0){
					minds.live.openChatWindow(toggles.attr('id'), $(this).text(), '');
				} else {
					box.addClass('toggled');
				}
			} else {
				if(toggles.hasClass('toggled')){
					toggles.removeClass('toggled');
				} else {
					toggles.addClass('toggled');
					$(this).parent().find('input').focus();
					//$(this).parent().find('messages').animate({ scrollTop:  $(this).parent().find('messages')[0].scrollHeight},1000);
				}
			}
			toggles.removeClass('active');	
		});

		$(document).on('click', '.minds-live-chat-userlist li .del', function (e) {
			minds.live.removeCacheChat($(this).parent().attr('id'));
			$(this).parents('li').remove();	
			minds.live.adjustOffset();	
		})

		//foreach chat window we have, give it an offset 
		minds.live.adjustOffset();		*/	
	}
}

/**
 * Retrieve the gathering info. Creds, ids etc
 */
minds.live.getGatheringInfo = function(guid){
	return $.parseJSON($.ajax(
		{
			async: false,
			url: elgg.get_site_url() + 'gatherings/join/'+guid,
			type: "GET",
			dataType: "json"
		}
	).responseText); 
}

/**
 * The api handling function
 */

minds.live.apiInstance = function(guid, controllers) {
	var gathering = guid, 
		a = window[gathering];

	if (!a) {
		
		var g = minds.live.getGatheringInfo(guid);

		/* api instance not yet created for this conference */
		a = BR.v1.api.create({
			hosts: "https://api.babelroom.com", //@todo make this configurable
			authentication: {token: g.token},
			conference_id: g.cid,
			controllers: controllers
		});
		
		a.gathering_guid = gathering;
		
		/* start the conference stream after the DOM is fully loaded */
		jQuery(document).ready(function() { a.start(); });
		window[gathering] = a;
	}

	return a;
}

minds.live.adjustOffset = function(e){
	 $(document).find('.minds-live-chat-userlist li.box').each( function() {
                        console.log($(this).offset().left);
                        prev = $(this).prev();
                        console.log(prev.html());
                        if(prev){
                                $(this).offset({ left:prev.offset().left + prev.width() + 35});
                        }
                });
}

minds.live.openChatWindow = function(id,name,message, minimised){
	var cache = minds.live.getCacheChat(id);
	if(cache){
		var length = cache.length;
		var newmsg = '';
		for (var i = 0; i < length; i++) {
			newmsg	+= '<span class="message">' + cache[i] + '</span>';
		}
	}
			
	if(message){
		message = '<span class="message">' +name + ': ' + message + '</span>';
	}
	message = newmsg + message;
	if(minimised){
		var liclass = 'toggle';
	} else {
		var liclass = 'toggled';
	}
	var box = '<li class="box '+ liclass + '" id="' + id + '">' +
       			 '<h3>' + name + '</h3>' + '<span class="del entypo">&#10062;</span>' +
       			 '<div class="messages">' + message +  '</div>' + 
        		 '<div> <input type="text" class="elgg-input" /> </div>' +
		'</li>';	
	$('.minds-live-chat-userlist > ul').append(box).find('input').focus();
	minds.live.adjustOffset();
}

minds.live.getCacheChat = function(id){
	var key = 'chat.'+id;
	return JSON.parse(sessionStorage.getItem(key));
}
minds.live.saveCacheChat = function(id, message, name){
	ss = window.sessionStorage;
	var key = 'chat.'+id;
	var chatSession = JSON.parse(ss.getItem(key));
	if(!chatSession){
		chatSession = new Array();
	}
	chatSession.push(message);
	sessionStorage.setItem(key, JSON.stringify(chatSession));

	//add key to list of active chats
	ls = window.localStorage;
	var activeChats = JSON.parse(ls.getItem('activeChats'));
	if(!activeChats){
		activeChats = {};
	}
	chat = { id: id,
		 name: name	
		};
	activeChats[id] = chat;

	ls.setItem('activeChats', JSON.stringify(activeChats));
}
minds.live.removeCacheChat = function(id){
	ls = window.localStorage;
	var activeChats = JSON.parse(ls.getItem('activeChats'));
	$.each(activeChats, function(i, val) {
		console.log(id);
		if(i == id){
			delete activeChats[i];
		}
	});
	ls.setItem('activeChats', JSON.stringify(activeChats));
}
elgg.register_hook_handler('init', 'system', minds.live.init);

