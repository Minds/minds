<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
*
* Inspired in iZAP video widget!
**/

$total = (int) get_plugin_setting('numindexvideos','kaltura_video');
if(!$total) $total = 4;

$single = true;
if(get_plugin_setting('enableindexwidget', 'kaltura_video') == 'multi') $single = false;

if($single) $title = elgg_echo('kalturavideo:label:latest');
else $title = elgg_echo('kalturavideo:index:toplatest');

$url = $vars['url'].'/mod/kaltura_video/ajax-listvideos.php?total='.$total.'&type=';

?>
<div class="index_box">
  <a href="<?php echo $vars['url']?>pg/kaltura_video/">
    <?php echo elgg_view_title($title); ?>
  </a>
<?php
if(!$single) {
?>
	<div class="contentWrapper">
		<div id="elgg_horizontal_tabbed_nav">
		<ul id="kTabVideos">
			<li class = 'selected'><a href="#" rel="<?php echo $url; ?>latest"><?php echo elgg_echo('kalturavideo:index:latest'); ?></a></li>
			<li><a href="#" rel="<?php echo $url; ?>played"><?php echo elgg_echo('kalturavideo:index:played'); ?></a></li>
			<li><a href="#" rel="<?php echo $url; ?>commented"><?php echo elgg_echo('kalturavideo:index:commented'); ?></a></li>
<?php
		if(get_plugin_setting('enablerating','kaltura_video') == 'yes') {
?>
			<li><a href="#" rel="<?php echo $url; ?>rated"><?php echo elgg_echo('kalturavideo:index:rated'); ?></a></li>
<?php
		}
?>
		</ul>
		</div>
	</div>
<?php
}
?>
	<div id="kVideoContainer"></div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		//click on a
		$('#kTabVideos li a').click(function(){
			$('#kTabVideos li').removeClass('selected');
			$(this).parent().addClass('selected');
			kShowVideos($(this).attr('rel'));
			return false;
		});
		function kShowVideos(url) {
			$('#kVideoContainer').html('<p style="text-align:center;"><img src="<?php echo $vars['url']?>mod/kaltura_video/kaltura/editor/images/loadingAnimation.gif" /></p>');
			$('#kVideoContainer').load(url);
		}
		//initial loading
		kShowVideos('<?php echo $url.'latest'; ?>');
	});
</script>
