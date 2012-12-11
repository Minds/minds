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

$kmodel = KalturaModel::getInstance();
$mediaEntry = $kmodel->getEntry($ob->kaltura_video_id);

//get the number of comments
$num_comments = $vars['entity']->countComments();

$group = get_entity($vars['entity']->container_guid);
if(!($group instanceof ElggGroup)) $group = false;

if(elgg_get_context()=='archive') {
	//this view is for My videos:
	
	
$menu = elgg_view_menu('entity', array(
	'entity' => $ob,
	'handler' => 'archive',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$owner = get_entity($ob->owner_guid);
$owner_link = elgg_view('output/url', array(
	'text' => $owner->name,
	'href' => $owner->getURL()
));

$title = elgg_view('output/url', array(
	'text' => $ob->title,
	'href' => $ob->getURL(),
));

$description = $ob->description ? substr(strip_tags($ob->description), 0, 125) . '...' : '';

$subtitle .= 
	elgg_echo('by') . ' ' . $owner_link . ' ' .
	
	elgg_echo("kalturavideo:label:length") . ' <strong class="kaltura_video_length">'.$ob->kaltura_video_length.'</strong>' .

	elgg_echo("kalturavideo:label:plays") . ' <strong class="kaltura_video_plays" rel="'.$ob->kaltura_video_id.'">'.kaltura_get_plays_count($mediaEntry).'</strong>';


'<b class="kaltura_video_created">'. elgg_view_friendly_time($ob->time_created).'</b> by ' . $owner_link . elgg_echo("kalturavideo:label:length") . '<strong class="kaltura_video_length">' . $ob->kaltura_video_length . '</strong>';

$params = array(
	'entity' => $album,
	'title' => $title,
	'metadata' => $menu,
	'subtitle' => $subtitle,
	'content'=>$description,
	'tags' => elgg_view('output/tags', array('tags' => $ob->tags)),
);
$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

$icon = elgg_view('output/img', array(
			'src' => kaltura_get_thumnail($ob->kaltura_video_id, 120, 68, 100),
			'class' => 'elgg-photo',
			'title' => $ob->title,
			'alt' => $ob->title,
			'width'=>'120px',
			'height' => '68px'
	));
$icon = elgg_view('output/url', array(
		'text' => $icon,
		'href' => $ob->getURL()
	));
echo elgg_view_image_block($icon, $summary);

} elseif(elgg_get_context() == 'archive-tiles'){
	$icon = elgg_view('output/img', array(
			'src' => kaltura_get_thumnail($ob->kaltura_video_id, 120, 68, 100),
			'class' => 'elgg-photo',
			'title' => $ob->title,
			'alt' => $ob->title,
			'width'=>'120px',
			'height' => '68px'
	));
	$icon = elgg_view('output/url', array(
			'text' => $icon,
			'href' => $ob->getURL()
		));
	echo $icon;
}
elseif(elgg_get_context()=='sidebar') {
	?>
	<div class="kalturavideoitem" id="kaltura_video_<?php echo $ob->kaltura_video_id; ?>">

	<div class="left">
	<?php 
		elgg_pop_context(); if(elgg_get_context()=='news'){ $width=140;$height=79;} else {$width=215;$height=121;} elgg_push_context('sidebar');?>
	<p><a href="<?php echo $vars['entity']->getURL(); ?>" class="play"><img src="<?php echo kaltura_get_thumnail($ob->kaltura_video_id, $width, $height, 100); ?>" alt="<?php echo htmlspecialchars($vars['entity']->title); ?>" title="<?php echo htmlspecialchars($vars['entity']->title); ?>" /></a></p>
	</div>

	<h3><a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo $vars['entity']->title; ?></a></h3>
	
	
	<p class="stamp">
	 <?php echo elgg_echo('by'); ?> <a href="<?php echo $CONFIG->wwwroot.'archive/owner/'.$owner->username; ?>" title="<?php echo htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos")); ?>"><?php echo $owner->name; ?></a>
	
	<?php
	if($group) {
		echo elgg_echo('ingroup')." <a href=\"{$CONFIG->wwwroot}archive/owner/{$group->username}/\" title=\"".htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos"))."\">{$group->name}</a> ";
	}
	
	echo elgg_echo("kalturavideo:label:length"); echo ' <strong class="kaltura_video_length">'.$ob->kaltura_video_length.'</strong>'; ?>
	
	<?php echo elgg_echo("kalturavideo:label:plays"); echo ' <strong class="kaltura_video_plays" rel="'.$ob->kaltura_video_id.'">'.kaltura_get_plays_count($mediaEntry).'</strong>'; ?>
	
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
	$info .= elgg_echo("kalturavideo:label:plays"). ' <strong>'.kaltura_get_plays_count($mediaEntry).'</strong>';
	

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
