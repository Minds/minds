<?php
/**
 * CACHE CLEARER FOR MULTISITE
 */
// we want to know if an error occurs
ini_set('display_errors', 1);

require_once(dirname(__FILE__) . "/engine/start.php");

	xcache_coredump();

	set_time_limit(0);
	elgg_invalidate_simplecache();
	elgg_reset_system_cache();

	if(function_exists('apc_clear_cache')){
		apc_clear_cache();
	}


forward();
