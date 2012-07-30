<?php
/**
 *
 */

?>

elgg.provide('elgg.tidypics');

elgg.tidypics.init = function() {

	if ($(".tidypics-lightbox").length) {
		$(".tidypics-lightbox").fancybox({'type': 'image'});
	}

	$("#tidypics-sort").sortable({
		opacity: 0.7,
		revert: true,
		scroll: true
	});

	$('.elgg-form-photos-album-sort').submit(function() {
		var tidypics_guids = [];
		$("#tidypics-sort li").each(function(index) {
			tidypics_guids.push($(this).attr('id'));
		});
		$('input[name="guids"]').val(tidypics_guids.toString());
	});
};

elgg.register_hook_handler('init', 'system', elgg.tidypics.init);
