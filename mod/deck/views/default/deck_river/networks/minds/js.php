<?php if (0): ?><script><?php endif; ?>
/**
 * Minds Display *
 * @param {array}	json response
 */
elgg.deck_river.mindsDisplayItems = function(response, thread) {
	var output = '',
		elggRiverTemplate = Mustache.compile($('#elgg-river-minds-template').html());
		
	Mustache.compilePartial('elgg-river-minds-template-comments', $('#elgg-river-minds-template-comments').html());

	$.each(response.results, function(key, value) {
		output += elggRiverTemplate(value);
	});
	return $(output);
};

<?php if (0): ?></script><?php endif; ?>

