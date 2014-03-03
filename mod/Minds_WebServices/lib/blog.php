<?php
/**
 * Elgg Webservices plugin 
 * Blogs
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
 
 /**
 * Web service to get file list by all users
 *
 * @param string $context eg. all, friends, mine, groups
 * @param int $limit  (optional) default 10
 * @param int $offset (optional) default 0
 * @param int $group_guid (optional)  the guid of a group, $context must be set to 'group'
 * @param string $username (optional) the username of the user default loggedin user
 *
 * @return array $file Array of files uploaded
 */
function blog_get_posts($context,  $limit = 10, $offset = 0,$group_guid, $username) {	
	if(!$username) {
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
		if (!$user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}
		
		if($context == "all"){
		$params = array(
			'types' => 'object',
			'subtypes' => 'blog',
			'limit' => $limit,
			'full_view' => FALSE,
		);
		}
		if($context == "mine" || $context ==  "user"){
		$params = array(
			'types' => 'object',
			'subtypes' => 'blog',
			'owner_guid' => $user->guid,
			'limit' => $limit,
			'full_view' => FALSE,
		);
		}
		if($context == "group"){
		$params = array(
			'types' => 'object',
			'subtypes' => 'blog',
			'container_guid'=> $group_guid,
			'limit' => $limit,
			'full_view' => FALSE,
		);
		}
		$latest_blogs = elgg_get_entities($params);
		
		if($context == "friends"){
		$latest_blogs = get_user_friends_objects($user->guid, 'blog', $limit, $offset);
		}
	
	
	if($latest_blogs) {
		foreach($latest_blogs as $single ) {
			$blog['guid'] = $single->guid;
			$blog['title'] = $single->title;
			$blog['excerpt'] = $single->excerpt;

			$owner = get_entity($single->owner_guid);
			$blog['owner']['guid'] = $owner->guid;
			$blog['owner']['name'] = $owner->name;
			$blog['owner']['username'] = $owner->username;
			$blog['owner']['avatar_url'] = get_entity_icon_url($owner,'small');
			
			$blog['container_guid'] = $single->container_guid;
			$blog['access_id'] = $single->access_id;
			$blog['time_created'] = (int)$single->time_created;
			$blog['time_updated'] = (int)$single->time_updated;
			$blog['last_action'] = (int)$single->last_action;
			$return[] = $blog;
		}
	}
	else {
		$msg = elgg_echo('blog:none');
		throw new InvalidParameterException($msg);
	}
	return $return;
}

expose_function('blog.get_posts',
				"blog_get_posts",
				array(
						'context' => array ('type' => 'string', 'required' => false, 'default' => 'all'),
					  'limit' => array ('type' => 'int', 'required' => false, 'default' => 10),
					  'offset' => array ('type' => 'int', 'required' => false, 'default' => 0),
					  'group_guid' => array ('type'=> 'int', 'required'=>false, 'default' =>0),
					   'username' => array ('type' => 'string', 'required' => false),
					),
				"Get list of blog posts",
				'GET',
				false,
				false);


/**
 * Web service for making a blog post
 *
 * @param string $username username of author
 * @param string $title    the title of blog
 * @param string $excerpt  the excerpt of blog
 * @param string $text     the content of blog
 * @param string $tags     tags for blog
 * @param string $access   Access level of blog
 *
 * @return bool
 */
function blog_save($title, $text, $excerpt, $tags , $access, $container_guid, $author, $email, $profile_url, $permalink) {
	$user = get_loggedin_user();
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	
	$obj = new ElggObject();
	$obj->subtype = "blog";
	$obj->owner_guid = $user->guid;
	$obj->container_guid = $container_guid;
	$obj->access_id = strip_tags($access);
	$obj->method = "api";
	$obj->description = strip_tags($text);
	$obj->title = elgg_substr(strip_tags($title), 0, 140);
	$obj->status = 'published';
	$obj->comments_on = 'On';
	$obj->excerpt = strip_tags($excerpt);
	$obj->tags = strip_tags($tags);
        
        // Save extra stuff (note, using $_REQUEST, because the function variables aren't being correctly populated, and I don't have time to debug it at the moment..)
        $obj->ex_author = $_REQUEST['author'];//$author;
        $obj->ex_email = $_REQUEST['email'];//$email;
        $obj->ex_profile_url = $_REQUEST['profile_url'];//$profile_url;
        $obj->ex_permalink = $_REQUEST['permalink'];//$permalink;
        
        error_log('OAUTH API : ' . print_r($obj, true) . print_r($_REQUEST, true));

	$guid = $obj->save();
	add_to_river('river/object/blog/create',
	'create',
	$user->guid,
	$obj->guid
	);
	$return['success'] = true;
	$return['message'] = elgg_echo('blog:message:saved');
	return $return;
	} 
	
expose_function('blog.save_post',
				"blog_save",
				array(
						'title' => array ('type' => 'string', 'required' => true),
						'text' => array ('type' => 'string', 'required' => true),
						'excerpt' => array ('type' => 'string', 'required' => false),
						'tags' => array ('type' => 'string', 'required' => false, 'default' => "blog"),
						'access' => array ('type' => 'string', 'required' => false, 'default'=>ACCESS_PUBLIC),
						'container_guid' => array ('type' => 'int', 'required' => false, 'default' => 0),
                                    
                                                // The following fields allow for posts written elsewhere, but posted via one minds user, to be displayed with the correct information
                                                // this is primarily used by the minds wordpress plugin and bridge
                                                'author' => array ('type' => 'string', 'required' => false, 'default' => ''),
                                                'email' => array ('type' => 'string', 'required' => false, 'default' => ''),
                                                'profile_url' => array ('type' => 'string', 'required' => false, 'default' => ''),
                                                'permalink' => array ('type' => 'string', 'required' => false, 'default' => ''),
					),
				"Post a blog post",
				'POST',
				true,
				false);
/**
 * Web service for delete a blog post
 *
 * @param string $guid     GUID of a blog entity
 * @param string $username Username of reader (Send NULL if no user logged in)
 * @param string $password Password for authentication of username (Send NULL if no user logged in)
 *
 * @return bool
 */
function blog_delete_post($guid, $username) {
	$return = array();
	$blog = get_entity($guid);
	$return['success'] = false;
	if (!elgg_instanceof($blog, 'object', 'blog')) {
		throw new InvalidParameterException('blog:error:post_not_found');
	}
	
	$user = get_user_by_username($username);
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	$blog = get_entity($guid);
	if($user->guid!=$blog->owner_guid) {
		$return['message'] = elgg_echo('blog:message:notauthorized');
	}

	if (elgg_instanceof($blog, 'object', 'blog') && $blog->canEdit()) {
		$container = get_entity($blog->container_guid);
		if ($blog->delete()) {
			$return['success'] = true;
			$return['message'] = elgg_echo('blog:message:deleted_post');
		} else {
			$return['message'] = elgg_echo('blog:error:cannot_delete_post');
		}
	} else {
		$return['message'] = elgg_echo('blog:error:post_not_found');
	}
	
	return $return;
}
	
expose_function('blog.delete_post',
				"blog_delete_post",
				array('guid' => array ('type' => 'string'),
						'username' => array ('type' => 'string'),
					),
				"Read a blog post",
				'POST',
				true,
				false);			
/**
 * Web service for read a blog post
 *
 * @param string $guid     GUID of a blog entity
 * @param string $username Username of reader (Send NULL if no user logged in)
 * @param string $password Password for authentication of username (Send NULL if no user logged in)
 *
 * @return string $title       Title of blog post
 * @return string $content     Text of blog post
 * @return string $excerpt     Excerpt
 * @return string $tags        Tags of blog post
 * @return string $owner_guid  GUID of owner
 * @return string $access_id   Access level of blog post (0,-2,1,2)
 * @return string $status      (Published/Draft)
 * @return string $comments_on On/Off
 */
function blog_get_post($guid, $username) {
	$return = array();
	$blog = get_entity($guid);

	if (!elgg_instanceof($blog, 'object', 'blog')) {
		$return['content'] = elgg_echo('blog:error:post_not_found');
		return $return;
	}
	
	$user = get_user_by_username($username);
	if ($user) {
		if (!has_access_to_entity($blog, $user)) {
			$return['content'] = elgg_echo('blog:error:post_not_found');
			return $return;
		}
		
		if ($blog->status!='published' && $user->guid!=$blog->owner_guid) {
			$return['content'] = elgg_echo('blog:error:post_not_found');
			return $return;
		}
	} else {
		if($blog->access_id!=2) {
			$return['content'] = elgg_echo('blog:error:post_not_found');
			return $return;
		}
	}

	$return['title'] = htmlspecialchars($blog->title);
	$return['content'] = $blog->description;
	$return['excerpt'] = $blog->excerpt;
	$return['tags'] = $blog->tags;
	$return['owner_guid'] = $blog->owner_guid;
	$return['access_id'] = $blog->access_id;
	$return['status'] = $blog->status;
	$return['comments_on'] = $blog->comments_on;
	return $return;
}
	
expose_function('blog.get_post',
				"blog_get_post",
				array('guid' => array ('type' => 'string'),
						'username' => array ('type' => 'string', 'required' => false),
					),
				"Read a blog post",
				'GET',
				false,
				false);
/**
 * Web service to retrieve comments on a blog post
 *
 * @param string $guid blog guid
 * @param string $limit    Number of users to return
 * @param string $offset   Indexing offset, if any
 *
 * @return array
 */    				
function blog_get_comments($guid, $limit = 10, $offset = 0){
	$blog = get_entity($guid);
	
$options = array(
	'annotations_name' => 'generic_comment',
	'guid' => $guid,
	'limit' => $limit,
	'pagination' => false,
	'reverse_order_by' => true,
);

	$comments = elgg_get_annotations($options);

	if($comments){
	foreach($comments as $single){
		$comment['guid'] = $single->id;
		$comment['description'] = strip_tags($single->value);
		
		$owner = get_entity($single->owner_guid);
		$comment['owner']['guid'] = $owner->guid;
		$comment['owner']['name'] = $owner->name;
		$comment['owner']['username'] = $owner->username;
		$comment['owner']['avatar_url'] = get_entity_icon_url($owner,'small');
		
		$comment['time_created'] = (int)$single->time_created;
		$return[] = $comment;
	}
} else {
		$msg = elgg_echo('generic_comment:none');
		throw new InvalidParameterException($msg);
	}
	return $return;
}
expose_function('blog.get_comments',
				"blog_get_comments",
				array(	'guid' => array ('type' => 'string'),
						'limit' => array ('type' => 'int', 'required' => false, 'default' => 10),
						'offset' => array ('type' => 'int', 'required' => false, 'default' => 0),
						
					),
				"Get comments for a blog post",
				'GET',
				false,
				false);	
/**
 * Web service to comment on a post
 *
 * @param int $guid blog guid
 * @param string $text
 * @param int $access_id
 *
 * @return array
 */    				
function blog_post_comment($guid, $text){
	
	$entity = get_entity($guid);

	$user = elgg_get_logged_in_user_entity();

	$annotation = create_annotation($entity->guid,
								'generic_comment',
								$text,
								"",
								$user->guid,
								$entity->access_id);


	if($annotation){
		// notify if poster wasn't owner
		if ($entity->owner_guid != $user->guid) {

			notify_user($entity->owner_guid,
					$user->guid,
					elgg_echo('generic_comment:email:subject'),
					elgg_echo('generic_comment:email:body', array(
						$entity->title,
						$user->name,
						$text,
						$entity->getURL(),
						$user->name,
						$user->getURL()
				))
			);
		}
	
		$return['success']['message'] = elgg_echo('generic_comment:posted');
	} else {
		$msg = elgg_echo('generic_comment:failure');
		throw new InvalidParameterException($msg);
	}
	return $return;
}
expose_function('blog.post_comment',
				"blog_post_comment",
				array(	'guid' => array ('type' => 'int'),
						'text' => array ('type' => 'string'),
					),
				"Post a comment on a blog post",
				'POST',
				true,
				true);	
