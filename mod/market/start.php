<?php
/**
 * Minds Market
 * 
 * This is an OOP plugin and is an example of the new structure Minds plugins should follow. 
 * 
 * @package Minds.Core
 * @subpackage Plugins
 * @author Mark Harding (mark@minds.com)
 */


elgg_register_event_handler('init','system', function(){	
	$market = new minds\plugin\market\market();
	$market->init();
});
