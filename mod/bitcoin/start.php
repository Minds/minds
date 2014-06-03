<?php
/**
 * Bitcoin support.
 * 
 *  
 * @package Minds.Core
 * @subpackage Plugins
 * @author Marcus Povey <http://www.marcus-povey.co.uk>
 */


elgg_register_event_handler('init','system', function(){	
	$market = new minds\plugin\bitcoin\blockchain();
	$market->init();
});
