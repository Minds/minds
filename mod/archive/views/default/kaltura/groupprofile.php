<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

require_once($CONFIG->pluginspath."kaltura_video/kaltura/api_client/includes.php");

//check to make sure this group forum has been activated
if($vars['entity']->kaltura_video_enable != 'no'){

?>
<div id="kaltura_group_video_widget">
<h2><?php echo elgg_echo("kalturavideo:groupprofile"); ?></h2>
<?php

		//the number of files to display
	$number = (int) $vars['entity']->num_display;
	if (!$number)
		$number = 5;

	//get the group files
	//comment this line if you want to show the classic little object listing
	$result = elgg_get_entities(array('types' => 'object', 'subtypes' => 'kaltura_video', 'container_guid' => page_owner(), 'limit' => $number, 'full_view' => FALSE));
	//big thumbnail:
if($result) {
	$body = '';
	foreach($result as $ob) {
		if($metadata = kaltura_get_metadata($ob)) {

			$body .= '<div class="kaltura_video_widget">';
			$body .= '<a class="tit" href="'.$ob->getURL().'">'.$ob->title.'</a>';

			$icon .= '<img src="' . $metadata->kaltura_video_thumbnail . '" alt="' . htmlspecialchars($ob->title) . '" title="' . htmlspecialchars($ob->title) . '" />';
			$body .= '<a class="img" href="'.$ob->getURL().'">'.$icon.'<span></span></a>';

			$body .= '<p>' . sprintf(elgg_echo("kalturavideo:strapline"),$metadata->kaltura_video_created) . '</p>';

			if($metadata->kaltura_video_rating_on != 'Off') {

				list($votes,$rating_image,$rating) = kaltura_get_rating($ob);
				$rating = round($rating);

				$rating = '<img src="'.$CONFIG->wwwroot.'mod/kaltura_video/kaltura/images/ratings/'.$rating_image.'" alt="'.$rating.'" /> ('.$votes.' '.elgg_echo('kalturavideo:votes').')';
				$body .= '<p class="rating">'.$rating.'</p>';
			}


			if(trim($ob->description)) $body .= '<p><a href="#" onclick="$(this).parent().next().slideToggle(\'fast\');return false;">'.elgg_echo('kalturavideo:more').'</a></p>';

			$body .= '<span class="desc">'.strip_tags($ob->description).'</span>';
			$body .= '<div class="clear"></div>';
			$body .= "</div>\n";
		}
		else {
			//$body .= elgg_echo('kalturavideo:error:objectnotavailable');
		}
	}
	$body .= '<div class="kaltura_video_widget last">';
	$body .= '<a class="tit" href="'.$CONFIG->wwwroot.'pg/kaltura_video/'.page_owner_entity()->username.'">'.elgg_echo("kalturavideo:label:morevideos").'</a>';
	$body .= "</div>\n";
}
else {
	$context = get_context();
	set_context("search");
	$body = elgg_list_entities(array('types' => 'object', 'subtypes' => 'kaltura_video', 'container_guid' => page_owner(), 'limit' => $number, 'full_view' => FALSE));
	set_context($context);

	if(!$body) $body = '<div class="forum_latest">'.elgg_echo("kalturavideo:text:nogroupvideos").'</div>';

}

echo $body;
?>
<div class="clearfloat" /></div>
</div>

<?php
}//end of activate check statement
?>
