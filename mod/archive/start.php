<?php
/**
* Minds Archive 
* @package minds.archive
* @author Mark Harding (mark@minds.com)
**/

elgg_register_event_handler('init','system','minds_archive_init', 1);
function  kaltura_get_thumnail(){
}
function minds_archive_init() {

	global $CONFIG;

	//register subtypes for classes
	add_subtype('object', 'image', 'TidypicsImage');
	add_subtype('object', 'album', 'TidypicsAlbum');
	add_subtype('object', 'kaltura_video', 'KalturaMedia');
	
	elgg_register_plugin_hook_handler('entities_class_loader', 'all', function($hook, $type, $return, $row){
		//var_dump($row);
		if($row->type == 'object' && $row->subtype == 'video')
			return new minds\plugin\archive\entities\video($row);
	});


	if(!elgg_is_active_plugin('tidypics')){
		$ia = elgg_set_ignore_access();
	
		try{	
			$plugin = new ElggPlugin('tidypics');//var_dump($plugin);
			$plugin->activate();
		}catch(Exception $e){
			var_dump($e);
		}

		elgg_set_ignore_access($ia);
	}

	elgg_extend_view('page/elements/head', 'archive/meta');
	
	//list featured in sidebar
	elgg_extend_view('page/elements/sidebar', 'archive/featured');

	elgg_extend_view('js/elgg', 'archive/js');
	elgg_register_js('player', '//vjs.zencdn.net/4.6.3/video.js','head');
	elgg_register_css('player', '//vjs.zencdn.net/4.6.3/video-js.css');
	elgg_register_js('player-res', elgg_get_site_url().'mod/archive/player/video.js.res.js');

    //Loading angularJS
    $angularRoot = elgg_get_site_url() . 'mod/archive/angular/app/';
    $templatesPath = $angularRoot . '/partials';

    $angularSettings = array(
        'templates_path' => $templatesPath
    );
	//what the heck is this???
    //elgg_register_js(array('angular' => $angularSettings), 'setting');

    // include library
    elgg_register_js('angular.min.js' , $angularRoot . 'lib/angular.min.js', 'footer', 200);
	elgg_register_js('angular-route.min.js' , $angularRoot . 'lib/angular-route.min.js','footer', 201);
	   
	//not liking all these scripts...
    elgg_register_js('jquery.ui.widget.js' , $angularRoot . 'lib/jQuery-File-Upload-8.5.0/js/vendor/jquery.ui.widget.js');
    elgg_register_js('jquery.fileupload.js' , $angularRoot . 'lib/jQuery-File-Upload-8.5.0/js/jquery.fileupload.js', 'footer', 201);
    elgg_register_js('jquery.iframe-transport.js' , $angularRoot . 'lib/jQuery-File-Upload-8.5.0/js/jquery.iframe-transport.js');
//    elgg_register_js('http://player.kaltura.com/mwEmbedLoader.php', 'external');

    // include directives
    elgg_register_js('kaltura-embed.js' , $angularRoot . 'directives/kaltura-embed.js', 'footer', 600);
    elgg_register_js('kaltura-upload.js' , $angularRoot . 'directives/kaltura-upload.js', 'footer', 601);
    elgg_register_js('kaltura-thumbnail.js' , $angularRoot . 'directives/kaltura-thumbnail.js', 'footer', 602);


	elgg_register_js('UploadController.js' , $angularRoot . 'controllers/UploadController.js', 'footer', 650);
	
    // include services
    elgg_register_js('KalturaService.js' , $angularRoot . 'services/KalturaService.js', 'footer', 651);
    elgg_register_js('ElggService.js' , $angularRoot . 'services/ElggService.js', 'footer', 652);

    elgg_register_js('app.js' , $angularRoot . 'app.js', 'footer', 700);

    // include css
    elgg_register_css('appstyle.css' , $angularRoot .'css/appstyle.css');
    elgg_register_css('bootstrap.min.css' , $angularRoot . 'lib/bootstrap/css/bootstrap.min.css');

    //site menu
	elgg_register_menu_item('site', array(
			'name' => elgg_echo('minds:archive'),
			'href' => elgg_is_active_plugin('analytics') ? 'archive/trending' : 'archive/all',
			'text' => '<span class="entypo">&#59392;</span> Archive',
			'title' =>  elgg_echo('minds:archive'),
			'priority' => 4
	));
		
	elgg_extend_view('css','archive/css');

	// Photo related JS/CSS
	$js = elgg_get_simplecache_url('js', 'photos/tidypics');
	elgg_register_simplecache_view('js/photos/tidypics');
	elgg_register_js('tidypics', $js, 'footer');

	// $js = elgg_get_simplecache_url('js', 'photos/tagging');
	// elgg_register_simplecache_view('js/photos/tagging');
	// elgg_register_js('tidypics:tagging', $js, 'footer');

	$js = elgg_get_simplecache_url('js', 'photos/lightbox');
	elgg_register_simplecache_view('js/photos/lightbox');
	elgg_register_js('tidypics:lightbox', $js, 'footer');

	elgg_extend_view('css/elgg', 'css/photos/css');

	

	// Extend Elgg JS (extra elgg config variables)
	elgg_extend_view('js/elgg', 'js/photos/config');

	// Load all photo related JS/CSS
	elgg_load_js('tidypics');
	//elgg_load_js('tidypics:tagging');
	elgg_load_js('tidypics:lightbox');
	//elgg_load_js('jquery-fancybox2');
	//elgg_load_css('jquery-fancybox2');


	// Register a page handler, so we can have nice URLs (fallback in case some links go to old kaltura_video)
	elgg_register_page_handler('kaltura_video','minds_archive_page_handler');
	elgg_register_page_handler('archive','minds_archive_page_handler');
	elgg_register_page_handler('file','minds_archive_page_handler');

	// Register a url handler
	elgg_register_entity_url_handler('object', 'kaltura_video','minds_archive_entity_url');
	elgg_register_entity_url_handler('object', 'file','minds_archive_entity_url');
	//tidypics is a separate plugin which we plan on integrating into here shortly. Make sure this plugin is below the tidypics plugin
	elgg_register_entity_url_handler('object', 'image','minds_archive_entity_url');
	elgg_register_entity_url_handler('object', 'album','minds_archive_entity_url');
	elgg_register_entity_url_handler('object', 'video', 'minds_archive_entity_url');
	
	//override icon urls
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'minds_archive_file_icon_url_override');	

	// Listen to notification events and supply a more useful message
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'kaltura_notify_message');

	// Add profile widget
    	elgg_register_widget_type('kaltura_video',elgg_echo('kalturavideo:label:latest'),elgg_echo('kalturavideo:text:widgetdesc'));

	// Register entity type @todo check if this is needed
	//elgg_register_entity_type('object','kaltura_video');
	
	// register actions
	$action_path = elgg_get_plugins_path() . 'archive/actions/';

	//actions for the plugin
	elgg_register_action("archive/delete", $action_path . "delete.php");//new (studio)
	elgg_register_action("archive/download", $action_path . "download.php");
	elgg_register_action("archive/monetize", $action_path . "monetize.php");
	elgg_register_action("archive/feature", $action_path . "feature.php");
	elgg_register_action("archive/save", $action_path . "save.php");
	elgg_register_action("archive/add_album", $action_path . "tidypics/add_album.php");
	
    elgg_register_action("archive/upload", $action_path . "upload.php");
	
    elgg_register_action("archive/deleteElggVideo" , $action_path . "deleteAngular.php");
    elgg_register_action("archive/selectAlbum" , $action_path . "tidypics/album.php");
    elgg_register_action("archive/getKSession" , $action_path . "generateKalturaSession.php");
    elgg_register_action("archive/image/update", $action_path . "tidypics/update_image.php");

	//Setup kaltura
	
	elgg_register_event_handler('pagesetup','system','minds_archive_page_setup');
}

