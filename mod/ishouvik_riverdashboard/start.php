<?php
function ishouvik_riverdashboard_init() {
	//extend some views
	elgg_extend_view('css/elgg', 'css/ishouvik_riverdashboard/css');
}
elgg_register_event_handler('init', 'system', 'ishouvik_riverdashboard_init');
?>
