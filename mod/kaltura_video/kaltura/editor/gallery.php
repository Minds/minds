<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

require_once(dirname(dirname(__FILE__))."/api_client/includes.php");

$type = $_REQUEST['type'];

$limit = get_input('limit', 8);
$page = get_input('page',1);

$offset = ($page-1) * $limit;

//get the page_owner
$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($_SESSION['guid']);
}
if (!($page_owner instanceof ElggEntity)) forward();

$wrapped_entries = array();

if($type == 'friends') {
	$count = (int) count_user_friends_objects($page_owner->getGUID(), 'kaltura_video');
	$result = get_user_friends_objects($page_owner->getGUID(),'kaltura_video', $limit,$offset);
}
elseif($type == 'public') {
	$result = elgg_get_entities(array('types' => 'object', 'subtypes' => 'kaltura_video', 'limit' => $limit, 'offset' => $offset));
	$count = (int) elgg_get_entities(array('types' => 'object', 'subtypes' => 'kaltura_video', 'limit' => $limit, 'offset' => $offset, 'count' => true));

}
else {
	$count = (int) count_user_objects($_SESSION['user']->getGUID(), 'kaltura_video');
	$result = get_user_objects($_SESSION['user']->getGUID(),'kaltura_video', $limit, $offset);

}

if($result) {
	foreach($result as $ob) {
		$metadata = kaltura_get_metadata($ob);
		$wrapped_entries[] = array($ob,$metadata);
	}
}


echo '<div class="box">';
//echo '<h2>'.elgg_echo('kalturavideo:label:gallery').": ";
echo '<h2 style="height:40px;">';
if($type=='') echo elgg_echo('kalturavideo:label:myvideos');
else echo '<a href="init.php" class="kalturaButton">'.elgg_echo('kalturavideo:label:myvideos').'</a>';

if($type=='friends') echo ' '.elgg_echo('kalturavideo:label:friendsvideos');
else echo ' <a href="init.php?type=friends" class="kalturaButton">'.elgg_echo('kalturavideo:label:friendsvideos').'</a>';
if($type=='public') echo ' '.elgg_echo('kalturavideo:label:allvideos');
else echo ' <a href="init.php?type=public" class="kalturaButton">'.elgg_echo('kalturavideo:label:allvideos').'</a>';
//echo '</h2>';
echo '</h2>';

echo '<div class="gallery">';

if(count($wrapped_entries)>0) {
	//echo '<pre>'.print_R($wrapped_entries,true).'</pre>';die;
	foreach($wrapped_entries as $entry) {
		echo '<div class="galleryItem">';
		echo '<label>'.$entry[1]->kaltura_video_created.'</label>';
		echo '<img src="'.$entry[1]->kaltura_video_thumbnail.'" alt="'.htmlspecialchars($entry[0]->title).'" title="'.htmlspecialchars($entry[0]->title).'" />';

		echo '<div><a href="'.$CONFIG->wwwroot.'pg/kaltura_video/show/'.$entry[0]->guid.'" rel="'.$entry[1]->kaltura_video_id.'" class="button1 insert">'.elgg_echo('kalturavideo:label:miniinsert').'</a>';
		if($entry[1]->kaltura_video_editable) {
			echo '<a href="'.$CONFIG->wwwroot.'pg/kaltura_video/show/'.$entry[0]->guid.'" rel="'.$entry[1]->kaltura_video_id.'" class="button2 edit">'.elgg_echo('kalturavideo:label:miniedit').'</a>';
		}
		echo '</div></div>';
	}
}
else {
	echo '<h3>';
	if($type == 'friends') {
		echo elgg_echo("kalturavideo:text:nofriendsvideos");
	}
	elseif($type == 'public') {
		echo elgg_echo("kalturavideo:text:nopublicvideos");
	}
	else {
		echo elgg_echo("kalturavideo:text:novideos");
	}
	echo '</h3>';
}

	echo '<div class="clear"></div>';
	echo '</div>';
	echo '<div class="left"><p>';
	echo '<a href="#" class="kalturaButton cancel">'.elgg_echo('kalturavideo:label:cancel').'</a> &nbsp; ';
	if(in_array(get_plugin_setting("alloweditor","kaltura_video"), array('simple','no'))) {
	    $use_simple_video = true;
	}
	echo '<a href="#" class="kalturaButton ' . ($use_simple_video ? 'newsimple' : 'new') . '">'.elgg_echo('kalturavideo:label:newvideo').'</a>';
	echo '</p></div>';
	echo '<div class="right"><p>';
	if($page > 1) {
		echo '<a href="#" rel="1" class="kalturaButton prev">'.elgg_echo('kalturavideo:label:start').'</a> &nbsp; ';
		echo '<a href="#" rel="'.($page-1).'" class="kalturaButton prev">'.elgg_echo('kalturavideo:label:prev').'</a> &nbsp; ';
	}
	if($page < ceil($count/$limit)) echo '<a href="#" rel="'.($page+1).'" class="kalturaButton next">'.elgg_echo('kalturavideo:label:next').'</a>';
	echo '</p></div>';
	echo '<div class="clear"></div>';
	echo '</div>';
?>
