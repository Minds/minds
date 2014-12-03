<?php

if(elgg_get_plugin_setting('enabled', 'minds_themeconfig') == 'off'){
	return false;
}

$domain = elgg_get_site_url();
switch($vars['type']){
	case 'content-side':
	case 'content-side-single':
		if($ad = elgg_get_plugin_setting('ads-side-1', 'minds_themeconfig') && strlen(elgg_get_plugin_setting('ads-side-1', 'minds_themeconfig')) > 5){
			echo $ad;
		}
                break;
	case 'content-side-single-user-2':
		echo elgg_get_plugin_setting('ads-side-2', 'minds_themeconfig');
		break;
	case 'content-below-banner':
		echo elgg_get_plugin_setting('ads-content-1', 'minds_themeconfig');
		break;
	case 'content-foot-user-1':	
	case 'content-foot':
	case 'content-block-rotator':
         	 break;
}
