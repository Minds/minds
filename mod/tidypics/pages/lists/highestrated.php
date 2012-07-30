<?php
	/**
	 * Tidypics Friends Albums Listing
	 * 
	 */

	include_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php";

	//if no friends were requested, see world pictures instead, or redirect to user's friends
/*	if (is_null(get_input('username')) || get_input('username')=='') {
		if (!isloggedin()) {
			forward('pg/photos/world');
		} else {
			forward('pg/photos/friends/' . $_SESSION['user']->username);
		}
	}*/

//	if (is_null(page_owner_entity()->name) || page_owner_entity()->name == '') {
//		$groupname = get_input('username');
//	} else {
//		$groupname = page_owner_entity()->name;
//	};
//	
	//there has to be a better way to do this
	if(!$groupname) {
		$page = get_input("page");
		list($pagename, $groupname) = split("/", $page);
	}

	list($group_holder, $album_id) = split(":", $groupname);
//		echo "<pre>page: $page\ngroup: $groupname\nalbum: $album_id"; die;
	
	$user = get_user_by_username($friendname);
	global $CONFIG;
	$prefix = $CONFIG->dbprefix;
	$max = 24;

	$sql = "SELECT ent.guid, count(1) as mycount, avg(ms2.string) as average
			FROM " . $prefix . "entities ent
			INNER JOIN " . $prefix . "entity_subtypes sub ON ent.subtype = sub.id
			AND sub.subtype = 'image' AND  ent.container_guid = $album_id
			INNER JOIN " . $prefix . "annotations ann1 ON ann1.entity_guid = ent.guid
			INNER JOIN " . $prefix . "metastrings ms ON ms.id = ann1.name_id
			AND ms.string = 'generic_rate'
			INNER JOIN " . $prefix . "metastrings ms2 ON ms2.id = ann1.value_id
			INNER JOIN " . $prefix . "users_entity u ON ann1.owner_guid = u.guid			
			GROUP BY ent.guid HAVING mycount > 1
			ORDER BY average DESC
			LIMIT $max";
	
	$result = get_data($sql);

	$entities = array();
	foreach($result as $entity) {
		$entities[] = get_entity($entity->guid);
	}
	
	$album = get_entity($album_id);
	$title = $album["title"] . ": " . elgg_echo("tidypics:highestrated");
	$area2 = elgg_view_title($title);
	$area2 .= elgg_view_entity_list($entities, $max, 0, $max, false);
	$body = elgg_view_layout('two_column_left_sidebar', '', $area2);
	page_draw($title, $body);

?>