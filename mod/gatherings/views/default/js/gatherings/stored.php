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
		e.preventDefault();
		
		user_guid = $(this).find('input[name="user_guid"]').val();
		portal.find().send("message", 
						{ 
							to_guid: user_guid,
							message:'test',
							from:'mark'
						},
					 	function(data){
					 		console.log('typing');
					 	},
					 	function(data){
					 		console.log('typing errored');
					 	}
				);
	});
		
}


elgg.register_hook_handler('init', 'system', minds.conversations.init);