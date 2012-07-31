<?php

	/**
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 */

	// If we're not logged in, forward to the front page
		if (!isloggedin()) forward();

	// Load Elgg engine
		require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");
    global $CONFIG;
$sql = "SELECT ".$CONFIG->dbprefix."objects_entity.guid, ".
       $CONFIG->dbprefix."objects_entity.title, ".
       $CONFIG->dbprefix."objects_entity.description, ".
       $CONFIG->dbprefix."entities.subtype AS subtype_id, ".
       $CONFIG->dbprefix."entities.owner_guid, ".
       $CONFIG->dbprefix."entity_subtypes.subtype ".
			 "FROM ( ".$CONFIG->dbprefix."entities ".$CONFIG->dbprefix."entities ".
       "INNER JOIN ".
       $CONFIG->dbprefix."entity_subtypes ".$CONFIG->dbprefix."entity_subtypes ".
       "ON (".$CONFIG->dbprefix."entities.subtype = ".$CONFIG->dbprefix."entity_subtypes.id)) ".
       "INNER JOIN ".
       $CONFIG->dbprefix."objects_entity ".$CONFIG->dbprefix."objects_entity ".
       "ON (".$CONFIG->dbprefix."objects_entity.guid = ".$CONFIG->dbprefix."entities.guid) ".
			 "WHERE ".$CONFIG->dbprefix."entity_subtypes.subtype = 'videochat'; ";


if ($rooms = get_data($sql)) { 
	foreach($rooms as $room) {
		$ztime = time();
		$description = $room->description; 
		$nilai = explode("^", $description);
		$cleanup = $nilai[16];
		$timelastaccess = $nilai[30]; 
		$mastercleanup = (datalist_get('vchat_availability') * 86400);
		//echo $room->title." -> ".$timelastaccess." -> ".$cleanup." -> ".$ztime." -> ".$mastercleanup."<br />";
		if (($cleanup>0) AND (($ztime-$timelastaccess)>$cleanup)) {
			// delete_records('objects_entity','guid',$room->guid);
			delete_data("delete from {$CONFIG->dbprefix}objects_entity where guid = {$room->guid} OR title = '".mysql_real_escape_string($room->title)."'");
			delete_data("delete from {$CONFIG->dbprefix}entities where guid = {$room->guid}");
		}
		if ($mastercleanup > 0) {
			if (($ztime-$timelastaccess)>$mastercleanup) {
				// delete_records('objects_entity','guid',$room->guid);
				delete_data("delete from {$CONFIG->dbprefix}objects_entity where guid = {$room->guid} OR title = '".mysql_real_escape_string($room->title)."'");
				delete_data("delete from {$CONFIG->dbprefix}entities where guid = {$room->guid}");
			}
		}
	} 
}
// end cleanup

    $ver=explode('.', get_version(true));
  	if ($ver[1]>7) {
      elgg_pop_breadcrumb();
      elgg_push_breadcrumb(elgg_echo('videochat'));
    }

		$title = elgg_view_title(elgg_echo("videochat:rooms"));
		
  	if ($ver[1]>7) {
      $options = elgg_list_entities(array(
      	'type' => 'object',
      	'subtype' => 'videochat',
      	'limit' => 10,
      	'full_view' => false,
      	'view_toggle_type' => false
      ));
    } else {
  		$options = array();
  		$options['types']='object';
  		$options['subtypes']='videochat';
    }

    if (!$options) {
    	$options = elgg_echo('videochat:none');
    }
  	
  	if ($ver[1]>7) {
      $sidebar = elgg_view('videochat/sidebar');
      $body = elgg_view_layout('content', array(
      	'filter_context' => 'all',
      	'content' => $options,
      	'title' => $title,
      	'sidebar' => $sidebar,
      ));

    } else {
      $area2 = $title;
    	$area2 .= elgg_list_entities($options);
      $body = elgg_view_layout("two_column_left_sidebar", '', $area2);
    }

	// Display page
  	if ($ver[1]>7) echo elgg_view_page(elgg_echo('videochat:rooms'),$body, 'default', array( 'sidebar' => "" ));
    else page_draw(elgg_echo('videochat:rooms'),$body);

?>
