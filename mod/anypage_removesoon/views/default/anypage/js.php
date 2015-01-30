<?php
/**
 * Any page JS
 */
?>
//<script>

elgg.provide('elgg.anypage');

elgg.anypage.init = function() {
	$('#anypage-use-view').change(function() {
		var $this = $(this);

		if ($this.is(":checked")) {
			$('#anypage-description').hide();
			$('#anypage-view-info').show();
		} else {
			$('#anypage-description').show();
			$('#anypage-view-info').hide();
		}
	});

	// @todo HTML5 browsers only. Not sure I care...
	$('#anypage-path').bind('input', elgg.anypage.updatePath);
	
	// open in new tab
	$('a.anypage-updates-on-path-change').click(function(e) {
		e.preventDefault();
		window.open($(this).attr('href'));
	});
}

elgg.anypage.updatePath = function() {
	var $this = $(this);
	var val = $this.val();
	val = val.ltrim('/');
	// we don't have rtrim?
	if (val.lastIndexOf('/') === val.length - 1) {
		val = val.substring(0, val.length - 1);
	}
	val = '/' + val;

	$('a.anypage-updates-on-path-change')
		.attr('href', elgg.normalize_url(val))
		.html(elgg.normalize_url(val));
	$('span.anypage-updates-on-path-change').html(val);
}

elgg.register_hook_handler('init', 'system', elgg.anypage.init);