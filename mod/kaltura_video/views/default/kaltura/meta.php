<?php 


$guid = (int) get_input('videopost');

if($guid){

	$ob = get_entity($guid);
	
	$widgetUi = elgg_get_plugin_setting('custom_kdp', 'kaltura_video');
	
	$viewData["swfUrl"]	= KalturaHelpers::getSwfUrlForBaseWidget($widgetUi);
	
	$entryId = $ob->kaltura_video_id;

?>
  
  		<meta property="fb:app_id" content="184865748231073" /> 
 
       
       
     <meta property="og:url" content="<?php echo $ob->url;?>">
    <meta property="og:title" content="<?php echo $ob->title;?>">
    <meta property="og:description" content="<?php echo $ob->description ? $ob->description : 'Minds.org'?>">
    <meta property="og:type" content="video.other">
    <meta property="og:image" content="http://i2.ytimg.com/vi/u_3rGX-MXtM/mqdefault.jpg">
      <meta property="og:video" content="<?php echo $viewData["swfUrl"] . '/entry_id/' . $entryId; ?>">
      <meta property="og:video:type" content="application/x-shockwave-flash">
      <meta property="og:video:width" content="1280">
      <meta property="og:video:height" content="720">
    <meta property="og:site_name" content="Minds, Freedom to share">
    
    <meta name="twitter:card" value="player">
    <meta name="twitter:site" value="@youtube">
      <meta name="twitter:player" value="https://www.youtube.com/embed/u_3rGX-MXtM">
      <meta property="twitter:player:width" content="1280">
      <meta property="twitter:player:height" content="720">

  

<?php } 

?>