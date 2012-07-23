<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/
	 //type
	 $type = $vars['type'];

	 //set the url
	 $url = $vars['url'] . "pg/kaltura_video_admin/?type=";

?>

<div id="elgg_horizontal_tabbed_nav">
<ul>
	<li <?php if($type == 'server' || $type == 'partner_wizard') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>server"><?php echo elgg_echo('kalturavideo:menu:server'); ?></a></li>
	<li <?php if($type == 'custom') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>custom"><?php echo elgg_echo('kalturavideo:menu:custom'); ?></a></li>
	<li <?php if($type == 'behavior') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>behavior"><?php echo elgg_echo('kalturavideo:menu:behavior'); ?></a></li>
	<li <?php if($type == 'advanced') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>advanced"><?php echo elgg_echo('kalturavideo:menu:advanced'); ?></a></li>
	<li <?php if($type == 'credits') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>credits"><?php echo elgg_echo('kalturavideo:menu:credits'); ?></a></li>
</ul>
</div>
