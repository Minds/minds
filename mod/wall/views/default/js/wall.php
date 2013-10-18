//<script>
elgg.provide('elgg.wall');
elgg.provide('elgg.wall.news');
elgg.provide('elgg.wall.groups');

elgg.wall.init = function() {
	
	var news = $('form[name=elgg-wall-news]');
	news.on('click', 'input[type=submit]', elgg.wall.news.submit);
	
	var news = $('form[name=elgg-wall-news-groups]');
	news.on('click', 'input[type=submit]', elgg.wall.groups.submit);
	
	var body = $('body');
	body.on('click', 'form[name=elgg-wall] input[type=submit]', elgg.wall.submit);

	// remove the default binding for confirmation since we're doing extra stuff.
	// @todo remove if we add a hook to the requires confirmation callback
	/*form.parent().find('a.elgg-requires-confirmation')
		.click(elgg.wall.deletePost)

		// double whammy for in case the load order changes.
		.unbind('click', elgg.ui.requiresConfirmation)
		.removeClass('elgg-requires-confirmation');*/
		
	$("#wall-textarea").live('keydown', function() {
		elgg.wall.textCounter(this, $("#wall-characters-remaining span"), 1000);
	});
	$("#wall-textarea").live('keyup', function() {
		elgg.wall.textCounter(this, $("#wall-characters-remaining span"), 1000);
	});
	
	//AUTOSIZE TEXTAREAS
	$('body').on('focus', '#wall-textarea',function(e){ $(this).autosize(); }); 
	
	console.log('loaded');
};

elgg.wall.submit = function(e) {
	var form = $(this).parents('form');
	var data = form.serialize();

	elgg.action('wall/add', {
		data: data,
		success: function(json) {
			console.log(json);
			// the action always returns the full ul and li wrapped annotation.
			var ul = form.next('ul.elgg-list-entity');

			if (ul.length < 1) {
				form.parent().append(json.output);
			} else {
				ul.prepend(json.output);
			};
			form.find('textarea').val('');
		}
	});

	e.preventDefault();
};

elgg.wall.news.submit = function(e) {
	var form = $(this).parents('form');
	var data = form.serialize();

	elgg.action('wall/add', {
		data: data,
		success: function(json) {
		//	console.log(json);	
			$('.elgg-list.elgg-list-river.elgg-river').first('.elgg-list.elgg-list-river.elgg-river').prepend(json.output);
			$(document).find('textarea').val('');
		}});

	e.preventDefault();
};

elgg.wall.groups.submit = function(e) {
	var form = $(this).parents('form');
	var data = form.serialize();

	elgg.action('wall/add', {
		data: data,
		success: function(json) {
			
			$list = $(this).parent();
			
			$params = elgg.parse_str(elgg.parse_url(location.href).query);
			$params = $.extend($params, {
				path: location.href,
				items_type: $list.hasClass('elgg-list-entity') ? 'entity' :
							$list.hasClass('elgg-list-river') ? 'river' :
							$list.hasClass('elgg-list-annotation') ? 'annotation' : 'river',
				offset: 0,
				limit: 1,
				subject_guids: "<?php echo elgg_get_logged_in_user_guid(); ?>"
			});
			
			location.reload();
		}
	});

	e.preventDefault();
};


elgg.wall.deletePost = function(e) {
	var link = $(this);
	var confirmText = link.attr('title') || elgg.echo('question:areyousure');

	if (confirm(confirmText)) {
		elgg.action($(this).attr('href'), {
			success: function() {
				var item = $(link).closest('.elgg-item');
				item.remove();
			}
		});
	}

	e.preventDefault();
};

/**
 * Update the number of characters left with every keystroke
 *
 * @param {Object}  textarea
 * @param {Object}  status
 * @param {integer} limit
 * @return void
 */
elgg.wall.textCounter = function(textarea, status, limit) {

	var remaining_chars = limit - $(textarea).val().length;
	status.html(remaining_chars);

	if (remaining_chars < 0) {
		status.parent().css("color", "#D40D12");
		$("#wall-submit-button").attr('disabled', 'disabled');
		$("#wall-submit-button").addClass('elgg-state-disabled');
	} else {
		status.parent().css("color", "");
		$("#wall-submit-button").removeAttr('disabled', 'disabled');
		$("#wall-submit-button").removeClass('elgg-state-disabled');
	}
};

elgg.register_hook_handler('init', 'system', elgg.wall.init);
