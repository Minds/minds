<?php
switch($vars['type']){
	case 'content-side':
	case 'content-side-single':
		echo elgg_get_plugin_setting('ads-side-1', 'minds_themeconfig');
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
                echo elgg_get_plugin_setting('ads-content-2', 'minds_themeconfig');
                break;
}
