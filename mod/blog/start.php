<?php
/**
 * Blogs
 *
 * @package Blog
 *
 * @todo
 * - Either drop support for "publish date" or duplicate more entity getter
 * functions to work with a non-standard time_created.
 * - Pingbacks
 * - Notifications
 * - River entry for posts saved as drafts and later published
 */

elgg_register_event_handler('init', 'system', 'blog_init');

/**
 * Init blog plugin.
 */
function blog_init() {

	add_subtype('object', 'blog', 'ElggBlog');
	add_subtype('object', 'scraper');

	elgg_register_library('elgg:blog', elgg_get_plugins_path() . 'blog/lib/blog.php');
	
	// menus
	elgg_register_menu_item('site', array(
		'name' => 'blog',
		'text' => '&#59396;',
		'href' => elgg_is_active_plugin('analytics') ? 'blog/trending' : 'blog/all',
		'title' => elgg_echo('blog:blogs'),
		'priority' => 3
	));
	

	elgg_register_event_handler('upgrade', 'upgrade', 'blog_run_upgrades');

	// add to the main css
	elgg_extend_view('css/elgg', 'blog/css');

	// register the blog's JavaScript
	$blog_js = elgg_get_simplecache_url('js', 'blog/save_draft');
	elgg_register_simplecache_view('js/blog/save_draft');
	elgg_register_js('elgg.blog', $blog_js);

	// routing of urls
	elgg_register_page_handler('blog', 'blog_page_handler');

	// override the default url to view a blog object
	elgg_register_entity_url_handler('object', 'blog', 'blog_url_handler');

	// notifications
	register_notification_object('object', 'blog', elgg_echo('blog:newpost'));
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'blog_notify_message');

	// add blog link to
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'blog_owner_block_menu');

	// pingbacks
	//elgg_register_event_handler('create', 'object', 'blog_incoming_ping');
	//elgg_register_plugin_hook_handler('pingback:object:subtypes', 'object', 'blog_pingback_subtypes');
	
	// Register library for parsing rss
	$lib = elgg_get_plugins_path() . 'blog/vendors/simplepie.inc';
	elgg_register_library('simplepie', $lib);
	//set a cron script to run on the hour
	elgg_register_plugin_hook_handler('cron', 'fifteenmin', 'minds_blog_scraper');
	elgg_register_plugin_hook_handler('permissions_check', 'all', 'minds_blog_scraper_permissions_hook');
	elgg_register_event_handler('pagesetup', 'system', 'blog_pagesetup');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'minds_blog_scraper_entity_menu_setup', 1000);

	// Register for search.
	elgg_register_entity_type('object', 'blog');

	// Add group option
	add_group_tool_option('blog', elgg_echo('blog:enableblog'), true);
	elgg_extend_view('groups/tool_latest', 'blog/group_module');

	// add a blog widget
	elgg_register_widget_type('blog', elgg_echo('blog'), elgg_echo('blog:widget:description'));

	// register actions
	$action_path = elgg_get_plugins_path() . 'blog/actions/blog';
	elgg_register_action('blog/save', "$action_path/save.php");
	elgg_register_action('blog/auto_save_revision', "$action_path/auto_save_revision.php");
	elgg_register_action('blog/delete', "$action_path/delete.php");
	elgg_register_action('scraper/create', "$action_path/../scraper/create.php");
	elgg_register_action('scraper/delete', "$action_path/../scraper/delete.php");

	// entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'blog_entity_menu_setup');

	// ecml
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'blog_ecml_views_hook');
}

/**
 * Dispatches blog pages.
 * URLs take the form of
 *  All blogs:       blog/all
 *  User's blogs:    blog/owner/<username>
 *  Friends' blog:   blog/friends/<username>
 *  User's archives: blog/archives/<username>/<time_start>/<time_stop>
 *  Blog post:       blog/view/<guid>/<title>
 *  New post:        blog/add/<guid>
 *  Edit post:       blog/edit/<guid>/<revision>
 *  Preview post:    blog/preview/<guid>
 *  Group blog:      blog/group/<guid>/all
 *
 * Title is ignored
 *
 * @todo no archives for all blogs or friends
 *
 * @param array $page
 * @return bool
 */
