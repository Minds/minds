<?php

  // Load Elgg engine
  //require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
// must be logged in to see this page
admin_gatekeeper();

set_context('admin');

$user = get_loggedin_user();

$guid = get_input('guid');

$consumEnt = get_entity($guid);

if ($consumEnt->canEdit()) {

	$area2 .= elgg_view_title(elgg_echo('oauth:consumer:edit:title'));

	$form = elgg_view('oauth/editconsumer', array('entity' => $consumEnt));
	$area2 .= $form;

} else {

	$area2 .= 'Permission Denied';

}
			  
// format
$body = elgg_view_layout("two_column_left_sidebar", array('area2' => $area2));

// Draw page
echo elgg_view_page(elgg_echo('oauth:consumer:edit:title'), $body);
