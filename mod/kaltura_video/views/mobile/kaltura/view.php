<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

include_once(dirname(dirname(dirname(dirname(__FILE__))))."/kaltura/api_client/includes.php");


$guid = (int) get_input('videopost');

$ob = get_entity($guid);
if(!$ob) forward();

$kmodel = KalturaModel::getInstance();
$mediaEntry = $kmodel->getEntry($ob->kaltura_video_id);

if($mediaEntry->status != 2){
	//Not converted so say
	echo '<div class="notconverted">';
		
		echo elgg_echo('kalturavideo:notconverted');
	
	echo '</div>';
	
	
	
} else {
$access_id = $ob->access_id;


$standard_entity = elgg_view_entity($ob);
//get the number of comments
$num_comments = $ob->countComments();

// If we've been asked to display the full view
$comments =  elgg_view_comments($ob);

list($votes,$rating_image,$rating) = kaltura_get_rating($ob);
$can_rate = (elgg_is_logged_in() && !kaltura_is_rated_by_user($ob->getGUID(),$_SESSION['user'],$votes));

//groups handle
$group = get_entity($ob->container_guid);
if($group instanceof ElggGroup) {
	set_page_owner($group->getGUID());
	add_submenu_item(elgg_echo('kalturavideo:label:groupvideos'), $CONFIG->wwwroot."pg/kaltura_video/".$group->username);
}
else $group = false;

//generic widget
$widget = kaltura_create_generic_widget_html ( $ob->kaltura_video_id , 'l' );
$widgetm = kaltura_create_generic_widget_html ( $ob->kaltura_video_id , 'm' );

//if widget exists
if($metadata->kaltura_video_widget_html &&
	!in_array(elgg_get_plugin_setting("alloweditor","kaltura_video"), array('simple','no'))) {
	//generated widget
	$widget = $metadata->kaltura_video_widget_html;
	$metadata->kaltura_video_widget_width .= 'px';
	$metadata->kaltura_video_widget_height .= 'px';

	//echo "WIDGET ".$metadata->kaltura_video_widget_uid;
}
else {
	preg_match('/width="([0-9]*)"/',$widget,$matchs);
	$metadata->kaltura_video_widget_width = 'auto';
	if($matchs[1]) $metadata->kaltura_video_widget_width = $matchs[1]."px";

	$metadata->kaltura_video_widget_height = 'auto';
	preg_match('/height="([0-9]*)"/',$widget,$matchs);
	if($matchs[1]) $metadata->kaltura_video_widget_height = $matchs[1]."px";

	//echo "GENERIC WIDGET";
}

$title = elgg_echo("kalturavideo:label:adminvideos").': ';
$title .= elgg_echo("kalturavideo:label:showvideo");

if(elgg_get_viewtype() != 'default' && elgg_get_viewtype() != 'mobile') {
	//put here the standard view call: rss, opendd, etc.
	echo $standard_entity;
	//add comments
	echo $comments;
	return true;
}

?>

<div class="contentWrapper singleview">

<div class="kalturaviewer blog_post">
<!--
<h1><a href="<?php echo $ob->getURL(); ?>"><?php echo $ob->title; ?></a></h1>
-->
<div class="post_icon">
<?php

$uob = get_user($ob->owner_guid);
echo elgg_view_entity_icon($uob, 'tiny');

?>
</div>
<p class="strapline">
<?php
	echo sprintf(elgg_echo("kalturavideo:strapline"),$metadata->kaltura_video_created);
?>

<?php echo elgg_echo('by'); ?> <a href="<?php echo $CONFIG->wwwroot.'pg/kaltura_video/'.$uob->username; ?>" title="<?php echo htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos")); ?>"><?php echo $uob->name; ?></a>
<?php
if($group) echo elgg_echo('ingroup')." <a href=\"{$CONFIG->wwwroot}pg/kaltura_video/{$group->username}/\" title=\"".htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos"))."\">{$group->name}</a> ";
 ?>
