<?php
/**
 * Minds
 * 
 * THIS PLUGIN IS IN A TERRIBLE STATE AND REQUIRES REFACTORING. MANY THINGS CAN BE MOVED INTO THE CORE..
 */

function minds_init(){
	global $CONFIG;
	$base_url = isset($CONFIG->cdn_url) ? $CONFIG->cdn_url : elgg_get_site_url();	

	//minds\core\views::cache('output/carousel');

	elgg_register_event_handler('pagesetup', 'system', 'minds_pagesetup');
	
	elgg_register_page_handler('news', 'minds_news_page_handler');
		
 	//elgg_extend_view('page/elements/head','minds/meta',1);
		
	elgg_extend_view('register/extend', 'minds/register_extend', 500);
	
	//put the quota in account statistics
	elgg_extend_view('core/settings/statistics', 'minds/quota/statistics', 500);
	
	elgg_extend_view('page/elements/body', 'account/register_popup');

	/**
	 * Ads
	 */
	elgg_extend_view('page/elements/ads', 'minds/ads');

	//plugin for cookie manipulation via JS
	elgg_register_js('jquery-cookie', $base_url .'mod/minds/vendors/jquery-cookie/jquery.cookie.js', 'footer');
	elgg_load_js('jquery-cookie');
	
	//register textarea expander
	elgg_register_js('jquery.autosize', $base_url . 'mod/minds/vendors/autosize/jquery.autosize.js', 'footer');
	
	/**
	 * Carousel js libraries
	 */
	elgg_register_js('carousel',  $base_url . 'mod/minds/vendors/bootstrap-carousel/carousel.min.js');
	elgg_register_css('carousel',  $base_url . 'mod/minds/vendors/bootstrap-carousel/carousel.css');
	elgg_register_js('spectrum', $base_url . 'mod/minds/vendors/spectrum/spectrum.js');
	elgg_register_css('spectrum', $base_url . 'mod/minds/vendors/spectrum/spectrum.css');
	
	/** 
	 * Masonry libraries
	 */
	elgg_register_js('jquery-masonry', $base_url . 'mod/minds/vendors/masonry/masonary.min.js','header',600);
	elgg_load_js('jquery-masonry');
	elgg_register_js('jquery-imagesLoaded', $base_url . 'mod/minds/vendors/masonry/imagesLoaded.min.js','header',700);	
	elgg_load_js('jquery-imagesLoaded');

	//register jquery.form
	elgg_register_js('jquery.form', $base_url . 'mod/minds/vendors/jquery/jquery.form.min.js', 'footer');
	elgg_load_js('jquery.form');
	
	//registers tipsy
	elgg_register_js('jquery.tipsy', $base_url . 'mod/minds/vendors/tipsy/src/javascripts/jquery.tipsy.min.js', 'footer');
	elgg_load_js('jquery.tipsy');
	elgg_register_css('tipsy', $base_url . 'mod/minds/vendors/tipsy/src/stylesheets/tipsy.css', 'footer');
	elgg_load_css('tipsy');
		
	//set the custom index
	elgg_register_plugin_hook_handler('index', 'system','minds_index');
	//make sure users agree to terms
	elgg_register_plugin_hook_handler('action', 'register', 'minds_register_hook');
	
	//add an infinite rather than buttons
	elgg_extend_view('navigation/pagination', 'minds/navigation');
	elgg_register_ajax_view('page/components/ajax_list');
	
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'minds_entity_menu_setup');
	
	//setup the licenses pages
	elgg_register_page_handler('licenses', 'minds_license_page_handler');
	
	//setup the tracking of user quota - on a file upload, increment, on delete, decrement
	elgg_register_event_handler('create', 'object', 'minds_quota_increment');
	elgg_register_event_handler('delete', 'object', 'minds_quota_decrement');
	
	//subscribe users to the minds channel once they register
	elgg_register_plugin_hook_handler('register', 'user', 'minds_subscribe_default', 1);
	
	\elgg_register_admin_menu_item('configure', 'donations', 'monitization');
	
	$actionspath = elgg_get_plugins_path() . "minds/actions";
	elgg_register_action("minds/donations","$actionspath/minds/donations.php");
	elgg_register_action("minds/feature","$actionspath/minds/feature.php");
	elgg_register_action("minds/river/delete", "$actionspath/river/delete.php");
	elgg_register_action("minds/upload", "$actionspath/minds/upload.php");
	elgg_register_action("minds/remind", "$actionspath/minds/remind.php");
	elgg_register_action("minds/remind/external", "$actionspath/minds/remind_external.php");
	elgg_register_action("friends/add", "$actionspath/friends/add.php", "public");
	elgg_register_action("embed/youtube", "$actionspath/embed/youtube.php");
	
	elgg_register_action("carousel/add", "$actionspath/carousel/add.php", "admin");
	elgg_register_action("carousel/delete", "$actionspath/carousel/delete.php", "admin");
	elgg_register_action("carousel/batch", "$actionspath/carousel/batch.php");
	
	elgg_register_admin_menu_item('configure', 'carousel', 'appearance');
	
	elgg_register_page_handler('carousel', function($page){
		global $CONFIG;
		switch($page[0]){
		
			case 'background':
			default:
				$item = get_entity($page[1]);
				$filename = $CONFIG->dataroot . 'carousel/' . $page[1] . $page[4];
				//error_log("LOADING $filename");
				//pre AUG 29 2014
				if(!file_exists($filename))
					$filename = $CONFIG->dataroot . 'carousel/' . $page[1];
				if(!file_exists($filename))
					$filename = $CONFIG->dataroot . 'carousel/' . $page[1] . '.jpg';

                    $finfo    = finfo_open(FILEINFO_MIME);
                    $mimetype = finfo_file($finfo, $filename);
                    finfo_close($finfo);
                    header('Content-Type: '.$mimetype);
                    header('Expires: ' . date('r', time() + 864000));
                    header("Pragma: public");
                    header("Cache-Control: public");

                    echo file_get_contents($filename);

                    exit;				
		}
	});
	

	//make sure all users are subscribed to minds, only run once.
        
    // Set validation true if this is a tier signup
    elgg_register_plugin_hook_handler('register', 'user', function($hook, $type, $return, $params) {
        
        $object = $params['user'];

        if ($object && elgg_instanceof($object, 'user')) {
            if (get_input('returntoreferer') == 'y') // Hack, but sessions seem not to be available here. TODO: Secure this.
                elgg_set_user_validation_status($object->guid, true, 'tier_signup');      
            }
    }, 1);


	elgg_register_page_handler('thumbProxy', function($pages){
		include('thumbnailProxy.php');
		return true;
	});
}        

