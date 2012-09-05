<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

function kaltura_video_init() {
	// Load system configuration
	global $CONFIG,$KALTURA_CURRENT_TINYMCE_FILE;

	//Add the javascript
	elgg_extend_view('page/elements/head', 'kaltura/jscripts');
	elgg_extend_view('page/elements/head', 'kaltura/meta');

	$addbutton = elgg_get_plugin_setting('addbutton', 'kaltura_video');
	if (!$addbutton) $addbutton = 'simple';

	if( in_array($addbutton , array('simple','tinymce')) ) {

		include_once(dirname(__FILE__)."/kaltura/api_client/definitions.php");

		//needs to be loaded after htmlawed
		//this is for allow html <object> tags
		$CONFIG->htmlawed_config['safe'] = false;



		if( $addbutton == 'tinymce'	) {
			set_view_location('input/longtext', $CONFIG->pluginspath . 'kaltura_video/kaltura/views/');
		}
		else {
			elgg_extend_view('input/longtext', 'kaltura/addvideobutton',9);
			//elgg_extend_view('input/longtext','embed/link',10);
		}
	}
	
	// embed support
	/*$item = ElggMenuItem::factory(array(
		'name' => 'studio',
		'text' => elgg_echo('Media'),
		'priority' => 20,
		'data' => array(
			'options' => array(
				'type' => 'object',
				'subtype' => 'kaltura_video',
			),
		),
	));
	elgg_register_menu_item('embed', $item);*/


	// Set up menu for logged in users
	if (elgg_is_logged_in()) {
		//add_menu(elgg_echo('kalturavideo:label:adminvideos'), $CONFIG->wwwroot . "kaltura_video/" . $_SESSION['user']->username);
		elgg_register_menu_item('site', array(
			'name' => elgg_echo('kalturavideo:label:adminvideos'),
			'href' =>  $CONFIG->wwwroot . "archive/" . $_SESSION['user']->username,
			'text' =>  elgg_echo('kalturavideo:label:adminvideos'),
		));
		
		
	// And for logged out users
	} else {
		elgg_register_menu_item('site', array(
			'name' => elgg_echo('kalturavideo:label:adminvideos'),
			'href' =>  $CONFIG->wwwroot . "archive/all",
			'text' =>  elgg_echo('kalturavideo:label:adminvideos'),
		));
	}

	// Extend system CSS with our own styles, which are defined in the blog/css view
	elgg_extend_view('css','kaltura/css');

	// Extend hover-over menu
	elgg_extend_view('profile/menu/links','kaltura/menu');

	// Add to groups context
	//elgg_extend_view('groups/right_column', 'kaltura/groupprofile');
	//if you prefer to see the widgets in the left part of the groups pages:
	//extend_view('groups/left_column','kaltura/groupprofile');

	// Add group menu option
	//add_group_tool_option('kaltura_video',elgg_echo('kalturavideo:enablevideo'),true);

	// Register a page handler, so we can have nice URLs
	//fallback (in case some links go to kaltura_video
	elgg_register_page_handler('kaltura_video','kaltura_video_page_handler');
	//prefered
	elgg_register_page_handler('archive','kaltura_video_page_handler');
	// Register a admin page handler
	elgg_register_page_handler('kaltura_video_admin','kaltura_video_page_handler');

	// Register a url handler
	elgg_register_entity_url_handler('object', 'kaltura_video','kaltura_video_url');

	// Register granular notification for this type
	if (is_callable('register_notification_object')) {
		register_notification_object('object', 'kaltura_video', elgg_echo('kalturavideo:newvideo'));
	}

	// Listen to notification events and supply a more useful message
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'kaltura_notify_message');
	
	//register the cron to convert videos
	
	elgg_register_plugin_hook_handler('cron', 'minute', 'kalturavideo_convert');

	// Add profile widget
    elgg_register_widget_type('kaltura_video',elgg_echo('kalturavideo:label:latest'),elgg_echo('kalturavideo:text:widgetdesc'));

   // Add index widget
	$enableindexwidget = elgg_get_plugin_setting('enableindexwidget', 'kaltura_video');
	if (!$enableindexwidget) $enableindexwidget = 'single';

	if( in_array($enableindexwidget , array('single', 'multi')) ) {
		elgg_extend_view('index/righthandside', 'kaltura/customindex.videos');
	}	

	// Register entity type
	elgg_register_entity_type('object','kaltura_video');
	
	if(elgg_is_active_plugin('htmlawed')){
		//Add to HTMLawed so that we can allow embedding
		elgg_unregister_plugin_hook_handler('validate', 'input', 'htmlawed_filter_tags');
		elgg_register_plugin_hook_handler('validate', 'input', 'kaltura_htmlawed_filter_tags', 1);
	}
	
	// register actions
	$action_path = elgg_get_plugins_path() . 'kaltura_video/actions/';

	//actions for the plugin
	elgg_register_action("kaltura_video/delete", $action_path . "delete.php");//fallback
	elgg_register_action("archive/delete", $action_path . "delete.php");//new (studio)
	elgg_register_action("archive/download", $action_path . "download.php");
	elgg_register_action("kaltura_video/update", $action_path . "update.php");
	elgg_register_action("kaltura_video/upload", $action_path . "upload.php");
	elgg_register_action("kaltura_video/rate",  $action_path . "rate.php");

	if(elgg_is_admin_logged_in()) {
		elgg_register_action("kaltura_video/wizard", $CONFIG->pluginspath . "kaltura_video/actions/wizard.php");
	}
}

