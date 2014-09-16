<?php

$username = get_input('user', 'false');
$user = get_user_by_username($username);
elgg_set_page_owner_guid($user->guid);

if (!$user) {
	echo elgg_echo('deck_river:user-not-exist');
	return;
}
?>
<ul class="elgg-tabs elgg-htabs man">
	<li class="elgg-state-selected"><a href="#<?php echo $user->guid; ?>-info-profile"><?php echo elgg_echo('profile'); ?></a></li>
	<li><a href="#<?php echo $user->guid; ?>-info-activity"><?php echo elgg_echo('activity'); ?></a></li>
	<li><a href="#<?php echo $user->guid; ?>-info-mentions"><?php echo elgg_echo('river:mentions'); ?></a></li>
</ul>
<ul class="elgg-body">
	<li id="<?php echo $user->guid; ?>-info-profile" class="mts">
		<div class="elgg-avatar elgg-avatar-large float">
			<a href="<?php echo $user->getURL(); ?>" title="<?php echo $user->username; ?>">
				<span class="gwfb hidden"><br><?php echo elgg_echo('deck_river:go_to_profile'); ?></span>
				<div class="avatar-wrapper center">
					<?php
						echo elgg_view('output/img', array(
							'src' => elgg_format_url($user->getIconURL('large')),
							'alt' => $user->username,
							'title' => $user->username,
							'width' => '200px'
						));
					?>
				</div>
			</a>
		</div>

		<div class="elgg-body plm">
			<h1 class="pts mbs"><?php echo $user->realname; ?></h1>
			<h2><a href="#" class="elgg-user-info-popup info-popup mbs" style="font-weight:normal;" title="<?php echo $user->username; ?>"><?php echo '@' . $user->username; ?></a></h2>
			<div><?php echo deck_river_wire_filter($user->briefdescription); ?></div>

			<?php
			if (elgg_is_logged_in() && $user->getGUID() != elgg_get_logged_in_user_guid()) {
				echo '<ul class="elgg-menu profile-action-menu mvm float">';
				if ($user->isFriend()) {
					echo elgg_view('output/url', array(
						'href' => elgg_add_action_tokens_to_url("action/friends/remove?friend={$user->guid}"),
						'text' => elgg_echo('friend:remove'),
						'class' => 'elgg-button elgg-button-action gwfb remove_friend'
					));
				} else {
					echo elgg_view('output/url', array(
						'href' => elgg_add_action_tokens_to_url("action/friends/add?friend={$user->guid}"),
						'text' => elgg_echo('friend:add'),
						'class' => 'elgg-button elgg-button-action gwfb add_friend'
					));
				}
				echo '</ul>';
			}
			?>
		</div>

		<?php
			echo elgg_view_menu('owner_block', array(
				'entity' => $user,
				'class' => 'profile-content-menu tiny',
			));
		?>

		<div id="profile-details" class="elgg-body pll">
			<?php
				echo elgg_view('profile/details');
			?>
		</div>
	</li>
	<li id="<?php echo $user->guid; ?>-info-activity" class="column-river hidden">
		<div class="message-box"><div class="column-messages"></div></div>
		<ul class="column-header hidden" data-network="elgg" data-river_type="entity_river" data-entity="<?php echo $user->guid; ?>"></ul>
		<?php
			echo elgg_view('output/url', array(
				'text' => elgg_view_icon('search'),
				'title' => elgg_echo('deck_river:filter'),
				'href' => "#",
				'class' => "elgg-column-filter-button tooltip s",
			));
			echo elgg_view('page/layouts/content/deck_river_column_filter');
		?>
		<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
	</li>
	<li id="<?php echo $user->guid; ?>-info-mentions" class="column-river hidden">
		<div class="message-box"><div class="column-messages"></div></div>
		<ul class="column-header hidden" data-network="elgg" data-river_type="entity_mention" data-entity="<?php echo $user->guid; ?>"></ul>
		<?php
			echo elgg_view('output/url', array(
				'text' => elgg_view_icon('search'),
				'title' => elgg_echo('deck_river:filter'),
				'href' => "#",
				'class' => "elgg-column-filter-button tooltip s",
			));
			echo elgg_view('page/layouts/content/deck_river_column_filter',  array(
				'column_settings' => array('type' => 'mention')
			));
		?>
		<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
	</li>
</ul>
