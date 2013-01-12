<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>
elgg.provide('hj.framework.ajax.base');

hj.framework.ajax.base.init = function() {

	window.loader = '<div class="hj-ajax-loader hj-loader-circle"></div>';

	$('.hj-ajaxed-add')
	.unbind('click')
	.bind('click', hj.framework.ajax.base.view);

	$('.hj-ajaxed-edit')
	.unbind('click')
	.bind('click', hj.framework.ajax.base.view);

	$('.hj-ajaxed-view')
	.unbind('click')
	.bind('click', hj.framework.ajax.base.view);

	$('.hj-ajaxed-remove')
	.die()
	.unbind('click')
	.bind('click', hj.framework.ajax.base.remove);

	$('.hj-ajaxed-delete')
	.die()
	.unbind('click')
	.bind('click', hj.framework.ajax.base.remove);

	$('.hj-ajaxed-save')
	.attr('onsubmit','')
	.unbind('submit')
	.bind('submit', hj.framework.ajax.base.save);

	$('.hj-ajaxed-addwidget')
	.unbind('click')
	.bind('click', hj.framework.ajax.base.addwidget);

	$('.elgg-widget-edit > form')
	.die()
	.bind('submit', hj.framework.ajax.base.reloadwidget);

	$('.hj-ajaxed-gallery')
	.unbind('click')
	.bind('click', hj.framework.ajax.base.gallery);

	$('a.elgg-widget-delete-button')
	.die()
	.live('click', elgg.ui.widgets.remove);

	if ($('.elgg-input-date').length) {
		elgg.ui.initDatePicker();
	}

	$('.hj-pagination-next')
	.unbind()
	.bind('click', hj.framework.ajax.base.paginate_next);
}

hj.framework.ajax.base.view = function(event) {
	event.preventDefault();

	var action = $(this).attr('href'),
	values = $(this).data('options'),
	targetContainer = '#'+values.params.target,
	rel = $(this).attr('rel');

	if (rel == 'fancybox') {
		$.fancybox({
			content : window.loader
		});
		values.params.push_context = 'fancybox';
	} else {
		$(targetContainer)
		.fadeIn()
		.html(window.loader);
	}

	elgg.action(action, {
		data : values,
		success : function(output) {
			if (rel == 'fancybox') {
				$.fancybox({
					padding: '30',
					content : output.output.data,
					autoDimensions : false,
					width : values.params.fbox_x || '500',
					height : values.params.fbox_y || '500',
					onComplete : function() {
						elgg.trigger_hook('success', 'hj:framework:ajax');
					}
				});
				$.fancybox.resize();
			} else {
				$(targetContainer)
				.slideDown(800)
				.html(output.output.data);
				elgg.trigger_hook('success', 'hj:framework:ajax');

			}
		}
	});
}

hj.framework.ajax.base.remove = function(event) {
	var action = $(this).attr('href'),
	subjectGuid = $(this).attr('id').replace('hj-ajaxed-remove-', ''),
	targetContainer = 'elgg-object-'+subjectGuid,
	options = $(this).data('options'),
	sourceContainer = options.params.source,
	confirmText = $(this).attr('rel') || elgg.echo('question:areyousure');

	if (!subjectGuid.length) {
		return true;
	}

	$(this).attr('rel', '');
	$(this).attr('confirm', '');

	event.preventDefault();

	if (confirm(confirmText)) {
		elgg.system_message(elgg.echo('hj:framework:processing'));
		elgg.action(action, {
			success : function(output) {
				$('[id="'+targetContainer+'"]')
				.each(function() {
					$(this).fadeOut(800, function() {
						$(this).remove()
					})
				});
				$('[id="'+sourceContainer+'"]')
				.each(function() {
					$(this).fadeOut(800, function() {
						$(this).remove()
					})
				});

			}
		});

	}
}

hj.framework.ajax.base.gallery = function(event) {
	event.preventDefault();

	var values = $(this).data('options'),
	targetContainer = $(this).attr('href'),
	rel = $(this).attr('rel');

	var fbox_content = $(targetContainer).html();
	$.fancybox({
		content : fbox_content,
		autoDimensions : false,
		'width' : values.params.fbox_x || window.width - 200,
		'height' : values.params.fbox_y || window.height - 200,
		'padding' : '20'
	});
}

