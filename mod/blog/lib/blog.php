<?php
/**
 * Blog helper functions
 *
 * @package Blog
 */


/**
 * Get page components to view a blog post.
 *
 * @param int $guid GUID of a blog entity.
 * @return array
 */
function blog_get_page_content_read($guid = NULL) {
	
	header('X-XSS-Protection: 0');

	$return = array();

	$blog = get_entity($guid, 'object');
	
	elgg_set_page_owner_guid($blog->owner_guid);
	
	// no header or tabs for viewing an individual blog
	$return['filter'] = '';
	
	if (!elgg_instanceof($blog, 'object', 'blog')) {
		register_error(elgg_echo('noaccess'));
		$_SESSION['last_forward_from'] = current_page_url();
		forward('');
	}
	
	$menu =  elgg_view_menu('entity', array(
                'entity' => $blog,
                'handler' => 'blog',
                'sort_by' => 'priority',
                'class' => 'elgg-menu-hz',
        ));

	global $CONFIG;
	$excerpt = $blog->excerpt ? strip_tags($blog->excerpt) : elgg_get_excerpt($blog->description) ?:  $CONFIG->site_description; 
	$excerpt = str_replace('"', "'", $excerpt);
	set_input('description', $excerpt);
        set_input('keywords', $blog->tags);

        //set up for facebook
        minds_set_metatags('og:type', 'article');
        minds_set_metatags('og:url',$blog->getPermaURL());
        minds_set_metatags('og:title',$blog->title);
        minds_set_metatags('og:description', $excerpt);
        minds_set_metatags('og:image', minds_fetch_image($blog->description, $blog->owner_guid, 800));
        //setup for twitter
        minds_set_metatags('twitter:card', 'summary');
        minds_set_metatags('twitter:url', $blog->getURL());
        minds_set_metatags('twitter:title', $blog->title);
        minds_set_metatags('twitter:image', minds_fetch_image($blog->description, $blog->owner_guid));
        minds_set_metatags('twitter:description', $excerpt);

	$title = elgg_view_title($blog->title, array('class' => 'heading-main elgg-heading-main'));

	$return['buttons'] = ' ';	
	$return['title'] = $blog->title;
	$return['subtitle'] = elgg_get_friendly_time($blog->time_created); 
	
/*	$container = $blog->getContainerEntity();
	$crumbs_title = $container->name;
	if (elgg_instanceof($container, 'group')) {
		elgg_push_breadcrumb($crumbs_title, "blog/group/$container->guid/all");
	} else {
		elgg_push_breadcrumb($crumbs_title, "blog/owner/$container->username");
	}
*/
	elgg_push_breadcrumb($blog->title);
	$return['content'] = elgg_view_entity($blog, array('full_view' => true));
	 $return['content'] .= elgg_view('minds/ads', array('type'=>'content-below-banner'));
	//check to see if comment are on
	if ($blog->comments_on != 'Off') {
		$return['content'] .= elgg_view_comments($blog);
	}
	$return['content'] .= elgg_view('minds/ads', array('type'=>'content-block-rotator'));
	
	$return['menu'] = $menu;
	//add the sidebar
	$return['sidebar'] = blog_sidebar($blog);
	
	return $return;
}

/**
 * Get page components to list a user's or all blogs.
 *
 * @param int $container_guid The GUID of the page owner or NULL for all blogs
 * @return array
 */