function minds_index($hook, $type, $return, $params) {
	if ($return == true) {
		// another hook has already replaced the front page
		return $return;
	}
	
	if(!include_once(dirname(__FILE__) . '/pages/index.php')){
		return false;
	}
	
	return true;
}

/**
 * Page handler for news
 *
 * @param array $page
 * @return bool
 * @access private
 */
function minds_news_page_handler($page) {
	global $CONFIG;

	if(is_numeric($page[0])){
		switch($page[1]){
			case 'embed':
				$code = '<div class="minds-post" data-guid="'.$page[0].'"></div><script async src="'.elgg_get_site_url().'js/widgets.0.js"></script>';
				echo '<p>Copy this post to your website by copying the code below</p>';
				echo elgg_view('input/text', array('value'=>$code, 'style'=>'width:640px'));
				
				echo "<h3>Preview</h3>";
				echo $code;
				echo '<script>
						$("input[type=\'text\']").select();
						$("input[type=\'text\']").on("click", function () {
  							 $(this).select();
						});
					</script>';
				return true;
			case 'share':
				$url = elgg_get_site_url() . 'news/'.$page[0];
				echo '<p>Copy the url below to share this post</p>';
				echo elgg_view('input/text', array('value'=>$url, 'style'=>'width:400px'));
				
				echo '<script>
						$("input[type=\'text\']").select();
						$("input[type=\'text\']").on("click", function () {
  							 $(this).select();
						});
					</script>';
				
				return true;
				
		}
		
			$post = new ElggRiverItem($page[0]);
			set_input('show_loading', 'false');
			$user =$post->getSubjectEntity(); 
			elgg_set_page_owner_guid($user->guid);
			$sidebar .= elgg_view('channel/sidebar', array(
				'user' => $user
			));
			$options['count'] = $count;
			$options['items'] = array($post);
			
			if(get_input('async')){
				echo elgg_view('page/elements/head');
				
				set_input('masonry', 'off');
				$options['list_class'] = 'elgg-list minds-list-river x1 no-margin';
				echo elgg_view('page/components/list', $options);
				
				echo $content;
				
				$js = minds\core\resources::getLoaded('js', 'footer');
				foreach ($js as $script) { ?>
					<script type="text/javascript" src="<?php echo $script['src']; ?>"></script>
				<?php
				} ?>
				<script type="text/javascript">
					<?php echo elgg_view('js/initialize_elgg'); ?>
				</script>
			<?php	
				exit;
			} else {
				$options['list_class'] = 'elgg-list minds-list-river x1 mason';
				$content = elgg_view('page/components/list', $options);
			}
			$params = array(
				'content' =>  $content,
				'avatar' => $avatar,
				'sidebar' => $sidebar,
				'filter_context' => $page_filter,
				'filter' => false,
				'header' => $header,
				'class' => 'elgg-river-layout',
			);
			
			$body = elgg_view_layout('fixed', $params);
			
			echo elgg_view_page($title, $body, 'default', array('class'=>'news'));
			return true;
	}

	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

	// make a URL segment available in page handler script
	$page_type = elgg_extract(0, $page, 'friends');
	$page_type = preg_replace('[\W]', '', $page_type);
	if ($page_type == 'owner') {
		$page_type = 'mine';
	}
	set_input('page_type', $page_type);

	// content filter code here
	$entity_type = '';
	$entity_subtype = '';

	require_once(dirname(__FILE__) . "/pages/river.php");
	return true;
}

