<?php
admin_gatekeeper();

if($enabled = get_input('enabled')){
	elgg_set_plugin_setting('enabled', $enabled, 'minds_themeconfig');
}


$ads = get_input('ads');
if(!is_array($ads)){
	register_error('Ads should be an array');
	return false;
}

foreach($ads as $block => $code){
	elgg_set_plugin_setting('ads-'.$block, $code, 'minds_themeconfig');
}
