<?php
	include_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/kaltura/api_client/includes.php");

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();

	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("kalturavideo:river:updated"),$url) . " ";
    $string .= elgg_echo("kalturavideo:river:update") . " <a href=\"" . $object->getURL() . "\">" . $object->title . "</a>";

	$widgetUi = elgg_get_plugin_setting('custom_kdp', 'kaltura_video');
	$viewData["swfUrl"]	= KalturaHelpers::getSwfUrlForBaseWidget($widgetUi);
	
	//elgg_load_js('lightbox');
	//elgg_load_css('lightbox');
	 
	/*$image = elgg_view('output/url', array(
		'href' => $viewData["swfUrl"] . '?entryId=' . $object->kaltura_video_id,
		'text' =>  kaltura_create_generic_widget_html ( $object->kaltura_video_id , 'news' ),
		'title' => $object->title,
	));*/
	elgg_load_js('uiVideoInline');
	$image = elgg_view('output/url', array(
		'href' => false,
		'class' => 'uiVideoInline archive',
		'video_id'=> $object->kaltura_video_id,
		'text' =>  '<span></span><img src=\'' . kaltura_get_thumnail($object->kaltura_video_id, 525, 0, 100) . '\' width="525px"/>',
		'title' => $object->title,
	));

?>


<?php 
$object = $vars['item']->getObjectEntity();

$excerpt = strip_tags($object->excerpt);
$excerpt = elgg_get_excerpt($excerpt);

//@todo - cache this info so that we are not calling the kaltura server each time @MH
$kmodel = KalturaModel::getInstance();
$mediaEntry = $kmodel->getEntry($object->kaltura_video_id);

if($mediaEntry->status >= 2){
	
	echo elgg_view('river/elements/layout', array(
		'item' => $vars['item'],
		'message' => $image,
	));
}

?>