hj.framework.ajax.base.save = function(event) {
	event.preventDefault();

	var values = new Object();
	values = $.parseJSON($(this).find('input[name="params"]').val());

	if (values.target) {
		var target = values.target;
	} else {
		var target = 'elgg-object-'+values.entity_guid;
	}

	if (hj.framework.fieldcheck.init($(this))) {
		$.fancybox({
			content : window.loader
		});
		$.fancybox.resize();
		values.push_context = 'fancybox';
		var params = ({
			dataType : 'json',
			success : function(output) {
				$.fancybox.close();
				if (values.event == 'update') {
					$('[id="' + target + '"]')
					.each(function() {
						$(this)
						.fadeIn()
						.html(output.output.data);
					});
				} else {
					if (values.dom_order == 'prepend') {
						$('[id="' + target + '"]')
						.each(function() {
							$(this).prepend(output.output.data);
						});
					} else {
						$('[id="' + target + '"]')
						.each(function() {
							$(this).append(output.output.data);
						});
					}
				}
				elgg.trigger_hook('success', 'hj:framework:ajax');
			}
		});

		if ($(this).hasClass('hj-ajaxed-file-save')) {
			params.iframe = true;
		} else {
			params.iframe = false;
		}
		$(this).ajaxSubmit(params);
	} else {
		event.preventDefault();
	}

}

hj.framework.ajax.base.addwidget = function(event) {
	event.preventDefault();
	var action = $(this).attr('href'),
	values = $(this).data('options');

	elgg.system_message(elgg.echo('hj:framework:processing'));

	elgg.action(action, {
		data : values,
		success: function(json) {
			$('#elgg-widget-col-1').prepend(json.output);
			elgg.trigger_hook('success', 'hj:framework:ajax');
		}
	});
}

hj.framework.ajax.base.reloadwidget = function(event) {
	event.preventDefault();
	event.stopPropagation();

	var action = $(this).attr('action');
	var widgetGuid = $(this).parent().attr('id').replace('widget-edit-','');

	var sourceContainer = $('#elgg-widget-'+widgetGuid);

	$(sourceContainer)
	.removeClass()
	.wrap('<div></div>')
	.html(window.loader);

	elgg.action(action, {
		data : $(this).serialize(),
		success : function() {
			elgg.action('action/framework/widget/load', {
				data : {
					e : widgetGuid
				},
				success : function(output) {
					$(sourceContainer)
					.parent('div')
					.slideDown(800)
					.html(output.output.data);
					elgg.trigger_hook('success', 'hj:framework:ajax');

				}
			});
		}
	});
}

hj.framework.ajax.base.paginate_next = function(event) {
	event.preventDefault();
	var button = $(this),
	list = $('#' + $(this).attr('rel'));

	if ($(window).data('ajaxready') == false) return;
	var loader = $('<div>').addClass('hj-ajax-loader hj-loader-circle');
	var last = list.find('li.elgg-item').last();

	if (!last.length) {
		last = list;
	}
	$(window).data('ajaxready', false);
	var time = last.data('timestamp');
	var guid = last.attr('id').replace('elgg-object-', '');
	var pagination_data = $(this).data('options');
	var url = pagination_data.baseurl;

	last.append(loader);

	elgg.getJSON(url, {
		data : {
			sync : 'old',
			time : time,
			guid : guid,
			options : list.data('options'),
			limit : pagination_data.limit
		},
		success : function(output) {
			if (output) {
				$.each(output, function(key, val) {
					list.append(val);
				});
				$(window).data('ajaxready', true);
				elgg.trigger_hook('success', 'hj:framework:ajax');
			}
			if (output && output.length < pagination_data.limit) {
				$(window).data('ajaxready', true);
				button.hide();
			}
			loader.remove();
		}
	});
}

elgg.register_hook_handler('init', 'system', hj.framework.ajax.base.init);
elgg.register_hook_handler('success', 'hj:framework:ajax', elgg.security.refreshToken, 1);
elgg.register_hook_handler('success', 'hj:framework:ajax', elgg.ui.widgets.init);
elgg.register_hook_handler('success', 'hj:framework:ajax', hj.framework.ajax.base.init);

<?php if (FALSE) : ?></script><?php
endif;
?>