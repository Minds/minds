<?php
	$limit = get_input('limit', 15);
	$offset = get_input('offset', 0);

	$options = array(
		"type" => "object",
		"subtype" => "spam_login_filter_ip",
		'limit' => $limit,
		'offset' => $offset,
		'count' => TRUE,
	);
		
	$spam_login_filter_ip_list = elgg_get_entities($options);

	if (!$count = elgg_get_entities($options)) {
		echo elgg_echo('spam_login_filter:admin:no_ips');
		return;
	}

	$options['count']  = FALSE;

	$spam_login_filter_ip_list = elgg_get_entities($options);

	
	// setup pagination
	$pagination = elgg_view('navigation/pagination',array(
		'baseurl' => $vars['url'] . 'spam_login_filter/admin/',
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
	));

	echo $pagination;
	
	$form_body .= "<table cellpadding='3px'>";

	foreach ($spam_login_filter_ip_list as $spam_login_filter_ip) {
		$form_body .= elgg_view('spam_login_filter/ip_detail', array('spam_login_filter_ip' => $spam_login_filter_ip));
	}
	
	$form_body .= "</table>";

	//$form_body = elgg_view("page/elements/wrapper", array('body' => $form_body));

	echo elgg_view('input/form', array(
		'body' => $form_body,
		'name' => 'spam_login_filter_delete_ip',
	));
	
	echo $pagination;
?>