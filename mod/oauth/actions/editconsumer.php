<?php

  // must be logged in to use this page

admin_gatekeeper();

$user = get_loggedin_user();
$name = get_input('name');
$desc = get_input('desc');
$callback = get_input('callback');
$revA = get_input('reva', False) ? True : False; // force a boolean
$outbound = get_input('outbound', False) ? True : False; // force a boolean
$key = get_input('key');
$secret = get_input('secret');

$save = get_input('save', False);
$cancel = get_input('cancel', False);

$name = trim($name);
$desc = trim($desc);
$key = trim($key);
$secret = trim($secret);

$guid = get_input('guid');

$consumEnt = get_entity($guid);

if ($save && $consumEnt && $consumEnt->canEdit() && $name && $desc && $key && $secret) {

	$consumEnt->name = $name;
	$consumEnt->description = $desc;
	$consumEnt->callbackUrl = $callback;
	$consumEnt->revA = $revA;
	$consumEnt->consumer_type = ($outbound ? 'outbound' : 'inbound');
	$consumEnt->key = $key;
	$consumEnt->secret = $secret;

	$consumEnt->save(); // probably unnecessary, but safe

	system_message('Your application ' . $name . ' has been updated.');	
} else if ($cancel) {

} else {
	register_error('Permission denied');
}

forward($CONFIG->wwwroot . 'pg/oauth/register');