function blog_get_page_content_list($container_guid = NULL) {

	$return = array();

	$return['filter_context'] = $container_guid ? 'mine' : 'all';

	$options = array(
		'type' => 'object',
		'subtype' => 'blog',
		'full_view' => false,
		'limit' => get_input('limit', 8),
		'offset' => get_input('offset', 0)
	);

	$current_user = elgg_get_logged_in_user_entity();

	if ($container_guid) {
		// access check for closed groups
		group_gatekeeper();

		$options['container_guid'] = $container_guid;
		$container = get_entity($container_guid);
		$return['title'] = elgg_echo('blog:title:user_blogs', array($container->name));

		$crumbs_title = $container->name;
		elgg_push_breadcrumb($crumbs_title);

		if ($current_user && ($container_guid == $current_user->guid)) {
			$return['filter_context'] = 'mine';
		} else if (elgg_instanceof($container, 'group')) {
			$return['filter'] = false;
		} else {
			// do not show button or select a tab when viewing someone else's posts
			$return['filter_context'] = 'none';
		}
	} else {
		$return['filter_context'] = 'all';
		$return['title'] = elgg_echo('blog');
		elgg_pop_breadcrumb();
		elgg_push_breadcrumb(elgg_echo('blog:blogs'));
	}

	elgg_register_title_button();

	// show all posts for admin or users looking at their own blogs
	// show only published posts for other users.
	$show_only_published = true;
	if ($current_user) {
		if (($current_user->guid == $container_guid) || $current_user->isAdmin()) {
			$show_only_published = false;
		}
	}
	
	$list = elgg_list_entities($options);
	if (!$list) {
		$return['content'] = elgg_echo('blog:none');
	} else {
		$return['content'] = $list;
	}

	$return['filter'] = elgg_view('page/layouts/content/trending_filter', $return);
	return $return;
}

/** 
 * Get trending page
 */
function blog_get_trending_page_content_list() {
	
	if(!elgg_plugin_exists('analytics')){
		forward(REFERRER);
	}
	
	elgg_register_title_button();
	
	$return = array();
	
      	$return['filter_context'] = 'trending';
	
	$limit = get_input('limit', 8);
	$offset = get_input('offset', '');

	$guids = analytics_retrieve(array('context'=>'blog','limit'=> $limit, 'offset'=>$offset));
	
	if($guids)	{
		$list = elgg_list_entities(array('guids'=>$guids, 'limit'=>$limit, 'offset'=>0, 'full_view'=>false, 'pagination_legacy' => true));
	}        
	
	if (!$list) {
                $return['content'] = elgg_echo('blog:none');
        } else {
                $return['content'] = $list;
        }

        $return['filter'] = elgg_view('page/layouts/content/trending_filter', $return);

	return $return;
}
/**
 * Get page components to list of the user's friends' posts.
 *
 * @param int $user_guid
 * @return array
 */
function blog_get_page_content_friends($user_guid) {

	$user = get_user($user_guid);
	if (!$user) {
		forward('blog/all');
	}

	$limit = get_input('limit', 8);
	$offset = get_input('offset','');
	$return = array();

	$return['filter_context'] = 'friends';
	$return['title'] = elgg_echo('blog:title:friends');

	$crumbs_title = $user->name;
	elgg_push_breadcrumb($crumbs_title, "blog/owner/{$user->username}");
	elgg_push_breadcrumb(elgg_echo('friends'));

	elgg_register_title_button();

	$options = array( 	'type' => 'object',
				'subtype' => 'blog',
				'network' => $user_guid,
				'limit' => $limit,
				'offset' => $offset,
				'full_view' => false
			);
	$list = elgg_list_entities($options);
	if (!$list) {
		$return['content'] = elgg_echo('blog:none');
	} else {
		$return['content'] = $list;
	}

	$return['filter'] = elgg_view('page/layouts/content/trending_filter', $return);

	return $return;
}

/**
 * Get page components to show blogs with publish dates between $lower and $upper
 *
 * @param int $owner_guid The GUID of the owner of this page
 * @param int $lower      Unix timestamp
 * @param int $upper      Unix timestamp
 * @return array
 */
