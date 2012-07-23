<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

global $SKIP_KALTURA_REWRITE;
if(empty($SKIP_KALTURA_REWRITE)) {

?>
<script type="text/javascript">
/* <![CDATA[ */

function KalturaVideoStartModal() {
	KalturaTextArea = $('textarea[name="<?php echo $vars['internalname']; ?>"]');
	KalturaModal.openModal("TB_window", "<?php echo $vars['url']; ?>mod/kaltura_video/kaltura/editor/init.php", { width: 240, height: 60 } );
	return false;
}

/* ]]> */
</script>


<a class="embed_kaltura" href="Javascript://" onclick="return KalturaVideoStartModal();"><img src="<?php echo $vars['url']; ?>mod/kaltura_video/kaltura/images/interactive_video_button.gif" alt="<?php echo elgg_echo('kalturavideo:label:addvideo'); ?>" title="<?php echo elgg_echo('kalturavideo:label:addvideo'); ?>" style="vertical-align:top;" /><?php echo elgg_echo('kalturavideo:label:addvideo'); ?></a>

<?php
}
?>
