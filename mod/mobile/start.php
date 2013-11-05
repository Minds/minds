<?php
/**
 * Elgg Mobile
 * A Mobile Client For Elgg
 *
 * @package Mobile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Mark Harding
 * @link http://kramnorth.com
 *
 */

elgg_register_viewtype_fallback('mobile');
elgg_register_event_handler('init','system','mobile_init');	
	
function mobile_init(){

	//if the site isn't using varnish then detect normally	
	if(!isset($_SERVER['HTTP_X_UA_DEVICE'])){
		mobile_detect();
	} elseif($_SERVER['HTTP_X_UA_DEVICE'] == 'mobile') {
		elgg_set_viewtype('mobile');
	}
						
	elgg_extend_view('css/elgg','mobile/css');
	
	//set our default index page
	if(elgg_get_viewtype() == 'mobile'){
		
		$url = elgg_get_simplecache_url('css', 'mobile');
       		 elgg_register_css('minds.mobile', $url); 
	
		elgg_register_plugin_hook_handler('index', 'system','mobile_main_handler');
	
		elgg_register_simplecache_view('mobile');
	
		elgg_register_js('bootstrap', elgg_get_site_url() .'mod/mobile/vendors/bootstrap/js/bootstrap.min.js', 'footer');
		elgg_register_css('bootstrap',elgg_get_site_url() .'mod/mobile/vendors/bootstrap/css/bootstrap.min.css',1);
		elgg_register_css('bootstrap-responsive',elgg_get_site_url().'mod/mobile/vendors/bootstrap/css/bootstrap-responsive.min.css',2);
	}
}
	
function mobile_main_handler($hook, $type, $return, $params) {
	if ($return == true) {
		// another hook has already replaced the front page
		return $return;
	}
	
	if(!include_once(dirname(__FILE__) . '/pages/main.php')){
		return false;
	}
	
	return true;
}
	
function mobile_detect(){
	$useragent= strtolower ( $_SERVER['HTTP_USER_AGENT'] );	
	//detect if there is a mobile device
	if(preg_match('/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/',$useragent)||preg_match('/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /',$useragent && !strstr($useragent,'ipad'))){
		$mobile =  true;
	} else {
		$mobile = false;
	}

	//if there is a mobile device
	if($mobile == true){
		if($_SESSION['view_desktop']){
			elgg_extend_view('page/elements/head','mobile/desktop');
		} else {
			elgg_set_viewtype('mobile');
		}
	}
}

