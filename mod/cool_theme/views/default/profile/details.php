<?php
/**
 * Elgg user display (details)
 * @uses $vars['entity'] The user entity
 */

$user = elgg_get_page_owner_entity();

$profile_fields = elgg_get_config('profile_fields');

echo "<dl class=\"elgg-profile\">";
if (is_array($profile_fields) && sizeof($profile_fields) > 0) {
	foreach ($profile_fields as $shortname => $valtype) {
		if ($shortname == "description") {
			// skip about me and put at bottom
			continue;
		}
		$value = $user->$shortname;
		if (!empty($value)) {
?>
			<dt><?php echo elgg_echo("profile:{$shortname}"); ?></dt>
			<dd><?php echo elgg_view("output/{$valtype}", array('value' => $user->$shortname)); ?></dd>
<?php
		}
	}
}

if (!elgg_get_config('profile_custom_fields')) {
	if ($user->isBanned()) {
		echo "</dl><p class='profile-banned-user'>";
		echo elgg_echo('banned');
		echo "</p>";
	} else {
		if ($user->description) {
			echo "<dt>" . elgg_echo("profile:aboutme") . "</dt>";
			echo "<dd>";
			echo elgg_view('output/longtext', array('value' => $user->description));
			echo "</dd></dl>";
		}
	}
}