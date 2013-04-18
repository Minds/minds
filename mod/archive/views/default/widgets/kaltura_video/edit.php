<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

?>
<p>
		<?php echo elgg_echo("kalturavideo:showmode"); ?>:
		<select name="params[show_mode]">
		<?php
		foreach(array('embed','thumbnail') as $i) {
			echo '<option value="'.$i.'"'.($vars['entity']->show_mode == $i ? ' selected="selected"':'').'>'.elgg_echo("kalturavideo:showmode:$i")."</option>\n";
		}
		?>

		</select>
</p>

<p>
		<?php echo elgg_echo("kalturavideo:num_display"); ?>:
		<select name="params[num_display]">
		<?php
		for($i=1; $i<11; $i++) {
			echo '<option value="'.$i.'"'.($vars['entity']->num_display == $i ? ' selected="selected"':'').'>'.$i."</option>\n";
		}
		?>

		</select>
</p>

<p>
		<?php echo elgg_echo("kalturavideo:start_display"); ?>:
		<select name="params[start_display]">
		<?php
		for($i=1; $i<11; $i++) {
			echo '<option value="'.$i.'"'.($vars['entity']->start_display == $i ? ' selected="selected"':'').'>'.$i."</option>\n";
		}
		?>
		</select>
</p>