/**
 * Returns a more meaningful message
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function kaltura_video_notify_message($hook, $entity_type, $returnvalue, $params)
{
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'kaltura_video'))
	{
		$descr = $entity->description;
		$title = $entity->title;
		if ($method == 'sms') {
			$owner = $entity->getOwnerEntity();
			return $owner->username . ' via video: ' . $title;
		}
		if ($method == 'email') {
			$owner = $entity->getOwnerEntity();
			return $owner->username . ' via video: ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
		}
	}
	return null;
}
function kaltura_video_url($post) {
		global $CONFIG;
		$title = $post->title;
		return elgg_get_site_url() . "archive/show/" . $post->getGUID() . "/" . $title;

}

/**
* Post init gumph.
*/
function kaltura_video_page_setup()
{
	global $CONFIG;

	if (elgg_get_context() == 'admin' && elgg_is_admin_logged_in()) {
		//add_submenu_item(elgg_echo('kalturavideo:admin'), $CONFIG->wwwroot . 'kaltura_video_admin/');
		//elgg_register_menu_item
	}

	$page_owner = elgg_get_page_owner_entity();

	if ((elgg_get_context()=='kaltura_video' || elgg_get_context() == 'archive') && elgg_get_plugin_setting("password","kaltura_video"))
	{
		if ((elgg_get_page_owner_guid() == $_SESSION['guid'] || !elgg_get_page_owner_guid()) && elgg_is_logged_in()) {
		
			elgg_register_menu_item('page', array(
				'name' => elgg_echo('kalturavideo:label:myvideos'),
				'href' =>  $CONFIG->wwwroot."archive/" . $_SESSION['user']->username,
				'text' =>  elgg_echo('kalturavideo:label:myvideos'),
			));
			
			elgg_register_menu_item('page', array(
				'name' => elgg_echo('kalturavideo:label:friendsvideos'),
				'href' => $CONFIG->wwwroot."archive/" . $_SESSION['user']->username ."/friends/",
				'text' =>  elgg_echo('kalturavideo:label:friendsvideos'),
			));
		
		} else if (elgg_get_page_owner_guid()) {
				elgg_register_menu_item('page', array(
					'name' =>sprintf(elgg_echo('kalturavideo:user'),$page_owner->name),
					'href' => $CONFIG->wwwroot."archive/" . $page_owner->username,
					'text' => sprintf(elgg_echo('kalturavideo:user'),$page_owner->name),
				));
			if ($page_owner instanceof ElggUser) { // Sorry groups, this isn't for you.
				elgg_register_menu_item('page', array(
					'name' =>sprintf(elgg_echo('kalturavideo:user:friends'),$page_owner->name),
					'href' => $CONFIG->wwwroot."archive/" . $page_owner->username ."/friends/",
					'text' =>  sprintf(elgg_echo('kalturavideo:user:friends'),$page_owner->name),
				));
			}
		} 
		
			elgg_register_menu_item('page', array(
				'name' =>elgg_echo('kalturavideo:label:allvideos'),
				'href' => $CONFIG->wwwroot."archive/all",
				'text' =>  elgg_echo('kalturavideo:label:allvideos'),
			));
			
			elgg_register_menu_item('page', array(
				'name' =>elgg_echo('kalturavideo:label:trendingvideos'),
				'href' => $CONFIG->wwwroot."archive/trending",
				'text' =>  elgg_echo('kalturavideo:label:trendingvideos'),
			));

		if (can_write_to_container(0, elgg_get_page_owner_guid()) && elgg_is_logged_in())
		{
             if(in_array(elgg_get_plugin_setting("alloweditor","kaltura_video"), array('full', 'simple')))
             {
				//elgg_load_js('lightbox');
				//elgg_load_css('lightbox');
				elgg_register_menu_item('page', array(
					'name' => elgg_echo('kalturavideo:label:newvideo'),
					'href' => '#kaltura_create',
					//'href' => 'studio/upload',
					'text' => elgg_echo('kalturavideo:label:newvideo'),
					'class' => 'pagesactions'
				));
             }
             else {
				elgg_register_menu_item('page', array(
					'name' => elgg_echo('kalturavideo:label:newsimplevideo'),
					'href' => $CONFIG->wwwroot."mod/kaltura_video/newsimplevideo.php",
					'text' => elgg_echo('kalturavideo:label:newsimplevideo'),
					'class' => 'pagesactions'
				));
             }
		}


	}
	// Group submenu option
	if ($page_owner instanceof ElggGroup && get_context() == 'groups') {
		if($page_owner->kaltura_video_enable != "no") {
			elgg_register_menu_item('page', array(
					'name' =>sprintf(elgg_echo('kalturavideo:label:groupvideos'),$page_owner->name),
					'href' => $CONFIG->wwwroot . "archive/" . $page_owner->username,
					'text' =>  sprintf(elgg_echo('kalturavideo:label:groupvideos'),$page_owner->name),
				));
		}
	}

}

