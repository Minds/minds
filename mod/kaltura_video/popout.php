<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

require_once(dirname(__FILE__)."/kaltura/api_client/includes.php");
global $SKIP_KALTURA_REWRITE;

$entry_id = get_input('entryId');

$ob = kaltura_get_entity($entry_id);

if(!$ob) forward();
$access_id = $ob->access_id;
$metadata = kaltura_get_metadata($ob);

//generic widget
$widget = kaltura_create_generic_widget_html ( $metadata->kaltura_video_id , 'l' );
$widgetm = kaltura_create_generic_widget_html ( $metadata->kaltura_video_id , 'm' );

//if widget exists
if($metadata->kaltura_video_widget_html) {
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

?>

<h1><?php echo $ob->title; ?></h1>

<?php

echo $widget;

?>
