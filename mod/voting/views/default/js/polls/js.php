elgg.provide('elgg.polls');

elgg.polls.init = function() {
	$('.poll-show-link').live('click',elgg.polls.toggleResults);
	$('.poll-vote-button').live('click',function(e) {
		e.preventDefault();
		var guid = $(this).attr("rel");
		// prevent multiple clicks
		$(this).attr("disabled", "disabled");
		// submit the vote and display the response when it arrives 	 
	    elgg.action('action/polls/vote', {data: $('#poll-vote-form-'+guid).serialize(),
			success : function(response) {
			        	$('#poll-container-'+guid).html(response.result);
			        }
	        });
    });
};

elgg.polls.toggleResults = function() {
	var guid = $(this).attr("rel");
	if ($("#poll-vote-form-container-"+guid).is(":visible")) {
		$("#poll-vote-form-container-"+guid).hide();
		$("#poll-post-body-"+guid).show();
		$(this).html("<?php echo elgg_echo('polls:show_poll'); ?>");
	} else {
		$("#poll-vote-form-container-"+guid).show();
		$("#poll-post-body-"+guid).hide();
		$(this).html("<?php echo elgg_echo('polls:show_results'); ?>");
	}	
}

elgg.register_hook_handler('init', 'system', elgg.polls.init);