/**
* feeds page handler; allows the use of fancy URLs
*
* @param array $page From the page_handler function
* @return true|false Depending on success
*/

function kaltura_video_page_handler($page) {
	global $CONFIG;

	if(!elgg_get_plugin_setting("password","kaltura_video")){
		// If the URL is just 'feeds/username', or just 'feeds/', load the standard feeds index
		include(dirname(__FILE__) . "/missconfigured.php");
		return true;
	}

	// The first component of a blog URL is the username
	if (isset($page[0])) {
		switch($page[0]) {
			case 'all':
				include(dirname(__FILE__) . "/everyone.php");
				return true;
				break;
			case 'trending':
				include(dirname(__FILE__) . "/trending.php");
				return true;
				break;
			case 'api_upload':
				include(dirname(__FILE__) . "/api_upload.php");
				return true;
				break;
			case 'upload':
				include(dirname(__FILE__) . "/inline_upload.php");
				return true;
				break;
			case 'show':
				set_input('videopost',$page[1]);
				include(dirname(__FILE__) . "/show.php");
				return true;
				break;
			case 'edit':
				set_input('videopost',$page[1]);
				include(dirname(__FILE__) . "/edit.php");
				return true;
				break;
			
		default:
			set_input('username',$page[0]);
			$user = get_user_by_username($page[0]);
			elgg_set_page_owner_guid($user->guid);
			if (isset($page[1])) {
					switch($page[1]) {
						case 'friends':
										include(dirname(__FILE__) . "/friends.php");
										return true;
										break;
						case 'show':
										set_input('videopost',$page[2]);
										include(dirname(__FILE__) . "/show.php");
										return true;
										break;
			
						default:
										include(dirname(__FILE__) . "/index.php");
										return true;
					}
				// If the URL is just 'blog/username', or just 'blog/', load the standard blog index
				} else {
					include(dirname(__FILE__) . "/index.php");
					return true;
				}

		}
	} else {
		include(dirname(__FILE__) . "/index.php");
	}

	return true;
}

/* Setup Kaltura to work with elgg 
 * This is run along side init
 */
function kaltura_setup_init(){
	require_once("kaltura/api_client/includes.php");
	$email = elgg_get_plugin_setting('email', 'kaltura_video');
	$partnerId = elgg_get_plugin_setting('partner_id', 'kaltura_video');
	$password = elgg_get_plugin_setting('password', 'kaltura_video');
	
	if ($partnerId && $email && $password) {
		
		$partner = new KalturaPartner();
			//Setup the Kaltura credentials
			try {
				$kmodel = KalturaModel::getInstance();
				$partner = $kmodel->getSecrets($partnerId, $email, $password);

				$partnerId = $partner->id;
				$subPartnerId = $partnerId * 100;
				$secret = $partner->secret;
				$adminSecret = $partner->adminSecret;
				$cmsUser = $partner->adminEmail;

				//Register Elgg vars
				elgg_set_plugin_setting("user",$cmsUser,"kaltura_video");
				elgg_set_plugin_setting("password",$password,"kaltura_video");
				elgg_set_plugin_setting("subp_id", $subPartnerId,"kaltura_video");
				elgg_set_plugin_setting("secret", $secret,"kaltura_video");
				elgg_set_plugin_setting("admin_secret", $adminSecret,"kaltura_video");

				system_message(elgg_echo("kalturavideo:registeredok"));
			}
			catch(Exception $e) {
				
			}
	}
}
/**
  * KALTURA CONVERT CRON SCRIPT
  *
  */