function minds_archive_entity_url($entity) {
		global $CONFIG;
		$title = preg_replace("/\\.[^.\\s]{3,4}$/", "",urlencode($entity->title));

		$guid = $entity->getGUID();
		if($entity->legacy_guid){
			$guid = $entity->legacy_guid;
		}

		return elgg_get_site_url() . "archive/view/" . $guid . "/" . $title;
}


function minds_archive_page_setup() {
	global $CONFIG;

	$page_owner = elgg_get_page_owner_entity();
	$user = elgg_get_logged_in_user_entity();
	
	/**
	 * EMBED 
	 */
	// embed support
	$item = ElggMenuItem::factory(array(
		'name' => 'file',
		'text' => elgg_echo('file'),
		'priority' => 12,
		'data' => array(
			'options' => array(
				'type' => 'object',
				'subtype' => 'file',
			),
		),
	));
	elgg_register_menu_item('embed', $item);

	 // embed support
        $item = ElggMenuItem::factory(array(
                'name' => 'image',
                'text' => elgg_echo('image'),
                'priority' => 11,
                'data' => array(
                        'options' => array(
                                'type' => 'object',
                                'subtype' => 'image',
                        ),
                ),
        ));
        elgg_register_menu_item('embed', $item);

        // embed support
        $item = ElggMenuItem::factory(array(
                'name' => 'video',
                'text' => elgg_echo('kalturavideo:label:videoaudio'),
                'priority' => 10,
                'data' => array(
                        'options' => array(
                                'type' => 'object',
                                'subtype' => 'kaltura_video',
                        ),
                ),
        ));
        elgg_register_menu_item('embed', $item);

	$item = ElggMenuItem::factory(array(
		'name' => 'file_upload',
		'text' => elgg_echo('minds:archive:upload'),
		'priority' => 100,
		'data' => array(
			'view' => 'archive/embed_upload',
		),
	));

	elgg_register_menu_item('embed', $item);
}

