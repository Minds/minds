//<script>
elgg.provide('elgg.thumbs');

elgg.thumbs.init = function() {
		
	$('.thumbs-button-up').click(elgg.thumbs.action);
	$('.thumbs-button-down').click(elgg.thumbs.action);
};


elgg.thumbs.action = function(e) {
	var link = $(this);

		elgg.action($(this).attr('href'), {
			success: function(data) {
				link.html(data.output);
			}
		});

	e.preventDefault();
};


elgg.register_hook_handler('init', 'system', elgg.thumbs.init);
