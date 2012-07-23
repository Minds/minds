<?php 


$guid = (int) get_input('videopost');

if($guid){

	$ob = get_entity($guid);
	
	$widgetUi = elgg_get_plugin_setting('custom_kdp', 'kaltura_video');
	
	$viewData["swfUrl"]	= KalturaHelpers::getSwfUrlForBaseWidget($widgetUi);
	
	$entryId = $ob->kaltura_video_id;

?>

  <meta property="fb:app_id" content="184865748231073" /> 
  <meta property="og:type"   content="video.other" /> 
  <meta property="og:url"    content="<?php echo $viewData["swfUrl"] . '?entryId=' . $entryId; ?>" /> 
  <meta property="og:title"  content="<?php echo $ob->title;?>" /> 
  <meta property="og:image"  content="<?php echo $ob->kaltura_video_thumbnail; ?>" /> 

<?php } 

?>