function minds_register_hook(){
	if (get_input('name', false) == true){
		return false;
	}
	if (get_input('tcs',false) != 'true') {
		register_error(elgg_echo('minds:register:terms:failed'));
		//forward(REFERER);
	}
	//a honey pot
	if (get_input('terms',false) == 'true' || get_input('tac',false) == 'true') {
		register_error(elgg_echo('minds:register:terms:failed'));
	//	forward(REFERER);
	}
	
	return true;
}


function minds_pagesetup(){
	$user = elgg_get_logged_in_user_entity();

	
	// embed support
        $item = ElggMenuItem::factory(array(
                'name' => 'youtube',
                'text' => elgg_echo('minds:embed:youtube'),
                'priority' => 15,
                'data' => array(
                        'view' => 'embed/youtube'
                ),
        ));
        elgg_register_menu_item('embed', $item);


}

/*
 * License Page
 */
function minds_license_page_handler($page){
	include(dirname(__FILE__) . "/pages/license.php");
	return true;
}
		
function minds_quota_increment($event, $object_type, $object) {
	
	$user = elgg_get_logged_in_user_entity();
	
	if(($object->getSubtype() == "file") || ($object->getSubtype() == "image")){
		if($object->size){
			$user->quota_storage = $user->quota_storage + $object->size;
			
			$user->save();
		}
		
	} 
	return;
}

function minds_quota_decrement($event, $object_type, $object) {
	if(($object->getSubtype() == "file") || ($object->getSubtype() == "image")){
		echo $object->size;
	}
	
	if($object->getSubtype() == "kaltura_video"){
		//we need to do kaltura differently because it is a remote uplaod
		//require_once(dirname(dirname(__FILE__)) ."/kaltura_video/kaltura/api_client/includes.php");
		
	}
	return;

}

/**
 * Edit the river menu defaults
 */
function minds_entity_menu_setup($hook, $type, $return, $params) {

		$entity = $params['entity'];
		$handler = elgg_extract('handler', $params, false);
		$context = elgg_get_context();
		$full = elgg_extract('full_view', $params, true);

	if (elgg_is_logged_in()) {		
		$allowedReminds = array('wallpost', 'video', 'album', 'image', 'tidypics_batch', 'blog');
		//Remind button
		if(in_array($entity->getSubtype(), $allowedReminds)){
				$options = array(
						'name' => 'remind',
						'href' => "newsfeed/remind/$entity->guid",
						'text' => '&#59159; Remind',
						'title' => elgg_echo('minds:remind'),
						'is_action' => true,
						'priority' => 1,
					);
				$return[] = ElggMenuItem::factory($options);	
		}
		//Delete button
		elgg_unregister_menu_item('entity', 'delete'); 
		if ($entity->canEdit()) {
			if($context == 'admin'){
				$handler = 'admin/user';
			}
			$options = array(
				'name' => 'delete',
				'href' => "action/$handler/delete?guid={$entity->getGUID()}",
				'text' => '&#10062;',
				'title' => elgg_echo('delete'),
				//'confirm' => elgg_echo('deleteconfirm'),
				'is_action' => true,
				'priority' => 200,
			);
			$return[] = ElggMenuItem::factory($options);
		}
	}
	if(elgg_is_admin_logged_in()){
		if($entity instanceof ElggObject || $entity instanceof ElggGroup || $entity->type == 'user'){
			//feature button
			$options = array(
						'name' => 'feature',
						'href' => "action/minds/feature?guid=$entity->guid",
						'text' => $entity->featured_id ? elgg_echo('un-feature') : elgg_echo('feature'),
						'title' => elgg_echo('feature'),
						'is_action' => true,
						'priority' => 2,
					);
			$return[] = ElggMenuItem::factory($options);
		}	
	} 
	if(!$full){
		elgg_unregister_menu_item('entity', 'edit');
		elgg_unregister_menu_item('entity', 'access');
		foreach($return as $k => $v){
			if($return[$k]->getName() == 'access' || $return[$k]->getName() == 'edit'){;			
				unset($return[$k]);
			}
		}
	}

	return $return;
}

/**
 * Replace urls, hash tags, and @'s by links
 * 
 * @param string $text The text
 * @return string
 */
