<?php
/**
 * Elgg show the users who liked the object
 *
 * @uses $vars['annotation']
 */

if (!isset($vars['annotation'])) {
	return true;
}

$like = $vars['annotation'];

$user = $like->getOwnerEntity();
if (!$user) {
	return true;
}

$user_icon = elgg_view_entity_icon($user, 'tiny');
$user_link = elgg_view('output/url', array(
	'href' => $user->getURL(),
	'text' => $user->name,
	'is_trusted' => true,
));


$friendlytime = elgg_view_friendly_time($like->time_created);


$body = <<<HTML
<p class="mbn">
	$user_link +1 $likes_string
	<span class="elgg-subtext">
		$friendlytime
	</span>
</p>
HTML;

echo elgg_view_image_block($user_icon, $body);