<?php echo elgg_echo("kalturavideo:label:length"); ?> <strong><?php echo $metadata->kaltura_video_length; ?></strong>
<?php echo elgg_echo("kalturavideo:label:plays"); ?> <strong class="ajax_play" rel="<?php echo $metadata->kaltura_video_id; ?>"><?php echo intval($metadata->kaltura_video_plays); ?></strong>
<!-- display the comments link -->
<?php
if($metadata->kaltura_video_comments_on != 'Off') {
?>
	<a href="<?php echo $ob->getURL(); ?>#comments"><?php echo sprintf(elgg_echo("comments")) . " (" . $num_comments . ")"; ?></a><br />
<?php
}
?>
</p>
<!-- display tags -->
<p class="tags">
<?php
$tags = elgg_view('output/tags', array('tags' => $ob->tags));
if (!empty($tags)) {
	echo $tags ;
}

$categories = elgg_view('categories/view', array('entity' => $ob));
if (!empty($categories)) {
	echo ($tags ? ' - ' : '' ).$categories;
}

?>
</p>
<div class="clearfloat"></div>
<?php echo elgg_view('share/kaltura', array('widget' => htmlspecialchars($widget))); ?>

<!-- video embed -->
<div class="kalturaplayer left bigwidget">

<?php 

//var_dump(KalturaModel::getflavourAssets($ob->kaltura_video_id));

/*flavour id's to use for mobile

@9 = small mp4 auto X 352
@10 = large mp4 auto X 720
*/

?>

<video controls>
  <source src="http://www.minds.tv/p/100/sp/0/playManifest/entryId/<?php echo $ob->kaltura_video_id;?>/format/url/flavorParamId/16/video.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
</video>

</div>

<br/>


<div class="text">
<?php
echo autop($ob->description);
?>
</div>
<p><?php echo elgg_echo('kalturavideo:license:label') . ': ' . elgg_echo('kalturavideo:license:' . $ob->license); ?>
<div class="clear"></div>



<div class="hide kaltura_video_details">
<p><strong><?php echo elgg_echo("kalturavideo:label:thumbnail");?></strong></p>
<p><a href="<?php echo $metadata->kaltura_video_thumbnail; ?>" onclick="window.open(this.href);return false;"><?php echo $metadata->kaltura_video_thumbnail; ?></a></p>
<p><strong><?php echo elgg_echo("kalturavideo:label:sharel");?></strong></p>
<p><input type="text" class="input-text" value="<?php echo htmlspecialchars($widget); ?>" /></p>
<p><strong><?php echo elgg_echo("kalturavideo:label:sharem");?></strong></p>
<p><input type="text" class="input-text" value="<?php echo htmlspecialchars($widgetm); ?>" /></p>
</div>
<?php
if($ob->canEdit()) {
?>

	<!-- options -->
	<p class="options">
    
	<a href="<?php echo $CONFIG->wwwroot; ?>mod/kaltura_video/edit.php?videopost=<?php echo $ob->getGUID(); ?>" rel="<?php echo $metadata->kaltura_video_id; ?>" class="submit_button"><?php echo elgg_echo("kalturavideo:label:editdetails"); ?></a>
<?php

	echo elgg_view("output/confirmlink",array("text" => elgg_echo("kalturavideo:label:delete"), "href" => $CONFIG->wwwroot . 'action/kaltura_video/delete?delete_video=' . $metadata->kaltura_video_id , "confirm" => elgg_echo("kalturavideo:prompt:delete") , "class" => 'submit_button'));

	echo ' '.elgg_echo('access').': ';
	echo kaltura_view_select_privacity($metadata->kaltura_video_id,$access_id,$group,$metadata->kaltura_video_collaborative);

?>
	</p>
<?php
}
?>

</div>

</div>
<?php
if($metadata->kaltura_video_comments_on != 'Off') {
?>
	<div id="comments">
	<?php echo $comments; ?>
	</div>
<?php
	} 
}
?>
