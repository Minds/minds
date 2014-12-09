//<script>
elgg.provide('elgg.thumbs');

elgg.thumbs.init = function() {
		
	$('body').on('click', '.thumbs-button-up', elgg.thumbs.action);
	$('body').on('click', '.thumbs-button-down', elgg.thumbs.action);
};


elgg.thumbs.action = function(e) {
	var link = $(this);

	var guid = new String($(this).attr('guid'));
	var count = new Number(link.find('.count').html());
	var action = link.data('action');
	
	elgg.post(elgg.get_site_url() + 'thumbs/actions/'+guid+'/'+action, {
		success: function(data) {
			switch(link.data('action')){
				case "up":
					link.css('color', '#4690D6');
					link.find('.count').html(count + 1);
					link.data('action', 'up-cancel');
					break;
				case "up-cancel":
					link.css('color', '#AAAAAA');
					link.find('.count').html(count - 1);
					link.data('action', 'up');
					break;
				case "down":
					link.css('color', '#4690D6');
					link.find('.count').html(count +1);
					link.data('action', 'down-cancel');
					break;
				case "down-cancel":
					link.find('.count').html(count - 1);
					link.css('color', '#AAAAAA');
					link.data('action', 'down');
					break;
			}
			
		}
	});

	e.preventDefault();
};


elgg.register_hook_handler('init', 'system', elgg.thumbs.init);
