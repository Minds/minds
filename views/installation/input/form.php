<?php
/**
 * Create a form for data submission.
 *
 * @uses $vars['body']   The body of the form (made up of other input/xxx views and html
 * @uses $vars['action'] URL of the action being called
 * @uses $vars['method'] Method (default POST)
 * @uses $vars['name']   Form name
 */

if (isset($vars['name'])) {
	$name = "name=\"{$vars['name']}\"";
} else {
	$name = '';
}

$body = $vars['body'];
unset($vars['body']);
$action = $vars['action'];
if (!isset($vars['method'])) {
	$vars['method'] = 'POST';
}

$method = strtolower($method);

$attributes = elgg_format_attributes($vars);
?>
<form <?php echo $attributes;?>>
<?php echo $body; ?>
</form>
