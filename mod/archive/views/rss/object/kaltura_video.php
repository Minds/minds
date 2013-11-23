<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/
return;
	//include_once($CONFIG->pluginspath."kaltura_video/kaltura/api_client/includes.php");

	//for the RSS
	$uob = get_user($vars['entity']->owner_guid);

	$metadata = kaltura_get_metadata($vars['entity']);

	$description = '<p>'.elgg_echo("kalturavideo:label:length").' <b>'.$metadata->kaltura_video_length."</b>\n";
	$description .= elgg_echo("kalturavideo:label:plays").' <b>'.intval($metadata->kaltura_video_plays)."</b>\n";
	$description .= elgg_echo("kalturavideo:label:author").' <b>'.$uob->name.'</b></p>';
	$description .= $vars['entity']->description;

	$title = $vars['entity']->title;
	if (empty($title)) {
		$title = substr($description,0,32);
		if (strlen($description) > 32)
			$title .= " ...";
	}

?>

	<item>
	  <guid isPermaLink='true'><?php echo $vars['entity']->getURL(); ?></guid>
	  <pubDate><?php echo date("r",$vars['entity']->time_created) ?></pubDate>
	  <link><?php echo $vars['entity']->getURL(); ?></link>
	  <title><![CDATA[<?php echo $title; ?>]]></title>
	  <description><![CDATA[<?php echo (autop($description)); ?>]]></description>
	</item>