function minds_archive_page_handler($page) {
		
	global $CONFIG;
	
	switch($page[0]) {
		case 'all':
			include('pages/archive/all.php');
			break;
		case 'top':
			include('pages/archive/top.php');
			break;
		case 'featured':
			include('pages/archive/featured.php');
			break;	
		case 'trending':
			include('pages/archive/trending.php');
			break;
		case 'api_upload':
			include('pages/archive/api_upload.php');
			break;
         case 'upload':
			if(!elgg_is_logged_in()){
				forward();
			}
	        	//@todo: rename this file upload...
			switch($page[1]){
				case 'album':
					include('pages/archive/add_album.php');
					return true;
					break;
				default:
				include('pages/archive/upload.php');
			}
			break;
		case 'thumbnail':
			$entity = new minds\plugin\archive\entities\video($page[1]);
			$user = $entity->getOwnerEntity();
			if(isset($user->legacy_guid) && $user->legacy_guid)
				$user_guid = $user->legacy_guid;

			 $user_path = date('Y/m/d/', $user->time_created) . $user_guid;


			$data_root = $CONFIG->dataroot;
			$filename = "$data_root$user_path/archive/thumbnails/$entity->guid.jpg";
			$contents = @file_get_contents($filename);
			
			header("Content-type: image/jpeg");
			header('Expires: ' . date('r', strtotime("today+6 months")), true);
			header("Pragma: public");
			header("Cache-Control: public");
			header("Content-Length: " . strlen($contents));
			// this chunking is done for supposedly better performance
			$split_string = str_split($contents, 1024);
			foreach ($split_string as $chunk) {
			echo $chunk;
			}
			exit;
			break;	
		case 'embed':
			set_input('subtype', $page[1]);
			include('pages/archive/embed.php');
			return true;
		case 'show':
		case 'view':
			set_input('guid',$page[1]);
			include('pages/archive/view.php');
			return true;			
			break;
		case 'inline':
			set_input('video_id',$page[1]);
			include('pages/archive/uiVideoInline.php');
			return true;
			break;
		case 'edit':
			set_input('guid',$page[1]);
			set_input('entryid',$page[1]);
			include('pages/archive/edit.php');
			break;
		case 'owner':
			set_input('username', $page[1]);
			include(dirname(__FILE__) . "/pages/archive/owner.php");
			break;
		case 'friends':
		case 'network':
			set_input('username', $page[1]);
			include(dirname(__FILE__) . "/pages/archive/network.php");
			break;	
		// Image lightbox
		case 'image':
			if (!elgg_is_xhr()) {
				return false;
			}
			set_input('guid', $page[1]);
			include(dirname(__FILE__) . "/pages/archive/image_lightbox.php");
			break;
		default:
			set_input('username',$page[0]);
			$user = get_user_by_username($page[0]);
			elgg_set_page_owner_guid($user->guid);
			if (isset($page[0])) {
				switch($page[1]) {
					case 'network':
						include(dirname(__FILE__) . "/pages/archive/network.php");
						break;
					case 'show':
					case 'view':
						set_input('videopost',$page[2]);
						include(dirname(__FILE__) . "/pages/archive/show.php");	
						break;
					default:
						include(dirname(__FILE__) . "/pages/archive/owner.php");
				}
			} else {
				include('pages/archive/all.php');
			}
	}

	return true;
}

