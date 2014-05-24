<?php
/**
 * Minds Comments
 * 
 * @author Mark Harding (mark@minds.com)
 */

elgg_register_event_handler('init', 'system', function(){
	new minds\plugin\comments\comments();
});