function blog_page_handler($page) {

	elgg_load_library('elgg:blog');

	// forward to correct URL for blog pages pre-1.8
	//blog_url_forwarder($page);

	// push all blogs breadcrumb
	elgg_push_breadcrumb(elgg_echo('blog:blogs'), "blog/all");

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			$user = get_user_by_username($page[1]);
			$params = blog_get_page_content_list($user->guid);
			
			$body = elgg_view_layout('gallery', $params);

			echo elgg_view_page($params['title'], $body);
			
			return true;
			break;
		case 'friends':
			$user = get_user_by_username($page[1]);
			$params = blog_get_page_content_friends($user->guid);
			
			$body = elgg_view_layout('gallery', $params);

			echo elgg_view_page($params['title'], $body);
			
			return true;
			break;
		case 'trending':
			$params = blog_get_trending_page_content_list();
			$body = elgg_view_layout('gallery', $params);

                        echo elgg_view_page($params['title'], $body);

                        return true;
                        break;
		case 'archive':
			$user = get_user_by_username($page[1]);
			$params = blog_get_page_content_archive($user->guid, $page[2], $page[3]);
			break;
		case 'view':
			$params = blog_get_page_content_read($page[1]);
			if(!get_input('offset')){
				set_input('limit',1);
			}
			if(elgg_is_active_plugin('analytics')){
				$trending = blog_get_trending_page_content_list();
				$params['footer'] .= $trending['content'];
			}
			$body = elgg_view_layout('content', $params);
	
			echo elgg_view_page($params['title'], $body);
			
			return true;	
			break;
		case 'read': // Elgg 1.7 compatibility
			register_error(elgg_echo("changebookmark"));
			forward("blog/view/{$page[1]}");
			break;
		case 'add':
			gatekeeper();
			$params = blog_get_page_content_edit($page_type, $page[1]);
			break;
		case 'edit':
			gatekeeper();
			$params = blog_get_page_content_edit($page_type, $page[1], $page[2]);
			break;
		case 'group':
			if ($page[2] == 'all') {
				$params = blog_get_page_content_list(null, $page[1]);
			} else {
				$params = blog_get_page_content_archive($page[1], $page[3], $page[4]);
			}
			
			$body = elgg_view_layout('gallery', $params);

           	echo elgg_view_page($params['title'], $body);
			return true;
			break;
		case 'scrapers':
			if(!elgg_is_logged_in()){
				forward(REFERRER);
			}
			switch($page[1]){
				case 'create':
					set_input('guid', $page[2]);
					include('pages/scraper/create.php');
					return true;
					break;
				case 'mine':
					include('pages/scraper/mine.php');
					return true;
					break;
				default:
					include('pages/scraper/all.php');
					return true;
			}
			break;
		case 'all':
			$params = blog_get_page_content_list();
			
			$body = elgg_view_layout('gallery', $params);

			echo elgg_view_page($params['title'], $body);
			return true;
			break;
		default:
			return false;
	}

	if (isset($params['sidebar'])) {
		$params['sidebar'] .= elgg_view('blog/sidebar', array('page' => $page_type));
	} else {
		$params['sidebar'] = elgg_view('blog/sidebar', array('page' => $page_type));
	}

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($params['title'], $body);
	return true;
}

/**
 * Format and return the URL for blogs.
 *
 * @param ElggObject $entity Blog object
 * @return string URL of blog.
 */
function blog_url_handler($entity) {

    // Override blog url to handle API permalink override
    if (get_input('blog_fullview') == true) { // If we're viewing the full page (bit of a hack)
        if ($entity->ex_permalink)
            return $entity->ex_permalink;
    }
    if (!$entity->getOwnerEntity()) {
        // default to a standard view if no owner.
        return FALSE;
    }

    $guid = $entity->guid;
    if ($entity->legacy_guid) {
        $guid = $entity->legacy_guid;
    }

    $friendly_title = elgg_get_friendly_title($entity->title); //this is to preserve list of shares on older 

    return "blog/view/$guid/$friendly_title";
}

/**
 * Add a menu item to an ownerblock
 */
function blog_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "blog/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('blog', elgg_echo('blog'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->blog_enable != "no") {
			$url = "blog/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('blog', elgg_echo('blog:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Add particular blog links/info to entity menu
 */
