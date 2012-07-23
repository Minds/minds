<?php
error_reporting(E_ALL ^ E_NOTICE);
$embedded = true;
include_once("services/qooxdoo/elggMan.php");

function westorElggMan_init()
{
	global $CONFIG;

	$adminOnlyOption = westorElggMan_get_plugin_setting('adminOnlyOption', 'westorElggMan');
    $isAdmin = method_exists ( $_SESSION['user'] , "isAdmin" ) ? $_SESSION['user']->isAdmin() : ($_SESSION['user']->admin || $_SESSION['user']->siteadmin);
	if ($adminOnlyOption == 'yes' && ! $isAdmin) {
		return;
	}

	if (westorElggMan_isloggedin()) {
		if (file_exists($CONFIG->path . "mod/westorElggMan/source/index.php")) {
			westorElggMan_menu_add(elgg_echo('ElggMan_'), $CONFIG->wwwroot . "mod/westorElggMan/source/index.php");
		} else {
			westorElggMan_menu_add(elgg_echo('ElggMan_'), $CONFIG->wwwroot . "mod/westorElggMan/build/index.php");
		}
	}
	// register cron avaery minute task
	westorElggMan_register_plugin_hook('cron', 'minute', 'westorElggMan_cron_handler');
	// override permissions for the myaccess context

	westorElggMan_register_plugin_hook('container_permissions_check', 'all', 'westorElggMan_permissions_check');
	westorElggMan_register_plugin_hook('permissions_check', 'all', 'westorElggMan_permissions_check');
	
	//lets have nice urls for minds
	elgg_register_page_handler('contactmanager','elggman_page_handler');
}

function elggman_page_handler($page) {
	
	include('build/index.php');
	
	return true;
}

/**
 * Overrides default permissions for the myaccess context
 */
function westorElggMan_permissions_check($hook_name, $entity_type, $return_value, $parameters) {
	if (westorElggMan_get_context() == 'westorElggMan') {
		return true;
	}
	return null;
}


function westorElggMan_cron_handler($hook, $entity_type, $returnvalue, $params)
{
	global $CONFIG;

	// old elgg bevore 1.7.0
	global $is_admin;
	$is_admin = true;

	if (function_exists("elgg_set_ignore_access")) {
		// new function for access overwrite
		elgg_set_ignore_access(true);
	}

	$context = westorElggMan_get_context();
	westorElggMan_set_context('westorElggMan');
	$prefix = $CONFIG->dbprefix;
	$sql = "SELECT {$prefix}metadata.entity_guid
FROM (({$prefix}metadata AS {$prefix}metadata_1 INNER JOIN {$prefix}metastrings AS {$prefix}metastrings_3
ON {$prefix}metadata_1.name_id = {$prefix}metastrings_3.id) INNER JOIN {$prefix}metastrings
AS {$prefix}metastrings_2 ON {$prefix}metadata_1.value_id = {$prefix}metastrings_2.id) INNER JOIN (({$prefix}metadata INNER JOIN {$prefix}metastrings ON {$prefix}metadata.name_id = {$prefix}metastrings.id) INNER JOIN {$prefix}metastrings AS {$prefix}metastrings_1 ON {$prefix}metadata.value_id = {$prefix}metastrings_1.id) ON {$prefix}metadata_1.entity_guid = {$prefix}metadata.entity_guid
WHERE ((({$prefix}metastrings.string)='waitForSend') AND (({$prefix}metastrings_1.string)='1')
AND (({$prefix}metastrings_3.string)='hiddenTo') AND (({$prefix}metastrings_2.string)<>'1'))";
	// and (scheduled is null || scheduled <= now());
	try {
		$result = get_data($sql);
	} catch (Exception $e) {
		westorElggMan_set_context($context);
		throw new Exception($e);
	}
	if (is_array($result)) {
		$elggMan = new class_elggMan();
		$now = date("Y-m-d H:i:s");
		foreach($result as $row) {
			$message = westorElggMan_get_entity($row->entity_guid);
			if (is_object($message) && $message->getSubtype() == "messages" && ($message->scheduled == null || $message->scheduled <= $now)) {
				$elggMan->sendMsgNow($message);
			}
		}
	}
	westorElggMan_set_context($context);
}

// compatibilty layer 1.7 or 1.8
// wraps function for 1.7 or 1.8
function westorElggMan_register_elgg_event_handler($event, $object, $funct) {
	if (function_exists("elgg_register_event_handler")) {
		elgg_register_event_handler($event, $object, $funct);
	} else {
		register_elgg_event_handler($event, $object, $funct);
	}
}

function westorElggMan_menu_add($menu_name, $menu_url) {
	if (function_exists("elgg_register_menu_item")) {
		return elgg_register_menu_item('site', array('name' => $menu_name, 'text' => $menu_name,
		'href' => $menu_url));
	} else {
		return add_menu($menu_name, $menu_url);
	}
}

function westorElggMan_get_plugin_setting($name, $plugin_id = "") {
	if (function_exists("elgg_get_plugin_setting")) {
		return elgg_get_plugin_setting($name, $plugin_id);
	} else {
		return get_plugin_setting($name, $plugin_id);
	}
}

function westorElggMan_set_plugin_setting($name, $value, $plugin_id = null) {
	if (function_exists("elgg_set_plugin_setting")) {
		return elgg_set_plugin_setting($name, $value, $plugin_id);
	} else {
		return set_plugin_setting($name, $value, $plugin_id);
	}
}

