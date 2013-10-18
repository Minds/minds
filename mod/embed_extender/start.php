<?php
function embed_extender_init()
{
	global $CONFIG;
	
	include_once $CONFIG->pluginspath . 'embed_extender/lib/embedvideo.php';
	
	include_once $CONFIG->pluginspath . 'embed_extender/lib/custom.php';
	include_once $CONFIG->pluginspath . 'embed_extender/lib/embed_extender.php';
	
	
	elgg_register_plugin_hook_handler('view', 'river/object/wall/create', 'embed_extender_rewrite');
	elgg_register_plugin_hook_handler('view', 'river/object/wall/remind', 'embed_extender_rewrite');
	elgg_register_plugin_hook_handler('view', 'object/wallpost', 'embed_extender_rewrite');
	elgg_register_plugin_hook_handler('view', 'object/hjannotation', 'embed_extender_rewrite');
	
	elgg_register_plugin_hook_handler('view', 'object/blog', 'embed_extender_rewrite');
	
	elgg_extend_view('css','embed_extender/css');
	
	//register JS
	$embed_js = elgg_get_simplecache_url('js', 'embed');
	elgg_register_js('embed', $embed_js);
	//register OEMBED
	elgg_register_js('oembed', elgg_get_site_url() . 'mod/embed_extender/vendors/oembed/jquery.oembed.min.js');
	
	// register example hook handler
	// for providing custom video handler (yahoo)
	elgg_register_plugin_hook_handler('embed_extender', 'custom_patterns', 'embed_extender_yahoo_pattern');
	elgg_register_plugin_hook_handler('embed_extender', 'custom_embed', 'embed_extender_yahoo_embed');
}

elgg_register_event_handler('init', 'system', 'embed_extender_init');