function kalturavideo_convert($hook, $entity_type, $returnvalue, $params){
	login(get_user_by_username('cron'));
	require_once("kaltura/api_client/includes.php");
    //grab list of videos that are not converted.
	$videos = elgg_get_entities_from_metadata( array(
					'type' => 'object',
					'subtype' => 'kaltura_video',
					'limit' => 0,
					//'metadata_name_value_pairs' => array( 'name' => 'converted', value => 'value', 'operand' => '=', 'case_sensitive' => TRUE )
			));
	$kmodel = KalturaModel::getInstance();
	
	$c = 0;
	foreach($videos as $video){
		try{
			/*$mediaEntries = $kmodel->listMixMediaEntries($video->kaltura_video_id);
			$mediaEntry = $mediaEntries[0];*/
			$mediaEntry = $kmodel->getEntry($video->uploaded_id);
			if((!$video->converted || $video->converted != true) && $mediaEntry->status == 2){
				//limit to 20 per minute
				if(++$c > 20){ break; }
				
				$entry = $kmodel->appendMediaToMix($video->kaltura_video_id, $mediaEntry->id);
				
				try {
					$kmodel = KalturaModel::getInstance();
					$mixEntry = new KalturaMixEntry();
					$mixEntry->name = $entry->name;
					$mixEntry->description = $entry->description;
					$mixEntry->tags = $entry->tags;
					$mixEntry->adminTags = KALTURA_ADMIN_TAGS;
					$entry = $kmodel->updateMixEntry($video_id,$mixEntry);
				} catch(Exception $e) {
					$error = $e->getMessage();
				}
				
				$video->converted = true;			
				if($video->save()){
					$resulttext = elgg_echo("kalturavideo:cron:converted:video") . $video->kaltura_video_id;
					//add to the river
					add_to_river('river/object/kaltura_video/update','update',$video->getOwnerGUID(),$video->getGUID());
					
					//increment the owners quota
					$assets = $kmodel->getflavourAssets($video->uploaded_id);
					$asset_vars = get_object_vars($assets[0]);
					$user = get_entity($video->getOwnerGUID());
					$user->quota_storage = $user->quota_storage + ($asset_vars['size']*1024) ;
					
					$user->save;
			
				}
				
			} 
		} catch (Exception $e){
			
		}
	}
	//logout for secruity!
	logout(); 
    return $resulttext;
  }
 /* Extend / override htmlawed */ 
function kaltura_htmlawed_filter_tags($hook, $type, $result, $params) {
	
	$var = $result;

	elgg_load_library('htmlawed');

	$htmlawed_config = array(
		// seems to handle about everything we need.
		'safe' => 0,
		'deny_attribute' => 'class, on*',
		'comments'=>0,
		'cdata'=>0,
		'hook_tag' => 'htmlawed_tag_post_processor',
		'elements'=>'*-applet-iframe-script', // object, embed allowed
		'schemes' => '*:http,https,ftp,news,mailto,rtsp,teamspeak,gopher,mms,callto',
		// apparent this doesn't work.
		// 'style:color,cursor,text-align,font-size,font-weight,font-style,border,margin,padding,float'
	);

	// add nofollow to all links on output
	if (!elgg_in_context('input')) {
		$htmlawed_config['anti_link_spam'] = array('/./', '');
	}

	$htmlawed_config = elgg_trigger_plugin_hook('config', 'htmlawed', null, $htmlawed_config);

	if (!is_array($var)) {
		$result = htmLawed($var, $htmlawed_config);
	} else {
		array_walk_recursive($var, 'htmLawedArray', $htmlawed_config);
		$result = $var;
	}

	return $result;
}

// Make sure the status initialisation function is called on initialisation
// we want this register the last, that's is only to hack the html cleaner
// if we want to allow <object> tags (only with option addbutton enabled)
elgg_register_event_handler('init','system','kaltura_video_init',9999);
//Setup kaltura
elgg_register_event_handler('upgrade','system','kaltura_setup_init');
elgg_register_event_handler('pagesetup','system','kaltura_video_page_setup');

?>
