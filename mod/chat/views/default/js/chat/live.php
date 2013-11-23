elgg.provide('minds.live');

minds.live.init = function() {
	
	var user = elgg.get_logged_in_user_entity();

	if(user){

		//load active chats on page loads
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
		
		$(document).on('keydown', '.minds-live-chat-userlist li input', function(e){ 
			parent = $(this).parents('li');
			if(e.which == 13){
				portal.find().send("message", {to_guid: parent.attr('id'), message: $(this).val(), from_name:user.name });  
				parent.removeClass('active');
				$(this).val('');
			}
		});

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
		});

		portal.open("http://108.82.235.133:8080/chat", {
			sharing:true
		}).on({
				open: function() {
					console.log("The connection has been opened");
					portal.find().send("connect", {guid: guid });
				},
				close: function(reason) {
				//         console.log("The connection has been closed due to '" + reason + "'");
			     //            $(input).attr("disabled", "disabled");
				   },
				connect: function(){
						console.log('you are connected?');
				},
				message: function(data) {
		console.log(data);
					if(data.from_guid == elgg.get_logged_in_user_guid()){
						box = $('.minds-live-chat-userlist').find('li.box#' + data.to_guid);
						var from = "You: ";
						minds.live.saveCacheChat(data.to_guid, from + data.message, box.find('h3').text());
					} else {
						//play sound
						document.getElementById('sound').play();
						box = $('.minds-live-chat-userlist').find('li.box#' + data.from_guid);
						if(box.length == 0){
							minds.live.openChatWindow(data.from_guid, data.from_name, data.message);
						return true;	
						}
						box.addClass('active');
						var from = box.find('h3').text() + ": ";
						minds.live.saveCacheChat(data.from_guid, from + data.message, data.from_name);
					}
					box.find('.messages').append('<span class="message">' + from + data.message + '</span>')
						.animate({ scrollTop: box.find('.messages')[0].scrollHeight},1000);
				
					//cache in a cookie so new page loads see it
				},
				error: function(error){
					console.log(error);
					if(error.code == 1){
						err_msg = "The user could not be reached, your message has not been sent";
					}
					box = $('.minds-live-chat-userlist').find('li.box#' + error.to_guid);
					box.find('.messages').append('<span class="message"><br/><i>' + err_msg + '</span>')
						.animate({ scrollTop: box.find('.messages')[0].scrollHeight},1000);
				},
				 waiting: function(delay, attempts) {
					   console.log("The socket will try to reconnect after " + delay + " ms");
					   console.log("The total number of reconnection attempts is " + attempts);
				  },
				 heartbeat: function() {
					  console.log("The server's heart beats");
				  },
				userList: function(data){
					var obj = $.parseJSON(data );
					$.each(obj, function( key, value ) {
						if(availableUsers.indexOf(value) == -1) {
							availableUsers.push(value);
						}
					});
					$.post( "chat/return_userlist", { guids: availableUsers} ).done(function(data){ $('.minds-live-chat-userlist').find('.userlist ul').html(data);});
				}
			});

			//foreach chat window we have, give it an offset 
			minds.live.adjustOffset();			
		}
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

