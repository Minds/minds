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

	$class = $vars['class'];
	if (!$class) $class = "input-pulldown";

?>


<select name="<?php echo $vars['name']; ?>" <?php if (isset($vars['internalid'])) echo "id=\"{$vars['internalid']}\""; ?> <?php echo $vars['js']; ?> <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> class="<?php echo $class; ?>">
<?php
	if ($vars['options_values'])
	{
		foreach($vars['options_values'] as $value => $option) {
	        if ($value != $vars['value']) {
	            echo "<option value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
	        } else {
	            echo "<option value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\" selected=\"selected\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
	        }
	    }
	}
	else
	{
	    foreach($vars['options'] as $option) {
	        if ($option != $vars['value']) {
	            echo "<option value=\"".htmlentities($option, ENT_QUOTES, 'UTF-8')."\">". elgg_echo(htmlentities($option, ENT_QUOTES, 'UTF-8')) ."</option>";
	        } else {
	            echo "<option value=\"".htmlentities($option, ENT_QUOTES, 'UTF-8')."\" selected=\"selected\">". elgg_echo(htmlentities($option, ENT_QUOTES, 'UTF-8')) ."</option>";
	        }
	    }
	}
?> 
</select>
