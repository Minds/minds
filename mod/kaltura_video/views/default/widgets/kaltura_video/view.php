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

//the page owner
$owner = get_user($vars['entity']->owner_guid);


//the number of files to display
$limit = (int) $vars['entity']->num_display;
if (!$limit)
	$limit = 1;
//the number of files to display
$offset = max((int) $vars['entity']->start_display - 1, 0);

$result = elgg_get_entities(array('types' => 'object', 'subtypes' => 'kaltura_video', 'container_guid' => $owner->getGUID(), 'order_by' => "time_created DESC", 'limit' => $limit, 'offset' => $offset));

if($result) {
	$body = '';
	foreach($result as $ob) {
		if($metadata = kaltura_get_metadata($ob)) {
			if($vars['entity']->show_mode == 'thumbnail') {
				$body .= '<div class="kaltura_video_widget">';
				$body .= '<a class="tit" href="'.$ob->getURL().'">'.$ob->title.'</a>';

				$icon .= '<img src="' . $metadata->kaltura_video_thumbnail . '" alt="' . htmlspecialchars($ob->title) . '" title="' . htmlspecialchars($ob->title) . '" />';
				$body .= '<a class="img" href="'.$ob->getURL().'">'.$icon.'<span></span></a>';

				$body .= '<p>' . sprintf(elgg_echo("kalturavideo:strapline"),$metadata->kaltura_video_created) . '</p>';



				if(trim($ob->description)) $body .= '<p><a href="#" onclick="$(this).parent().next().slideToggle(\'fast\');return false;">'.elgg_echo('kalturavideo:more').'</a></p>';

				$body .= '<span class="desc">'.strip_tags($ob->description).'</span>';
				$body .= '<div class="clear"></div>';
				$body .= "</div>\n";

			}
			else {
				$body .= '<div class="kaltura_video_widget">';
				$body .= '<a class="tit" href="'.$ob->getURL().'">'.$ob->title.'</a>';
				$widgetm = kaltura_create_generic_widget_html ( $ob->kaltura_video_id , 'm' );
				$body .= $widgetm;
				//$body .= '<a class="tit" href="'.$ob->getURL().'">'.elgg_echo('kalturavideo:label:details').'</a>';
				$body .= "</div>\n";
			}
		}
		else {
			//$body .= elgg_echo('kalturavideo:error:objectnotavailable');
		}
	}
	$body .= '<div class="kaltura_video_widget last">';
	$body .= '<a class="tit" href="'.$CONFIG->wwwroot.'pg/kaltura_video/'.$owner->username.'">'.elgg_echo("kalturavideo:label:morevideos").'</a>';
	$body .= "</div>\n";
}
else {
	//$body = elgg_echo("kalturavideo:text:nouservideos");
}

?>
<div style="text-align:center">
<?php
echo $body;
?>
</div>
