<?php
/**
 * Register the ElggWebinar class for the object/webinar subtype
 */
global $CONFIG;

$subtype_old = get_subtype_id('object', 'meeting');
if ($subtype_old) {
	
	$subtype_new = add_subtype("object", "webinar", "ElggWebinar");
	update_data("UPDATE {$CONFIG->dbprefix}entities
	SET subtype = '$subtype_new'
	WHERE subtype = '$subtype_old'
	");
	update_data("DELETE FROM {$CONFIG->dbprefix}entity_subtypes
	WHERE id = '$subtype_old'
	");
}else {
	add_subtype("object", "webinar", "ElggWebinar");
}

//change metastrings (webinar object's attributes)
$metastrings = array(	'serverSalt' => 'server_salt',
						'serverURL' => 'server_url',
						'logoutURL' => 'logout_url',
						'welcomeString' => 'welcome_msg',
						'adminPwd' => 'admin_pwd',
						'userPwd' => 'user_pwd',
);
foreach (array_keys($metastrings) as $string_old) {
	$id = get_metastring_id($string_old);
	if ($id){
		$string_new = $metastrings[$string_old];
		update_data("UPDATE {$CONFIG->dbprefix}metastrings
					 SET string = '$string_new'
					 WHERE id = '$id'
					");
	}
}

return $this->includeFile('bbb_api_conf.php');