function westorElggMan_register_plugin_hook($hook, $type, $callback, $priority = 500) {
	if (function_exists("elgg_register_plugin_hook_handler")) {
		return elgg_register_plugin_hook_handler($hook, $type, $callback, $priority);
	} else {
		return register_plugin_hook($hook, $type, $callback, $priority);
	}
}

function westorElggMan_set_context($context) {
	if (function_exists("elgg_set_context")) {
		return elgg_set_context($context);
	} else {
		return set_context($context);
	}
}

function westorElggMan_get_context() {
	if (function_exists("elgg_get_context")) {
		return elgg_get_context();
	} else {
		return get_context();
	}
}

function westorElggMan_isloggedin() {
	if (function_exists("elgg_is_logged_in")) {
		return elgg_is_logged_in();
	} else {
		return isloggedin();
	}
}

function westorElggMan_get_entities_from_metadata($meta_name, $meta_value = "",
$entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0,
$order_by = "", $site_guid = 0, $count = FALSE, $case_sensitive = TRUE) {
    if (function_exists("elgg_get_entities_from_metadata")) {
		$options = array();
		$options['metadata_names'] = $meta_name;
		if ($meta_value) {
			$options['metadata_values'] = $meta_value;
		}
		if ($entity_type) {
			$options['types'] = $entity_type;
		}
		if ($entity_subtype) {
			$options['subtypes'] = $entity_subtype;
		}
		if ($owner_guid) {
			if (is_array($owner_guid)) {
				$options['owner_guids'] = $owner_guid;
			} else {
				$options['owner_guid'] = $owner_guid;
			}
		}
		if ($limit) {
			$options['limit'] = $limit;
		}
		if ($offset) {
			$options['offset'] = $offset;
		}
		if ($order_by) {
			$options['order_by'];
		}
		if ($site_guid) {
			$options['site_guid'];
		}
		if ($count) {
			$options['count'] = $count;
		}
		// need to be able to pass false
		$options['metadata_case_sensitive'] = $case_sensitive;
		return elgg_get_entities_from_metadata($options);

    	return elgg_get_entities_from_metadata(array(
          'metadata_name' => $meta_name,
          'metadata_value' => $meta_value,
          'types' => $entity_type,
          'subtypes' => $entity_subtype,
          'limit' => $limit,
          'owner_guid' => $owner_guid
		));
    } else {
    	return get_entities_from_metadata($meta_name, $meta_value, $entity_type, $entity_subtype, $owner_guid,
		$limit, $offset, $order_by,$site_guid, $count, $case_sensitive);
    }
}

function westorElggMan_get_entities($type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10,
$offset = 0, $count = false, $site_guid = 0, $container_guid = null, $timelower = 0,
$timeupper = 0) {
    if (function_exists("elgg_get_entities")) {
		// rewrite owner_guid to container_guid to emulate old functionality
		if ($owner_guid != "") {
			if (is_null($container_guid)) {
				$container_guid = $owner_guid;
				$owner_guid = NULL;
			}
		}
		$options = array();
		if ($type) {
			if (is_array($type)) {
				$options['types'] = $type;
			} else {
				$options['type'] = $type;
			}
		}
		if ($subtype) {
			if (is_array($subtype)) {
				$options['subtypes'] = $subtype;
			} else {
				$options['subtype'] = $subtype;
			}
		}
		if ($owner_guid) {
			if (is_array($owner_guid)) {
				$options['owner_guids'] = $owner_guid;
			} else {
				$options['owner_guid'] = $owner_guid;
			}
		}
		if ($order_by) {
			$options['order_by'] = $order_by;
		}
		// need to pass 0 for all option
		$options['limit'] = $limit;
		if ($offset) {
			$options['offset'] = $offset;
		}
		if ($count) {
			$options['count'] = $count;
		}
		if ($site_guid) {
			$options['site_guids'] = $site_guid;
		}
		if ($container_guid) {
			$options['container_guids'] = $container_guid;
		}
		if ($timeupper) {
			$options['created_time_upper'] = $timeupper;
		}
		if ($timelower) {
			$options['created_time_lower'] = $timelower;
		}
		return elgg_get_entities($options);
    } else {
    	return get_entities($type, $subtype, $owner_guid, $order_by, $limit,
        $offset, $count = false, $site_guid, $container_guid, $timelower,
        $timeupper);
    }
}

function westorElggMan_get_entity($guid) {
    if (function_exists("elgg_get_entity")) {
    	return elgg_get_entity($guid);
    } else {
    	return get_entity($guid);
	}
}

function westorElggMan_count_unread_messages(){
    if (function_exists("messages_count_unread")) {
    	return messages_count_unread();
    } else {
    	return count_unread_messages();
	}
}

function westorElggMan_getIcon($entity, $what) {
   return ( method_exists ( $entity , "getIconURL" ) ? $entity->getIconURL($what) : $entity->getIcon($what) );
}

function westorElggMan_isAdmin($user) {
   return ( method_exists ( $user , "isAdmin" ) ? $user->isAdmin() : $user->admin );
}

function westorElggMan_page_draw($title,$content){
    if (function_exists("elgg_view_page")) {
		echo elgg_view_page($title, $content);
    } else {
    	return page_draw($title,$content);
	}
}

function westorElggMan_get_metadata($guid){
    if (function_exists("elgg_get_matadata")) {
    	return elgg_get_matadata($guid);
    } else {
    	return get_metadata_for_entity($guid);
	}
}

westorElggMan_register_elgg_event_handler('init', 'system', 'westorElggMan_init');
?>
