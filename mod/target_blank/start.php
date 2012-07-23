<?php
/**
 * Open external links in a new window.
 */

/**
 * Init
 */
function target_blank_init() {
	// extend js
	elgg_extend_view("js/elgg", "target_blank/js");
}

elgg_register_event_handler('init', 'system', 'target_blank_init');