function blog_get_page_content_archive($owner_guid, $lower = 0, $upper = 0) {

	$now = time();

	$owner = get_entity($owner_guid);
	elgg_set_page_owner_guid($owner_guid);

	$crumbs_title = $owner->name;
	if (elgg_instanceof($owner, 'user')) {
		$url = "blog/owner/{$owner->username}";
	} else {
		$url = "blog/group/$owner->guid/all";
	}
	elgg_push_breadcrumb($crumbs_title, $url);
	elgg_push_breadcrumb(elgg_echo('blog:archives'));

	if ($lower) {
		$lower = (int)$lower;
	}

	if ($upper) {
		$upper = (int)$upper;
	}

	$options = array(
		'type' => 'object',
		'subtype' => 'blog',
		'full_view' => FALSE,
	);

	if ($owner_guid) {
		$options['container_guid'] = $owner_guid;
	}

	// admin / owners can see any posts
	// everyone else can only see published posts
	if (!(elgg_is_admin_logged_in() || (elgg_is_logged_in() && $owner_guid == elgg_get_logged_in_user_guid()))) {
		if ($upper > $now) {
			$upper = $now;
		}

		$options['metadata_name_value_pairs'] = array(
			array('name' => 'status', 'value' => 'published')
		);
	}

	if ($lower) {
		$options['created_time_lower'] = $lower;
	}

	if ($upper) {
		$options['created_time_upper'] = $upper;
	}

	$list = elgg_list_entities_from_metadata($options);
	if (!$list) {
		$content = elgg_echo('blog:none');
	} else {
		$content = $list;
	}

	$title = elgg_echo('date:month:' . date('m', $lower), array(date('Y', $lower)));

	return array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
}

/**
 * Get page components to edit/create a blog post.
 *
 * @param string  $page     'edit' or 'new'
 * @param int     $guid     GUID of blog post or container
 * @param int     $revision Annotation id for revision to edit (optional)
 * @return array
 */
function blog_get_page_content_edit($page, $guid = 0, $revision = NULL) {

	elgg_load_js('elgg.blog');

	$return = array(
		'filter' => '',
	);

	$vars = array();
	$vars['id'] = 'blog-post-edit';
	$vars['class'] = 'elgg-form-alt';

	$sidebar = '';
	if ($page == 'edit') {
		$blog = get_entity($guid, 'object');

		$title = elgg_echo('blog:edit');

		if (elgg_instanceof($blog, 'object', 'blog') && $blog->canEdit()) {
			$vars['entity'] = $blog;

			$title .= ": \"$blog->title\"";

	
			$body_vars = blog_prepare_form_vars($blog, $revision);

			elgg_push_breadcrumb($blog->title, $blog->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));
			
			elgg_load_js('elgg.blog');

			$content = elgg_view_form('blog/save', $vars, $body_vars);
		} else {
			$content = elgg_echo('blog:error:cannot_edit_post');
		}
	} else {
		elgg_push_breadcrumb(elgg_echo('blog:add'));
		$body_vars = blog_prepare_form_vars(null);

		$title = elgg_echo('blog:add');
		$content = elgg_view_form('blog/save', $vars, $body_vars);
	}

	$return['title'] = $title;
	$return['content'] = $content;
	$return['sidebar'] = $sidebar;
	return $return;	
}

/**
 * Pull together blog variables for the save form
 *
 * @param ElggBlog       $post
 * @param ElggAnnotation $revision
 * @return array
 */
function blog_prepare_form_vars($post = NULL, $revision = NULL) {

	// input names => defaults
	$values = array(
		'title' => NULL,
		'description' => NULL,
		'status' => 'published',
		'access_id' => ACCESS_DEFAULT,
		'comments_on' => 'On',
		'excerpt' => NULL,
		'tags' => NULL,
		'container_guid' => NULL,
		'guid' => NULL,
		'draft_warning' => '',
		'license' => ''
	);

	if ($post) {
		foreach (array_keys($values) as $field) {
			if (isset($post->$field)) {
				$values[$field] = $post->$field;
			}
		}
		
		if ($post->status == 'draft') {
			$values['access_id'] = $post->future_access;
		}
	}

	if (elgg_is_sticky_form('blog')) {
		$sticky_values = elgg_get_sticky_values('blog');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}
	
	elgg_clear_sticky_form('blog');

	if (!$post) {
		return $values;
	}

	
	return $values;
}

/**
 * Forward to the new style of URLs
 * 
 * Pre-1.7.5
 * Group blogs page: /blog/group:<container_guid>/
 * Group blog view:  /blog/group:<container_guid>/read/<guid>/<title>
 * 1.7.5-1.8
 * Group blogs page: /blog/owner/group:<container_guid>/
 * Group blog view:  /blog/read/<guid>
 * 
 *
 * @param string $page
 */
