<?php 
$options = array(
	'types' => 'object',
	'subtypes' => 'wallpost',
	'limit' => 0,
	'owner_guid'=>elgg_get_logged_in_user_guid()
);
$wallposts = elgg_get_entities($options);
?>
<div id="dashboard1">
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
	<li><?php echo elgg_echo('friends:of');?>: <?php echo count($user->getFriendsOf());?></li>
	<li><?php echo elgg_echo('friends');?>: <?php echo count($user->getFriends());?></li>
	<li><?php echo elgg_echo('minds:thoughts');?>: <?php echo count($wallposts); ?></li>
</ul>
</ul>
</div> <!-- /dashboard_navigation -->
</div> <!-- /dasboard1 -->
