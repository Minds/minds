<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

include_once($CONFIG->pluginspath."kaltura_video/kaltura/api_client/includes.php");

$owner = $vars['entity']->getOwnerEntity();
$access_id = $vars['entity']->access_id;

$ob = $vars['entity'];


//get the number of comments
$num_comments = $vars['entity']->countComments();

$group = get_entity($vars['entity']->container_guid);
if(!($group instanceof ElggGroup)) $group = false;

if(elgg_get_context()=='archive') {
	//this view is for My videos:
	
	
	echo elgg_view_menu('entity', array(
	'entity' => $ob,
	'handler' => 'archive',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

?>

<div class="contentWrapper singleview">

<div class="kalturavideoitem" id="kaltura_video_<?php echo $ob->kaltura_video_id; ?>">

<div class="left">
<p><a href="<?php echo $vars['entity']->getURL(); ?>" class="play"><img src="<?php echo kaltura_get_thumnail($ob->kaltura_video_id, 120, 68, 100); ?>" height="68px" width="120px" alt="<?php echo htmlspecialchars($vars['entity']->title); ?>" title="<?php echo htmlspecialchars($vars['entity']->title); ?>" /></a></p>
</div>

<div class="main_block">

<h3><a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo $vars['entity']->title; ?></a></h3>

<p class='description'>
	<?php echo $ob->description ? substr(strip_tags($ob->description), 0, 125) . '...' : '';?>
</p>

<p class="stamp">
<?php echo ' <b class="kaltura_video_created">'. elgg_view_friendly_time($ob->time_created).'</b>'; ?>
 <?php echo elgg_echo('by'); ?> <a href="<?php echo $CONFIG->wwwroot.'pg/kaltura_video/'.$owner->username; ?>" title="<?php echo htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos")); ?>"><?php echo $owner->name; ?></a>

<?php
if($group) {
	echo elgg_echo('ingroup')." <a href=\"{$CONFIG->wwwroot}pg/kaltura_video/{$group->username}/\" title=\"".htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos"))."\">{$group->name}</a> ";
}

echo elgg_echo("kalturavideo:label:length"); echo ' <strong class="kaltura_video_length">'.$ob->kaltura_video_length.'</strong>'; ?>

<?php echo elgg_echo("kalturavideo:label:plays"); echo ' <strong class="kaltura_video_plays" rel="'.$ob->kaltura_video_id.'">'.intval($ob->kaltura_video_plays).'</strong>'; ?>

</p>
<?php if(1==2){?>
<p class="options">

<a href="<?php echo $vars['entity']->getURL(); ?>" class="submit_button"><?php echo elgg_echo("kalturavideo:label:view"); ?></a>

<?php
if($vars['entity']->canEdit()) {

?>
	<a href="<?php echo $vars['url']; ?>mod/kaltura_video/edit.php?videopost=<?php echo $vars['entity']->getGUID(); ?>"  class="submit_button"><?php echo elgg_echo("kalturavideo:label:editdetails"); ?></a>
<?php
	echo elgg_view("output/confirmlink",array("text" => elgg_echo("kalturavideo:label:delete"), "href" => $vars['url'] . 'action/kaltura_video/delete?delete_video=' . $ob->kaltura_video_id , "confirm" => elgg_echo("kalturavideo:prompt:delete") , "class" => 'submit_button'));
?>

	</p>
	<p class="options" style="padding-top:0;">
<?php

	echo elgg_echo('access').': ';
	echo kaltura_view_select_privacity($metadata->kaltura_video_id,$access_id,$group,$metadata->kaltura_video_collaborative);

}
elseif($metadata->kaltura_video_cancollaborate) {

?>
	<a href="#" rel="<?php echo $metadata->kaltura_video_id; ?>" class="submit_button edit" title="<?php echo htmlspecialchars(elgg_echo("kalturavideo:text:iscollaborative")); ?>">
	<img src="<?php echo $CONFIG->wwwroot; ?>mod/kaltura_video/kaltura/images/group.png" alt="<?php echo htmlspecialchars(elgg_echo("kalturavideo:text:iscollaborative")); ?>" style="vertical-align:middle;" />
	<?php echo elgg_echo("kalturavideo:label:edit"); ?></a>

<?php
}
?>
</p>
<?php } ?>
</div>

<div class="clear"></div>
</div>

</div>

<?php
}
elseif(elgg_get_context()=='sidebar') {
	?>
	<div class="kalturavideoitem" id="kaltura_video_<?php echo $ob->kaltura_video_id; ?>">

	<div class="left">
	<p><a href="<?php echo $vars['entity']->getURL(); ?>" class="play"><img src="<?php echo kaltura_get_thumnail($ob->kaltura_video_id, 200, 75, 100); ?>" width="200px" height="113px" alt="<?php echo htmlspecialchars($vars['entity']->title); ?>" title="<?php echo htmlspecialchars($vars['entity']->title); ?>" /></a></p>
	</div>

	<h3><a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo $vars['entity']->title; ?></a></h3>
	
	
	<p class="stamp">
	 <?php echo elgg_echo('by'); ?> <a href="<?php echo $CONFIG->wwwroot.'pg/kaltura_video/'.$owner->username; ?>" title="<?php echo htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos")); ?>"><?php echo $owner->name; ?></a>
	
	<?php
	if($group) {
		echo elgg_echo('ingroup')." <a href=\"{$CONFIG->wwwroot}pg/kaltura_video/{$group->username}/\" title=\"".htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos"))."\">{$group->name}</a> ";
	}
	
	echo elgg_echo("kalturavideo:label:length"); echo ' <strong class="kaltura_video_length">'.$ob->kaltura_video_length.'</strong>'; ?>
	
	<?php echo elgg_echo("kalturavideo:label:plays"); echo ' <strong class="kaltura_video_plays" rel="'.$ob->kaltura_video_id.'">'.intval($ob->kaltura_video_plays).'</strong>'; ?>
	
	</p>
	</div>
