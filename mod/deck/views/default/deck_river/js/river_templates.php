<?php if (0): ?><script><?php endif; ?>
/**
 *	Elgg-deck_river plugin
 *	@package elgg-deck_river
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-deck_river
 *
 *	Elgg-deck_river river templates js
 *
 */



/**
 * Return html river
 */
elgg.deck_river.displayRiver = function(response, network, thread) {
	var network = network || 'elgg',
		thread = thread || false;

	if (elgg.isString(response.results)) {
		return $(response.results);
	} else if (response.results && response.results.length != 0) {
		return elgg.deck_river[network + 'DisplayItems'](response, thread);
	}
};



/**
 * Javascript template for river element @todo waiting for Elgg core developers to see wich library they will use (ember.js, ...) in elgg 1.9 or 2 and replace it with a js MVC system.
 *
 * @param {array}	json response
 */
elgg.deck_river.elggDisplayItems = function(response, thread) {
	var output = '',
		elggRiverTemplate = Mustache.compile($('#elgg-river-template').html());

	// Put users and groups in global var DataEntities
	$.each(response.users, function(i, entity) {
		elgg.deck_river.storeEntity(entity);
	});

	$.each(response.results, function(key, value) {

		// add user object
		value.user = $.grep(response.users, function(e){ return e.guid == value.subject_guid; })[0];
		// add friendly_time
		value.friendly_time = elgg.friendly_time(value.posted);

		value.text = elgg.isArray(value.message) ? null : value.message;
		if (value.type == 'object' && value.text) {
			value.message = value.text.ParseGroup().ParseURL(true, true).ParseUsername('elgg').ParseHashtag('elgg');
			value.text = $('<div>').html(value.text).text();
		}

		if (value.method == 'site') delete value.method;

		// Remove responses if in thread
		if (thread && !elgg.isNull(value.responses)) delete value.responses;
		if (thread) delete value.menu;

		output += elggRiverTemplate(value);

	});
	return $(output);
};

<?php if (0): ?></script><?php endif; ?>

