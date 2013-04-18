<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés - <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

/**
 * THIS FILE IS NOT USED RIGHT NOW
 *
 * */


require_once(dirname(__FILE__)."/kaltura/api_client/includes.php");

$object_label = "kaltura_video";

$limit = get_input('limit', 10);
$offset = get_input('offset');

$group = page_owner_entity();

if($group instanceof ElggGroup) {
	//List all videos (If I can see it) from a user
		$title = sprintf(elgg_echo("kalturavideo:label:videosfrom"),$group->name);

		$body = '<script type="text/javascript">
/* <![CDATA[ */
CURRENT_GROUP = "'.$group->username.'";
/* ]]> */
</script>
';

		//global $is_admin;$is_admin = true;
		$context = get_context();
		set_context('search');

		$count = get_objects_in_group($group->getGUID(),$object_label,0,0,"",$limit,$offset,true);
		$result = get_objects_in_group($group->getGUID(),$object_label,0,0,"",$limit,$offset,false);

		set_context($context);
}
else {
	//$group = false;
	$title = elgg_echo("kalturavideo:label:adminvideos").": ";
	$title .= elgg_echo("kalturavideo:label:allgroupvideos");

	$context = get_context();
	set_context('search');

	//list all videos from groups
	//LOOK THIS:::
	$body = list_entities_from_metadata('kaltura_video_group_visible','1','object',$object_label);

	set_context($context);

}

if($result) {
	$wrapped_entries = array();
	foreach($result as $ob) {
		//print_r($ob);die;
		$tmp = get_entity($ob->guid);
		$wrapped_entries[] = $tmp;
	}

	//print_r($result);die;
	$context = get_context();
	set_context('search');
	$body = elgg_view_entity_list($wrapped_entries, $count, $offset, $limit, false);
	set_context($context);

}
else {
	if($group) $body .= elgg_echo("kalturavideo:text:nogroupvideos");
	else $body .= elgg_echo("kalturavideo:text:nogroupsvideos");
}

if(elgg_get_viewtype() == 'default') {
	$body = '<div id="kaltura_container" class="contentWrapper">'.$body.'</div>';
}

// Display main admin menu
page_draw($title,elgg_view_layout("two_column_left_sidebar", '', elgg_view_title($title).$body));

?>
