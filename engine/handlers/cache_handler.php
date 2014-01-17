<?php
/**
 * Cache handler.
 *
 * External access to cached CSS and JavaScript views. The cached file URLS
 * should be of the form: cache/<type>/<viewtype>/<name/of/view>.<unique_id>.<type> where
 * type is either css or js, view is the name of the cached view, and
 * unique_id is an identifier that is updated every time the cache is flushed.
 * The simplest way to maintain a unique identifier is to use the lastcache
 * variable in Elgg's config object.
 *
 * @see elgg_register_simplecache_view()
 *
 * @package Elgg.Core
 * @subpackage Cache
 */

// Get dataroot
require_once(dirname(dirname(__FILE__)) . '/settings.php');

global $CONFIG;
$dataroot = $CONFIG->dataroot;
$simplecache_enabled = $CONFIG->simplecache_enabled;

$dirty_request = $_GET['request'];
// only alphanumeric characters plus /, ., and _ and no '..'
$filter = array("options" => array("regexp" => "/^(\.?[_a-zA-Z0-9\/]+)+$/"));
$request = filter_var($dirty_request, FILTER_VALIDATE_REGEXP, $filter);
if (!$request || !$simplecache_enabled) {
	echo 'Cache error: bad request';
	exit;
}

// testing showed regex to be marginally faster than array / string functions over 100000 reps
// it won't make a difference in real life and regex is easier to read.
// <type>/<viewtype>/<name/of/view.and.dots>.<ts>.<type>
$regex = '|([^/]+)/([^/]+)/(.+)\.([^\.]+)\.([^.]+)$|';
preg_match($regex, $request, $matches);

$type = $matches[1];
$viewtype = $matches[2];
$view = $matches[3];
$ts = $matches[4];

// If is the same ETag, content didn't changed.
$etag = $ts;
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
	header("HTTP/1.1 304 Not Modified");
	exit;
}

switch ($type) {
	case 'css':
		header("Content-type: text/css", true);
		$view = "css/$view";
		break;
	case 'js':
		header('Content-type: text/javascript', true);
		$view = "js/$view";
		break;
}

$filename = $dataroot . 'views_simplecache/' . md5($viewtype . $view);

if (file_exists($filename)) {
	$contents = file_get_contents($filename);
} else {
	// someone trying to access a non-cached file or a race condition with cache flushing
	require_once(dirname(dirname(__FILE__)) . "/start.php");
	
	global $CONFIG;
	if (!isset($CONFIG->views->simplecache[$view])) {
		header("HTTP/1.1 404 Not Found");
		exit;
	}

	elgg_set_viewtype($viewtype);
	$contents = elgg_view($view);
}

header('Expires: ' . date('r', strtotime("+6 months")), true);
header("Pragma: public", true);
header("Cache-Control: public", true);
header("ETag: $etag");

echo $contents;
