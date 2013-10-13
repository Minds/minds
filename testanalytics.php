<?php
/**
 * Elgg index page for web-based applications
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(__FILE__) . "/engine/start.php");

$options = array( 'limit' => get_input('limit', 12),
			'offset' => get_input('offset', 0),
			'context' => 'blog'
);
var_dump(analytics_retrieve($options));