function blog_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'blog') {
		return $return;
	}

	$full = elgg_extract('full', $params, false);

	if ($entity->status != 'published' && $full) {
		// draft status replaces access
		foreach ($return as $index => $item) {
			if ($item->getName() == 'access') {
				unset($return[$index]);
			}
		}

		$status_text = elgg_echo("blog:status:{$entity->status}");
		$options = array(
			'name' => 'published_status',
			'text' => "<span>$status_text</span>",
			'href' => false,
			'priority' => 150,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Set the notification message body
 * 
 * @param string $hook    Hook name
 * @param string $type    Hook type
 * @param string $message The current message body
 * @param array  $params  Parameters about the blog posted
 * @return string
 */
function blog_notify_message($hook, $type, $message, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (elgg_instanceof($entity, 'object', 'blog')) {
		$descr = $entity->excerpt;
		$title = $entity->title;
		$owner = $entity->getOwnerEntity();
		return elgg_echo('blog:notification', array(
			$owner->name,
			$title,
			$descr,
			$entity->getURL()
		));
	}
	return null;
}

/**
 * Register blogs with ECML.
 */
function blog_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['object/blog'] = elgg_echo('blog:blogs');

	return $return_value;
}

/**
 * Upgrade from 1.7 to 1.8.
 */
function blog_run_upgrades($event, $type, $details) {
	$blog_upgrade_version = elgg_get_plugin_setting('upgrade_version', 'blogs');

	if (!$blog_upgrade_version) {
		 // When upgrading, check if the ElggBlog class has been registered as this
		 // was added in Elgg 1.8
		if (!update_subtype('object', 'blog', 'ElggBlog')) {
			add_subtype('object', 'blog', 'ElggBlog');
		}

		elgg_set_plugin_setting('upgrade_version', 1, 'blogs');
	}
}

/**
 * Blog pagesetup
 */
function blog_pagesetup(){
	if(elgg_get_context() == 'settings'){
		if(elgg_is_logged_in()){
			$user = elgg_get_logged_in_user_entity();

			$params = array(
				'name' => 'scrapper_settings',
				'text' => elgg_echo('blog:minds:scraper:menu'),
				'href' => "blog/scrapers/mine",
			);
			elgg_register_menu_item('page', $params);
		}
	} elseif(elgg_get_context() == 'blog' && (elgg_get_page_owner_guid() == elgg_get_logged_in_user_guid() || elgg_get_page_owner_guid() == null)){
		$params = array(
                                'name' => 'scrapper_settings',
                                'text' => elgg_echo('blog:minds:scraper:menu'),
                                'href' => "blog/scrapers/mine",
                       		'class'=> 'elgg-button elgg-button-action'
			);
                elgg_register_menu_item('title', $params);

	}
}

/**
 * Hourly scraping cron script
 */
function minds_blog_scraper($hook, $entity_type, $return_value, $params){ 
	elgg_set_ignore_access(true);
	elgg_set_context('scraper');
	$scrapers = elgg_get_entities(array('type'=>'object','subtypes'=>array('scraper'), 'limit'=>0));
	elgg_load_library('simplepie');
	$i = 0;
	foreach($scrapers as $scraper){
		if(!$scraper->getOwnerEntity()){
			continue;
		}
		//if the site was scraped in the last 15 mins then skip
		echo "loading $scraper->title \n";
		//why would it be an array sometimes?? it is though
		if(is_array($scraper->timestamp)){
			$scraper->timestamp = $scraper->timestamp[0];
		}	
		if(isset($scraper->timestamp) && $scraper->timestamp > time() - 300){
			echo "canceling... scraped it withing the last 5 mins \n";
			continue;
		}
		$feed = new SimplePie($scraper->feed_url);
		//swamp the orderring so we do the latest, last
		if(!$feed){
			echo "$scraper->title coult not find any content";
			continue;
		}
		array_reverse($feed);
		
		//we load an array of previously collected rss ids
		$item_ids = unserialize($scraper->item_ids) == false ? array() : unserialize($scraper->item_ids);
		$n = 0;
		foreach($feed->get_items() as $item){
			//if the blog is newer than the scrapers last scrape - but ignore if the timestamp is greater than the time
			// or else we get duplicates!
			if($item->get_date('U') > $scraper->timestamp && $item->get_date('U') < time()){
			} else {
			continue;
			}
			//check if the id is not in the array, if it is then skip
			if(!in_array($item->get_id(true), $item_ids)){
			//if(true){	
			try{
				$blog = new ElggBlog();
				$blog->title = $item->get_title();
				$enclosure = $item->get_enclosure();
				if(strpos($item->get_permalink(), 'youtube.com/')){
					$url = parse_url($item->get_permalink());
					parse_str($url['query']);
					$w = '100%';
					$h = 411;
					$embed = '<iframe id="yt_video" width="'.$w.'" height="'.$h.'" src="//youtube.com/embed/'.$v.'" frameborder="0" allowfullscreen></iframe>';
					$icon = '<img src="//img.youtube.com/vi/'.$v.'/hqdefault.jpg" width="0" height="0"/>';
					//$disclaimer = 'This blog is free & open source, however the embed may not be.';
					$blog->excerpt = $item->get_description(true) ? elgg_get_excerpt($item->get_description(true)) : elgg_get_excerpt($item->get_content()); 
					$blog->description = $embed . $icon . $disclaimer;
				} else {
					$blog->excerpt = $item->get_description(true) ? elgg_get_excerpt($item->get_description(true)) : elgg_get_excerpt($item->get_content());
					$blog->description = $item->get_content() . '<br/><br/> Original: '. $item->get_permalink();				
					if($enclosure){
						$thumb_url = $enclosure->get_thumbnail();
                                                if(strpos($thumb_url, 'liveleak.com/')){
                                                      $thumb_url = str_replace('_thumb_', '_sf_', $thumb_url);
                                          	}
                                                $thumb = elgg_view('output/img', array('src'=>$thumb_url, 'width'=>0, 'height'=>0));
                                                if($player = $enclosure->get_player()) {
							//check for native embed now, if thats not got any content
							if($embed = $enclosure->native_embed()){
								if(strlen($embed) <= 24){
									$player = '<iframe id="blog_video" width="100%" height="411" src="'.$player.'" frameborder="0" allowfullscreen="true"></iframe>';             	
								} else {
									$player = $embed;
								}
							}
							$excerpt = strip_tags($item->get_description());
                                                        $blog->description = $thumb . $player;
                                                } elseif($player = $enclosure->native_embed()) {
							var_dump($player);
							//$blog->description = $thumb . $player;
						}
						$blog->tags = $enclosure->get_keywords();
                                        }
				}
				$blog->owner_guid = $scraper->owner_guid;
				$blog->license = $scraper->license;
				$blog->access_id = 2;
				$blog->status = 'published';
				$blog->rss_item_id = $item->get_id(true);
				if(!$scraper->getOwnerEntity()){
					continue;
				}
				$blog->save();
				echo 'Saved a blog titled: ' . $blog->title;
				add_to_river('river/object/blog/create', 'create', $blog->owner_guid, $blog->getGUID(),2, $item->get_date('U'));
				
				//make timestamp of last blog so we don't have timezone issues...
				//$scraper->timestamp = $item->get_date('U');
				}catch(Exception $e){
				}
				array_push($item_ids, $item->get_id(true));
				$n++;
			}
		}
		//$scraper->timestamp = time();
		if($n != 0){
			$scraper->item_ids = serialize($item_ids);
			$scraper->save();
		}	
	}
	elgg_set_ignore_access(false);
	return $return_value;	
}
/**
 * Scrapper permission hook
 * Allows blogs to be created by logged out users
 */
function minds_blog_scraper_permissions_hook($hook_name, $entity_type, $return_value, $parameters) {
	if (elgg_get_context() == 'scraper') {
		return true;
	}
	return null;
}

/**
 * Scraper entity menu
 */
function minds_blog_scraper_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'scraper') {
		return $return;
	}
	
	foreach ($return as $index => $item) {
		if ($item->getName() != 'delete') {
			unset($return[$index]);
		}
	}
	$options = array(
		'name' => 'edit',
		'text' => "edit",
		'href' => 'blog/scrapers/create/'.$entity->guid,
		'priority' => 150,
	);
	$return[] = ElggMenuItem::factory($options);


	return $return;
}
