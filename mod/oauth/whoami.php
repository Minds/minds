<?php

  //error_reporting(E_ALL);

//print 0;

  // Load Elgg engine
    require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
oauth_gatekeeper();

$user = get_loggedin_user();

if ($user) {
	print $user->name;
} else {
	print 'Nobody special';
}

?>