//elgg_register_event_handler('upgrade','system','kaltura_setup_init');
/* Setup Kaltura to work with elgg 
 * This is run along side init
 */
function kaltura_setup_init(){
	
	elgg_load_library('archive:kaltura');
	
	$email = elgg_get_plugin_setting('email', 'archive');
	$partnerId = elgg_get_plugin_setting('partner_id', 'archive');
	$password = elgg_get_plugin_setting('password', 'archive');
	
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
				elgg_set_plugin_setting("user",$cmsUser,"archive");
				elgg_set_plugin_setting("password",$password,"archive");
				elgg_set_plugin_setting("subp_id", $subPartnerId,"archive");
				elgg_set_plugin_setting("secret", $secret,"archive");
				elgg_set_plugin_setting("admin_secret", $adminSecret,"archive");

				system_message(elgg_echo("kalturavideo:registeredok"));
			}
			catch(Exception $e) {
				var_dump($e);
				exit;
				system_message('failed');
			}
	}
}
/**
  * KALTURA CONVERT CRON SCRIPT
  *
  */
function minds_archive_kalturavideo_convert($hook, $entity_type, $returnvalue, $params){
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

/**
 * Returns an overall file type from the mimetype
 *
 * @param string $mimetype The MIME type
 * @return string The overall type
 */
function file_get_simple_type($mimetype) {

	switch ($mimetype) {
		case "application/msword":
		case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
			return "document";
			break;
		case "application/pdf":
			return "document";
			break;
		case "application/ogg":
			return "audio";
			break;
	}

	if (substr_count($mimetype, 'text/')) {
		return "document";
	}

	if (substr_count($mimetype, 'audio/')) {
		return "audio";
	}

	if (substr_count($mimetype, 'image/')) {
		return "image";
	}

	if (substr_count($mimetype, 'video/')) {
		return "video";
	}

	if (substr_count($mimetype, 'opendocument')) {
		return "document";
	}

	return "general";
}

/**
 * Override the default entity icon for files
 *
 * Plugins can override or extend the icons using the plugin hook: 'file:icon:url', 'override'
 *
 * @return string Relative URL
 */
function minds_archive_file_icon_url_override($hook, $type, $returnvalue, $params) {
	global $CONFIG;
	$entity = $params['entity'];
	$file = $entity;
	$size = $params['size'];
	if (elgg_instanceof($file, 'object', 'file')) {

		// thumbnails get first priority
		if ($file->thumbnail) {
			$ts = (int)$file->icontime;
			return $CONFIG->cdn_url .  "mod/archive/thumbnail.php?file_guid=$file->guid&size=$size&icontime=$ts";
		}

		$mapping = array(
			'application/excel' => 'excel',
			'application/msword' => 'word',
			'application/ogg' => 'music',
			'application/pdf' => 'pdf',
			'application/powerpoint' => 'ppt',
			'application/vnd.ms-excel' => 'excel',
			'application/vnd.ms-powerpoint' => 'ppt',
			'application/vnd.oasis.opendocument.text' => 'openoffice',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'word',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'excel',
			'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'ppt',
			'application/x-gzip' => 'archive',
			'application/x-rar-compressed' => 'archive',
			'application/x-stuffit' => 'archive',
			'application/zip' => 'archive',

			'text/directory' => 'vcard',
			'text/v-card' => 'vcard',

			'application' => 'application',
			'audio' => 'music',
			'text' => 'text',
			'video' => 'video',
		);

		$mime = $file->mimetype;
		if ($mime) {
			$base_type = substr($mime, 0, strpos($mime, '/'));
		} else {
			$mime = 'none';
			$base_type = 'none';
		}

		if (isset($mapping[$mime])) {
			$type = $mapping[$mime];
		} elseif (isset($mapping[$base_type])) {
			$type = $mapping[$base_type];
		} else {
			$type = 'general';
		}

		if ($size == 'large') {
			$ext = '_lrg';
		} else {
			$ext = '';
		}
		
		$url = $CONFIG->cdn_url . "mod/archive/graphics/icons/{$type}{$ext}.gif";
		$url = elgg_trigger_plugin_hook('file:icon:url', 'override', $params, $url);
		return $url;
	} elseif(elgg_instanceof($entity, 'object', 'kaltura_video')) {
	
		return kaltura_get_thumnail($entity->kaltura_video_id, 120,68, 100);
	}
}


?>
