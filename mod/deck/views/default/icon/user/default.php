<?php
/**
 * Elgg user icon
 *
 * Rounded avatar corners - CSS3 method
 * uses avatar as background image so we can clip it with border-radius in supported browsers
 *
 * @uses $vars['entity']     The user entity. If none specified, the current user is assumed.
 * @uses $vars['size']       The size - tiny, small, medium or large. (medium)
 * @uses $vars['use_hover']  Display the hover menu? (true)
 * @uses $vars['use_link']   Wrap a link around image? (true)
 * @uses $vars['class']      Optional class added to the .elgg-avatar div
 * @uses $vars['img_class']  Optional CSS class added to img
 * @uses $vars['link_class'] Optional CSS class for the link
 * @uses $vars['href']       Optional override of the link href
 */

$user = elgg_extract('entity', $vars, elgg_get_logged_in_user_entity());
$size = elgg_extract('size', $vars, 'medium');
if (!in_array($size, array('topbar', 'tiny', 'small', 'medium', 'large', 'master'))) {
	$size = 'medium';
}

$class = "elgg-avatar elgg-avatar-$size";
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

$use_link = elgg_extract('use_link', $vars, true);

if (!($user instanceof ElggUser)) {
	return true;
}

$username = $user->username;

$icontime = $user->icontime;
if (!$icontime) {
	$icontime = "default";
}

$img_class = '';
if (isset($vars['img_class'])) {
	$img_class = $vars['img_class'];
}

$icon_url = elgg_format_url($user->getIconURL($size));
$icon = elgg_view('output/img', array(
	'src' => $icon_url,
	'alt' => $username,
	'title' => $username,
	'class' => $img_class,
));

?>
<div class="<?php echo $class; ?>">
<?php

if ($use_link) {
	$url = elgg_extract('href', $vars, false);
	if (!$url) {
		echo "<div class='elgg-user-info-popup info-popup' title='$username'>$icon</div>";
	} else {
		$class = elgg_extract('link_class', $vars, '');
		echo elgg_view('output/url', array(
			'href' => $url,
			'text' => $icon,
			'is_trusted' => true,
			'class' => $class . ' elgg-user-info-popup info-popup',
			'rel' => 'nofollow',
			'title' => $username
		));
	}
} else {
	echo "$icon";
}
?>
</div>
