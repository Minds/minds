<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

require_once(dirname(dirname(__FILE__))."/api_client/includes.php");

$entryId = @$_REQUEST['entryId'];

$widget_l = kaltura_create_generic_widget_html ( $entryId , 'l' );
$widget_m = kaltura_create_generic_widget_html ( $entryId , 'm' );

$ob = kaltura_get_entity($entryId);
$metadata = kaltura_get_metadata($ob);

if($metadata->kaltura_video_widget_html && !in_array(get_plugin_setting("alloweditor","kaltura_video"), array('no'))) $widget_l = $metadata->kaltura_video_widget_html;
if($metadata->kaltura_video_widget_m_html && !in_array(get_plugin_setting("alloweditor","kaltura_video"), array('no'))) $widget_m = $metadata->kaltura_video_widget_m_html;

//create the object manually
$obj_l = kaltura_build_widget_object($metadata,$widget_l);
$obj_m = kaltura_build_widget_object($metadata,$widget_m);

$pop_out_l_url = $obj_l->swf;
$pop_out_m_url = $obj_m->swf;

$entryForPopout = $entryId;
$kmodel = KalturaModel::getInstance();
try {
    $mediaEntries = $kmodel->listMixMediaEntries($entryId);
} catch(Exception $e) {
}
if(count($mediaEntries) > 0){
    $entryForPopout = $mediaEntries[0]->id . "&streamerType=rtmp&streamerUrl=rtmp://rtmpakmi.kaltura.com/ondemand&rtmpFlavors=1&&";
}

?>

<script type='text/javascript'>

widget_html_l = '<?php echo str_replace("'","\'",$widget_l . '<p class=clear"><a href="' . $pop_out_l_url . '?entryId=' . $entryForPopout . '" target="_blank" class="submit_button">popOut</a></p>'); ?>';
widget_html_m = '<?php echo str_replace("'","\'",$widget_m . '<p class=clear"><a href="' . $pop_out_m_url . '?entryId=' . $entryForPopout . '" target="_blank" class="submit_button">popOut</a></p>'); ?>';
obj_l = {<?php
	$parts = array();
	foreach($obj_l as $k => $v) {
		$parts[] = "$k:'".str_replace("'","\'",$v)."'";
	}
	echo implode(",\n",$parts);
?>};
obj_m = {<?php
	$parts = array();
	foreach($obj_m as $k => $v) {
		$parts[] = "$k:'".str_replace("'","\'",$v)."'";
	}
	echo implode(",\n",$parts);
?>};


</script>

<div class="left box">
<div class="videoPreview"><img src="<?php echo $metadata->kaltura_video_thumbnail; ?>/width/250/height/244" alt="<?php echo $ob->title; ?>" /></div>
<!--
maybe can be added: crop_provider/wordpress_comment_placeholder when click img works
-->
</div>

<div class="box">
<h3><?php echo elgg_echo('kalturavideo:label:select_size'); ?>:</h3>

<p><input type="radio" id="sizel" name="size" value="l" checked="true" /> <?php echo elgg_echo('kalturavideo:label:large'); ?> (<?php echo ($obj_l->width."x".$obj_l->height); ?>)</p>
<p><input type="radio" id="sizem" name="size" value="m" /> <?php echo elgg_echo('kalturavideo:label:small'); ?> (<?php echo ($obj_m->width."x".$obj_m->height); ?>)</p>
<p>&nbsp;</p>
<p class="margin"><a href="#" class="kalturaButton" id="finishVideo"><?php echo elgg_echo('kalturavideo:label:insert'); ?></a> &nbsp;
<a href="#" class="kalturaButton" id="gallery"><?php echo elgg_echo('kalturavideo:label:gallery'); ?></a> &nbsp;
<a href="#" class="kalturaButton" id="cancel"><?php echo elgg_echo('kalturavideo:label:cancel'); ?></a></p>
</div>

<div class="clear"></div>
