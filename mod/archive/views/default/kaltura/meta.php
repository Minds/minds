<?php 


$guid = (int) get_input('videopost');

if($guid){

	$ob = get_entity($guid);
	
	$widgetUi = elgg_get_plugin_setting('custom_kdp', 'kaltura_video');
	
	$viewData["swfUrl"]	= KalturaHelpers::getSwfUrlForBaseWidget($widgetUi);
	
	$entryId = $ob->kaltura_video_id;

?>
<?php } 

?>