function minds_filter($text) {
	global $CONFIG;

	$text = ' ' . $text;

	// email addresses
	$text = preg_replace(
				'/(^|[^\w])([\w\-\.]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})/i',
				'$1<a href="mailto:$2@$3">$2@$3</a>',
				$text);

	// links
	$text = parse_urls($text);

	// usernames
	$text = preg_replace(
				'/(^|[^\w])@([\p{L}\p{Nd}._]+)/u',
				'$1<a href="' . $CONFIG->wwwroot . 'channel/$2">@$2</a>',
				$text);

	// hashtags
	$text = preg_replace(
				'/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/',
				'$1<a href="' . $CONFIG->wwwroot . 'search/activity?q=%23$2">#$2</a>',
				$text);

	$text = trim($text);

	return $text;
}

/*
 * New users are automatically subscribed to the minds channel
 */
function minds_subscribe_default($hook, $type, $value, $params){
	$user = elgg_extract('user', $params);

	// no clue what's going on, so don't react.
	if (!$user instanceof ElggUser) {
		return;
	}

	// another plugin is requesting that registration be terminated
	// no need for uservalidationbyadmin
	if (!$value) {
		return $value;
	}
	
	$minds = get_user_by_username('minds');
	
	$user->addFriend($minds->guid);
	
	return $value;
}

function minds_fetch_image($description, $owner_guid=null, $width=null, $height=null) {
  
	global $CONFIG, $post, $posts;
	
        // If description is being passed the actual object, then do something special
        $obj = null;
        $ex_email = null;
        if ($description instanceof ElggObject) {
            $obj = $description;
            if (elgg_instanceof($description, 'object', 'blog')) {
                $description = $obj->description;
                $ex_email = $obj->ex_email;
            }
        }
        
	if($description){
		libxml_use_internal_errors(true);
		$dom = new DOMDocument();
		$dom->strictErrorChecking = FALSE;
		$dom->loadHTML($description);
		$nodes = $dom->getElementsByTagName('img');
		foreach ($nodes as $img) {
			$image = $img->getAttribute('src');
		}
	}
	if(!$image){
		if($owner_guid){
                	$owner = get_entity($owner_guid,'user');
                        if(!$owner)
				return false; //the user doesn't exist
			$image = $owner->getIconURL('large');
                        if (!$image && $ex_email) { // If we've been passed an email address in the metadata, and we can't find an image in the posting, then try and grab a gravatar, or fallback to poster icon
                            $image = minds_fetch_gravatar_url($ex_email, 'large', $owner->getIconURL('large'));
                        }
        	}
  	}
	if($CONFIG->cdn_url){
		$base_url = $CONFIG->cdn_url ? $CONFIG->cdn_url : elgg_get_site_url();
		$image = $base_url . 'thumbProxy?src='. urlencode($image) . '&c=2708';
		if($width){ $image .= '&width=' . $width; } 
	} 
	return $image;
}

/**
 * Retrieve a gravatar url
 * @param type $email
 * @param type $size
 */
function minds_fetch_gravatar_url($email, $size = 'large', $default = null) {
    
    $icon_sizes = elgg_get_config('icon_sizes');
    
    // avatars must be square
    if (is_string($size))
        $size = $icon_sizes[$size]['w']; // If string, then we convert size into pixels

    $hash = md5($email);
    $gravatar = "https://secure.gravatar.com/avatar/$hash.jpg?s=$size";
    if ($default)
        $gravatar .= "&d=" . urlencode($default);
    
    return $gravatar;
}

use phpcassa\ColumnSlice;

function featured_sort($a, $b){
            //return strcmp($b->featured_id, $a->featured_id);
	if ((int)$a->featured_id == (int) $b->featured_id) { //imposisble
          return 0;
        }
	return ((int)$a->featured_id < (int)$b->featured_id) ? 1 : -1;
}

function minds_get_featured($type, $limit = 5, $output = 'entities', $offset = ""){
	global $CONFIG;

	try{
		$namespace = 'object:featured';

		$db = new minds\core\data\call('entities_by_time');
		$guids = $db->getRow($namespace, array('offset'=>$offset, 'limit'=>$limit));
	
		if(!$guids){
			return false;
		}
	}catch(Exception $e){
		var_dump($e);
		//return false;
	}

	if($output == 'guids'){
		return $guids;
	}
        
	$entities = elgg_get_entities(array( 'type' => 'object',
                                        'guids' =>$guids
                                        ));

	usort($entities, 'featured_sort');

	/*$new_list = array();
	foreach($entities as $entity){
		$featured_id = (int) $entity->featured_id;
		$new_list[$featured_id] = $entity;
	}

	return $new_list;*/
	return $entities;
}
elgg_register_event_handler('init','system','minds_init');		