function blog_url_forwarder($page) {

	$viewtype = elgg_get_viewtype();
	$qs = ($viewtype === 'default') ? "" : "?view=$viewtype";

	$url = "blog/all";

	// easier to work with & no notices
	$page = array_pad($page, 4, "");

	// group usernames
	if (preg_match('~/group\:([0-9]+)/~', "/{$page[0]}/{$page[1]}/", $matches)) {
		$guid = $matches[1];
		$entity = get_entity($guid);
		if (elgg_instanceof($entity, 'group')) {
			if (!empty($page[2])) {
				$url = "blog/view/$page[2]/";
			} else {
				$url = "blog/group/$guid/all";
			}
			register_error(elgg_echo("changebookmark"));
			forward($url . $qs);
		}
	}

	if (empty($page[0])) {
		return;
	}
	
	if($page[0] == 'all'){
		return ;
	}
	
	// user usernames
	$user = get_user_by_username($page[1]);
	if (!$user) {
		return;
	}

	if (empty($page[1])) {
		$page[1] = 'owner';
	}

	switch ($page[1]) {
		case "read":
			$url = "blog/view/{$page[2]}/{$page[3]}";
			break;
		case "archive":
			$url = "blog/archive/{$page[0]}/{$page[2]}/{$page[3]}";
			break;
		case "friends":
			$url = "blog/friends/{$page[0]}";
			break;
		case "new":
			$url = "blog/add/$user->guid";
			break;
		case "owner":
			$url = "blog/owner/{$page[0]}";
			break;
	}

	register_error(elgg_echo("changebookmark"));
	forward($url . $qs);
}
/**
 * Retrieve featured blogs
 * 
 */
function blog_get_featured($limit=6){
/*	global $CONFIG;
	if (class_exists(elasticsearch)) {
		$es = new elasticsearch();
		$es->index = $CONFIG->elasticsearch_prefix . 'featured';
		$data = $es->query('blog', null, 'time_stamp:desc', $limit, 0, array('age'=>3600));
		if($data['hits']['total'] > 0){
			foreach($data['hits']['hits'] as $item){
				$guids[] = $item['_id'];
			}
		}
		if(count($guids) > 0){
			return $featured_blogs = elgg_get_entities(array('guids'=>$guids, 'limit'=>$limit));
		}
	}*/
	return false;
}
/**
 * Adds a sidebar to each blog which show information such as posts by the owner, popular blogs etc
 * @param guid (int)
 */
function blog_sidebar($blog){
	
	//@todo fix bug where json will pick this out...
	if(elgg_get_viewtype() == 'json'){
		return;
	}

	if($blog){	
		$return .= elgg_view('minds/ads', array('type'=>'content-side-single'));
		  $return .= elgg_view('minds/ads', array('type'=>'content-side-single-user-2'));
		//show more posts from this user
		$owners_blogs = elgg_get_entities(array('type'=>'object', 'subtype'=>'blog', 'owner_guid'=>$blog->owner_guid, 'limit'=>2));
		if (($key = array_search($blog, $owners_blogs)) !== false) {
		    unset($owners_blogs[$key]);
		}
		$owners_blogs = elgg_view_entity_list($owners_blogs, array('full_view'=>false, 'sidebar'=>true, 'class'=>'blog-sidebar', 'pagination'=>false, 'masonry'=>false));
		$return .= elgg_view_module('aside', elgg_echo('blog:owner_more_posts', array($blog->getOwnerEntity()->name)), $owners_blogs, array('class'=>'blog-sidebar'));
		
	}

	//show featured blogs
	$featured_blogs = minds_get_featured(null, 4);
	if($featured_blogs){
		$featured_blogs = elgg_view_entity_list($featured_blogs,  array('full_view'=>false, 'sidebar'=>true, 'class'=>'blog-sidebar', 'pagination'=>false, 'masonry'=>false));
		$return .= elgg_view_module('aside', elgg_echo('blog:featured'), $featured_blogs, array('class'=>'blog-sidebar'));	
	}

//	$return .= elgg_view('minds/ads', array('type'=>'content.ad-side'));
	
	return $return;
}
