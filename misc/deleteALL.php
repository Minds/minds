<?php

require('engine/start.php');

$user = get_user_by_username('mark');
$blogs = elgg_get_entities(array('type'=>'object', 'subtype'=>'kaltura_video','limit'=>0));

foreach($blogs as $blog){
	elgg_set_ignore_access();
	var_dump( $blog->delete() );
}
