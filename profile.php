<?php
/**
 * Elgg index page for web-based applications
 *
 * @package Elgg
 * @subpackage Core
 */
// start profiling
xhprof_enable();
/**
 * Start the Elgg engine
 */
require_once(dirname(__FILE__) . "/engine/start.php");

global $CONFIG;

elgg_set_context('main');

//elgg_generate_plugin_entities();

// allow plugins to override the front page (return true to stop this front page code)
if (elgg_trigger_plugin_hook('index', 'system', null, FALSE) != FALSE) {
	// stop profiler
$xhprof_data = xhprof_disable();

//
// Saving the XHProf run
// using the default implementation of iXHProfRuns.
//
include_once "misc/xhprof/xhprof_lib/utils/xhprof_lib.php";
include_once "misc/xhprof/xhprof_lib/utils/xhprof_runs.php";

$xhprof_runs = new XHProfRuns_Default();

// Save the run under a namespace "xhprof_foo".
//
// **NOTE**:
// By default save_run() will automatically generate a unique
// run id for you. [You can override that behavior by passing
// a run id (optional arg) to the save_run() method instead.]
//
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");

echo "---------------\n".
     "Assuming you have set up the http based UI for \n".
     "XHProf at some address, you can view run at \n".
     "http://<xhprof-ui-address>/index.php?run=$run_id&source=xhprof_foo\n".
     "---------------\n";	

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
