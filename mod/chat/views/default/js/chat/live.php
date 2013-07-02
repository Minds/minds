elgg.provide('minds.live');

minds.live.init = function() {
	
	function notify(text) {
			//$('<p class="message notice"/>').text(text).appendTo(content);
			//content.scrollTop = content.scrollHeight;
			console.log(text);
	}
	portal.open("//54.236.133.62:8080/chat").on({
		      	connecting: function() {
                               notify("The connection has been tried by '" + this.data("transport") + "'");
                         },
                        open: function() {
                                notify("The connection has been opened");
                       //         $(input).removeAttr("disabled").focus();
                          },
                        close: function(reason) {
                                 notify("The connection has been closed due to '" + reason + "'");
                     //            $(input).attr("disabled", "disabled");
                           },
                        message: function(data) {
                                  $('<p class="user"/>').text(data.from_guid).appendTo(content);
                                                                
                                  $('<p class="message"/>').text(data.message).appendTo(content);
                                  content.scrollTop = content.scrollHeight;
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
				to_guid = $("#to_guid").val();	
				message = $("#message").val();
				portal.find().send("message", {to_guid: to_guid, message: message});
					
				}
			});

}
elgg.register_hook_handler('init', 'system', minds.live.init);

