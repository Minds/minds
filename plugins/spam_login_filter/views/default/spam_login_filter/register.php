<?php
	//Check if the ip exists			
	$options = array(
		"type" => "object",
		"subtype" => "spam_login_filter_ip",
		"metadata_names" => "ip_address",
        "metadata_values" => $_SERVER['REMOTE_ADDR'],
		"count" => TRUE
	);
	
	elgg_set_ignore_access(true);
	
	$spam_login_filter_ip_list = elgg_get_entities_from_metadata($options);
	
	elgg_set_ignore_access(false);
	
	if ($spam_login_filter_ip_list > 0) {
		ob_end_clean();
		header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
		echo "403 Forbidden";
		exit;
	}	
?>