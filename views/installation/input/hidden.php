<?php
/**
 * Elgg text input
 * Displays a text input field
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name']  The name of the input field
 * @uses $vars['class'] CSS class
 */

$value = htmlentities($vars['value'], ENT_QUOTES, 'UTF-8');

?>
<input type="hidden" name="<?php echo $vars['name']; ?>" value="<?php echo $value; ?>" <?php echo $class; ?> />