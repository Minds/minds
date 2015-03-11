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

error_reporting(E_ALL); 
ini_set( 'display_errors','1');

$router = new Minds\Core\router();
$router->route();
exit;
global $CONFIG;

elgg_set_context('main');

//elgg_generate_plugin_entities();

// allow plugins to override the front page (return true to stop this front page code)
if (elgg_trigger_plugin_hook('index', 'system', null, FALSE) != FALSE) {
	exit;
}

if (elgg_is_logged_in()) {
	forward('activity');
}

$content = elgg_view_title(elgg_echo('content:latest'));

$login_box = elgg_view('core/account/login_box');

$params = array(
		'content' => $content,
		'sidebar' => $login_box
);

$body = elgg_view_layout('one_sidebar', $params);
echo elgg_view_page(null, $body);
