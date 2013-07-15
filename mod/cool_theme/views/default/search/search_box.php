<?php
/**
 * Search box
 *
 * @uses $vars['value'] Current search query
 *
 * @todo Move javascript into something that extends elgg.js
 */

$value = '';
if (array_key_exists('value', $vars)) {
	$value = $vars['value'];
} elseif ($value = get_input('q', get_input('tag', NULL))) {
	$value = $value;
}

// @todo - why the strip slashes?
$value = stripslashes($value);

// @todo - create function for sanitization of strings for display in 1.8
// encode <,>,&, quotes and characters above 127
$display_query = mb_convert_encoding($value, 'HTML-ENTITIES', 'UTF-8');
$display_query = htmlspecialchars($display_query, ENT_QUOTES, 'UTF-8', false);


?>

<form class="elgg-search" action="<?php echo elgg_get_site_url(); ?>search" method="get">
	<fieldset>
		<input type="text" size="21" name="q" placeholder="<?php echo elgg_echo('search'); ?>" class="search-input" value="<?php echo $value; ?>"/>
		<input type="submit" value="<?php echo elgg_echo('search:go'); ?>" class="search-submit-button" />
	</fieldset>
</form>