<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

$num_display = $vars['entity']->num_display;
if($num_display == ''){
	$num_display = '4';
} 
?>

<p>
		<?php echo elgg_echo("market:num_display"); ?>:
		<select name="params[num_display]">
			<option value="1" <?php if($num_display == 1) echo "SELECTED"; ?>>1</option>
			<option value="2" <?php if($num_display == 2) echo "SELECTED"; ?>>2</option>
			<option value="3" <?php if($num_display == 3) echo "SELECTED"; ?>>3</option>
			<option value="4" <?php if($num_display == 4) echo "SELECTED"; ?>>4</option>
		</select>
</p>

