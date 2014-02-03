<?php
admin_gatekeeper();

$ads = get_input('ads');
if(!is_array($ads)){
	register_error('Ads should be an array');
	return false;
}

foreach($ads as $block => $code){
	elgg_set_plugin_setting('ads-'.$block, $code, 'minds_themeconfig');
}
