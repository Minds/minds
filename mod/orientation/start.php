<?php
/**
 * Bootcamp Renamed to Orientation 
 * A plugin that teaches users how to use Minds.com
 * 
 * @author Mark Harding (mark@minds.com)
 */

elgg_register_event_handler('init', 'system', 'orientation_init');

function orientation_init() {
		
	elgg_extend_view('css/elgg','orientation/css');
	elgg_extend_view('js/elgg','orientation/js');
	
	if(elgg_is_logged_in() && elgg_get_context() == 'news'){
		elgg_extend_view('page/elements/sidebar','orientation/sidebar', 1);
	}
	
	elgg_register_page_handler('orientation', 'orientation_page_handler');
	
	elgg_register_library('orientation', elgg_get_plugins_path() . 'orientation/lib/orientation.php');
	
	global $SESSION;
	//On first login, promt user for bootcamp

	if(elgg_is_logged_in() && !elgg_get_plugin_user_setting('prompted') && !elgg_get_plugin_user_setting('prompted',null,'bootcamp') && !$_SESSION['fb_referrer'] && elgg_get_viewtype() != 'mobile' && ! $SESSION['orientated']){
//		elgg_set_plugin_user_setting('prompted', 'yes');
//		$SESSION['orientated'] = 'yes';
//		forward('orientation');
	}
}

/**
 * @param array $page
 */
function orientation_page_handler($page)
{
	$base = elgg_get_plugins_path() . 'orientation/pages/orientation';
	
	switch ($page[0]) {
			case 'networks':
				require_once "$base/index.php";
				break;
			case 'handler':
				set_input('provider', $page[1]);
				require_once "$base/handler.php";
				break;
			case 'closewindow':
				 echo '<script type="text/javascript">
						     self.close();
						</script>';
				break;
			default:
				require_once "$base/index.php";
				break;
		}
	return true;
}
