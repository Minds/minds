<?php
/**
 * Minds Inviter
 *
 * @package Minds
 * @author Mark Harding
 *
 */

function minds_inviter_init(){
										
	elgg_extend_view('css/elgg','minds_inviter/css');
	
}

/**
 * @param array $page
 */
function minds_inviter_page_handler($page)
{
	$base = elgg_get_plugins_path() . 'minds_inviter/pages/minds_inviter';
	
	switch ($page[0]) {
			case 'networks':
				require_once "$base/index.php";
				break;
			case 'callback':
				require_once "$base/callback.php";
				break;
			default:
				require_once "$base/index.php";
				break;
		}
	return true;
}

elgg_register_event_handler('init','system','minds_inviter_init');		

?>
