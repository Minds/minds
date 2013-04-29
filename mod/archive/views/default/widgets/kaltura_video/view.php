<?php

elgg_load_library('archive:kaltura');

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
	$body .= '<a href="'.elgg_get_site_url().'archive/owner/'.$owner->username.'">'.elgg_echo("kalturavideo:label:morevideos").'</a>';
	$body .= "</div>\n";
}
else {
	//$body = elgg_echo("kalturavideo:text:nouservideos");
}

?>
<div>
<?php
echo $body;
?>
</div>
