<?php
	/**
	 * Elgg Poll post widget edit view
	 *  
	 * @package Elggpoll
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author John Mellberg
	 * @copyright John Mellberg 2009
	 * 
	 * @uses $vars['entity'] Optionally, the poll post to view
	 */
?>
<p>
	<?php echo elgg_echo('polls:widget:label:displaynum'); ?>
	
	<select name="params[limit]">
		<option value="5" <?php if ((!$vars['entity']->limit) && ($vars['entity']->limit == 5)) echo " selected=\"yes\" "; ?>>5</option>
		<option value="10" <?php if ($vars['entity']->limit == 10) echo " selected=\"yes\" "; ?>>10</option>
		<option value="15" <?php if ($vars['entity']->limit == 15) echo " selected=\"yes\" "; ?>>15</option>
		<option value="20" <?php if ($vars['entity']->limit == 20) echo " selected=\"yes\" "; ?>>20</option>
	</select>
</p>