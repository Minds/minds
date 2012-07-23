<?php

  //error_reporting(E_ALL);

//print 0;


  // Load Elgg engine
    require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
print 1;

$user = get_loggedin_user();
print 2;

if ($user) {
	print $user->name;
} else {
	print 'Nobody special';
}

?>