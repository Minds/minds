elgg.provide('minds.live');

minds.live.init = function() {
	var guid = new String(elgg.get_logged_in_user_guid());
	function notify(text) {
			//$('<p class="message notice"/>').text(text).appendTo(content);
			//content.scrollTop = content.scrollHeight;
			console.log(text);
	}
//	portal.find().send("connect", {guid: guid});
        notify("connecting");

	$('.minds-live-chat-userlist li input').on('keydown', function(e){ 
		parent = $(this).parents('li');
		if(e.which == 13){
			portal.find().send("message", {to_guid: parent.attr('id'), message: $(this).val()  });  
			parent.removeClass('active');
			$(this).val('');
		}
	});

	$('.minds-live-chat-userlist li h3').on('click', function (e) {
		toggles = $(this).parent();
		if(toggles.hasClass('toggled')){
			toggles.removeClass('toggled');
		} else {
			toggles.addClass('toggled');
			$(this).parent().find('input').focus();
		}	
	});

	portal.open("//108.82.235.132:8080/chat").on({
		      	connecting: function() {
                               notify("The connection has been tried by '" + this.data("transport") + "'");
                         },
                        open: function() {
                                notify("The connection has been opened");
                        	portal.find().send("connect", {guid: guid });
			        notify("connecting " + guid);
 				//this.send("message", {to_guid: 76, message: guid + ' is connected!', from_name:  $('.minds-live-chat-userlist li h3').text()});
			},
                        close: function(reason) {
                                 notify("The connection has been closed due to '" + reason + "'");
                     //            $(input).attr("disabled", "disabled");
                           },
			connect: function(){
					console.log('you are connected?');
			//	console.log(data);
			},
                        message: function(data) {
				if(data.from_guid == elgg.get_logged_in_user_guid()){
					box = $('.minds-live-chat-userlist li#' + data.to_guid);
					var from = "You: ";
				} else {
					box = $('.minds-live-chat-userlist li#' + data.from_guid);
					box.addClass('active');
					var from = box.find('h3').text() + ": ";
				}
                                
				box.find('.messages').append('<span class="message">' + from + data.message + '</span>').animate({ scrollTop: box.find('.messages')[0].scrollHeight},1000);;
			
				//cache in a cookie so new page loads see it
	
			},
                         waiting: function(delay, attempts) {
                                   notify("The socket will try to reconnect after " + delay + " ms");
                                   notify("The total number of reconnection attempts is " + attempts);
                          },
                         heartbeat: function() {
                                  notify("The server's heart beats");
                          }
                });

		
		$("#message").keyup(function(event) {
			if (event.which === 13) {
				to_guid = parseInt($("#to_guid").val());	
				message = $("#message").val();
				portal.find().send("message", {to_guid: to_guid, message: message});
					
				}
			});

}
elgg.register_hook_handler('init', 'system', minds.live.init);