<?php
	
} else {
	//this view is for everything else

	$icon = '<a href="'.$vars['entity']->getURL().'">';
	$icon .= '<img src="' . $metadata->kaltura_video_thumbnail . '" alt="' . htmlspecialchars($vars['entity']->title) . '" title="' . htmlspecialchars($vars['entity']->title) . '" />';
	$icon .= '</a>';
	$info = "<p class=\"shares_gallery_title\">". elgg_echo("kalturavideo:river:shared") .": <a href=\"";
	$info .= $vars['entity']->getURL();
	$info .= "\">{$vars['entity']->title}</a> ";
	$info .= "</p>";
	//when listing user videos is ok:
	$info .= "<p class=\"owner_timestamp\">";
	$info .= $metadata->kaltura_video_created." ";
	$info .= elgg_echo('by')." <a href=\"{$vars['url']}pg/kaltura_video/{$owner->username}/\" title=\"".htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos"))."\">{$owner->name}</a> ";
	if($group) $info .= elgg_echo('ingroup')." <a href=\"{$vars['url']}pg/kaltura_video/{$group->username}/\" title=\"".htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos"))."\">{$group->name}</a> ";
	$info .= elgg_echo("kalturavideo:label:length"). ' <strong>'.$metadata->kaltura_video_length.'</strong> ';
	$info .= elgg_echo("kalturavideo:label:plays"). ' <strong>'.((int)$metadata->kaltura_video_plays).'</strong>';
	

	if ($num_comments && $metadata->kaltura_video_comments_on != 'Off')
		$info .= ", <a href=\"{$vars['entity']->getURL()}\">".sprintf(elgg_echo("comments")). " (" . $num_comments . ")</a>";



	$info .= "</p>";

	if (get_input('search_viewtype') == "gallery") {
		//display
		$info = '<p class="shares_gallery_title">'.$icon.'</p>';
		$info .= "<p class=\"shares_gallery_title\"><a href=\"";
		$info .= $vars['entity']->getURL();
		$info .= "\">{$vars['entity']->title}</a> ";
		$info .= "</p>";
		$info .= "<p class=\"shares_gallery_user\">";
		$info .= elgg_echo("kalturavideo:label:length"). ' <strong>'.$metadata->kaltura_video_length.'</strong> ';
		$info .= elgg_echo("kalturavideo:label:plays"). ' <strong>'.intval($metadata->kaltura_video_plays).'</strong>';
		$info .= "</p>";
		//when listing user videos is ok:
		$info .= "<p class=\"shares_gallery_user\"><a href=\"{$vars['url']}pg/kaltura_video/{$owner->username}/\">{$owner->name}</a> ";
		if($group) $info .= elgg_echo('ingroup')." <a href=\"{$vars['url']}pg/kaltura_video/{$group->username}/\" title=\"".htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos"))."\">{$group->name}</a> ";

		$info .= '<span class="shared_timestamp">'.$metadata->kaltura_video_created.'</span>';


		if ($num_comments && $metadata->kaltura_video_comments_on != 'Off')
			$info .= ", <a href=\"{$vars['entity']->getURL()}\">".sprintf(elgg_echo("comments")). " (" . $num_comments . ")</a>";


		echo "<div class=\"share_gallery_view\">";
		echo "<div class=\"share_gallery_info\">" . $info . "</div>";
		echo "</div>";

	}
	else {
		//this view is for context search listing
		echo elgg_view_listing($icon, $info);
	}
}
?>
