<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>
	elgg.provide('hj.river');

	hj.river.init = function() {

		var riverList = $('.elgg-list-river');
		var time = 25000;
		if (!window.rivertimer) {
			window.rivertimer = true;
			var refresh_river = window.setTimeout(function(){
				hj.river.timedRefresh(riverList);
			}, time);
		}
	};

	hj.river.timedRefresh = function(object) {
		var first = $('li.elgg-item:first', object);
		if (!first.length) {
			first = object;
		}
		var time = first.data('timestamp');

		elgg.getJSON('activity', {
			data : {
				sync : 'new',
				time : time,
				options : object.data('options')
			},
			success : function(output) {
				if (output) {
					$.each(output, function(key, val) {
						var new_item = $(val).hide();
						object.prepend(new_item.fadeIn(1000));
					});
				}
				window.rivertimer = false;
				elgg.trigger_hook('success', 'hj:framework:ajax');
			}
		});
	}

	elgg.register_hook_handler('init', 'system', hj.river.init);
	elgg.register_hook_handler('success', 'hj:framework:ajax', hj.river.init, 500);
<?php if (FALSE) : ?></script><?php endif; ?>