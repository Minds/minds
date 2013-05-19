<?php 
$options = array(
	'types' => 'object',
	'subtypes' => 'wallpost',
	'limit' => 0,
	'owner_guid'=>elgg_get_logged_in_user_guid()
);
$wallposts = elgg_get_entities($options);
?>
<!-- displayes user's avatar -->
<?php 
	$user = elgg_get_page_owner_entity();
	$icon_url = elgg_format_url($user->getIconURL('large'));
	$icon = elgg_view('output/img', array(
							'src' => $icon_url,
							'alt' => $user->name,
							'title' => $user->name,
						));
	echo "<div id=\"river_avatar\">" . $icon . "</div>"; 
    ?>
<!-- /river_avatar -->
<div id="dashboard_navigation">
<ul>
	<li><?php echo elgg_view('output/url', array( 	'text' => elgg_echo('friends:of') . ': ' . count($user->getFriendsOf(null, 0)),
													'href' => 'channels/subscribers'
												));?>
	</li>
	<li><?php echo elgg_view('output/url', array( 	'text' => elgg_echo('friends') . ': ' . count($user->getFriends(null, 0)),
													'href' => 'channels/subscriptions'
												));?>
	</li>
	<li><?php echo elgg_view('output/url', array( 	'text' => elgg_echo('minds:thoughts') . ': ' . count($wallposts),
													'href' => 'wall/'. $user->username
												));?>
	</li>
</ul>
</ul>
</div> <!-- /dashboard_navigation -->