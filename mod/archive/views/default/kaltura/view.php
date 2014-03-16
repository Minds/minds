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
$widget = kaltura_create_generic_widget_html ( $ob->kaltura_video_id , 'l',$ob->monetized );
$widgetm = kaltura_create_generic_widget_html ( $ob->kaltura_video_id , 'm',$ob->monetized  );

if(elgg_get_viewtype()=='mobile'){
	$widget = kaltura_create_generic_widget_html ( $ob->kaltura_video_id , 'mobile',$ob->monetized );
}

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

echo elgg_view_menu('entity', array(
	'entity' => $ob,
	'handler' => 'archive',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

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
	echo sprintf(elgg_echo("kalturavideo:strapline"),elgg_view_friendly_time($ob->time_created));
?>

<?php echo elgg_echo('by'); ?> <a href="<?php echo $CONFIG->wwwroot.'archive/owner/'.$uob->username; ?>" title="<?php echo htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos")); ?>"><?php echo $uob->name; ?></a>
<?php
if($group) echo elgg_echo('ingroup')." <a href=\"{$CONFIG->wwwroot}archive/owner/{$group->username}/\" title=\"".htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos"))."\">{$group->name}</a> ";
 ?>
</p>
<!-- display tags -->
<p class="tags">
<?php
$tags = elgg_view('output/tags', array('tags' => $ob->tags));
if (!empty($tags)) {
	echo $tags ;
}

?>
</p>
<div class="clearfloat"></div>
<div class="kalturaplayer left bigwidget"><?php echo $widget; ?></div><br/>
<div class="text">
<?php
echo autop($ob->description);
?>
</div>
<p><?php echo elgg_view('minds/license', array('license'=>$ob->license)); ?> <?php echo elgg_view('output/url', array(	'href'=>'/action/archive/download?guid='.$ob->guid,
												'text'=> elgg_echo('file:download'),
												'is_action' => true,
												'class'=> 'elgg-button elgg-button-action right'
																		
										));?>
										<?php if(elgg_is_admin_logged_in()){
											echo elgg_view('output/url', array(	'href'=>'/action/archive/feature?guid='.$ob->guid,
												'text'=> $ob->featured == true ? elgg_echo('archive:featured:un-action') : elgg_echo('archive:featured:action'),
												'is_action' => true,
												'class'=> 'elgg-button elgg-button-action right'
											));
											echo elgg_view('output/url', array(	'href'=>'/action/archive/monetize?guid='.$ob->guid,
												'text'=> $ob->monetized == true ? elgg_echo('archive:monetized:un-action') : elgg_echo('archive:monetized:action'),
												'is_action' => true,
												'class'=> 'elgg-button elgg-button-action right'
											));
										}?>
</p>
<?php 
	if($ob->access_id == 2){
		echo elgg_view('minds_social/social_footer');
	}
?>
<?php echo elgg_view('page/elements/ads', array('type'=>'content-foot')); ?>
<div class="clear"></div>


<div class="hide kaltura_video_details">
<p><strong><?php echo elgg_echo("kalturavideo:label:thumbnail");?></strong></p>
<p><a href="<?php echo $metadata->kaltura_video_thumbnail; ?>" onclick="window.open(this.href);return false;"><?php echo $metadata->kaltura_video_thumbnail; ?></a></p>
<p><strong><?php echo elgg_echo("kalturavideo:label:sharel");?></strong></p>
<p><input type="text" class="input-text" value="<?php echo htmlspecialchars($widget); ?>" /></p>
<p><strong><?php echo elgg_echo("kalturavideo:label:sharem");?></strong></p>
<p><input type="text" class="input-text" value="<?php echo htmlspecialchars($widgetm); ?>" /></p>
</div>

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
