<?php

$group_name = get_input('group', 'false');
$group_id = search_group_by_title($group_name);
elgg_set_page_owner_guid($group_id);
$group = get_entity($group_id);
$user = elgg_get_logged_in_user_entity();

if (!$group) {
	echo elgg_echo('deck_river:group-not-exist');
	return;
}
?>
<ul class="elgg-tabs elgg-htabs man">
	<li class="elgg-state-selected"><a href="#<?php echo $group_id; ?>-info-profile"><?php echo elgg_echo('profile'); ?></a></li>
	<li><a href="#<?php echo $group_id; ?>-info-activity"><?php echo elgg_echo('activity'); ?></a></li>
	<li><a href="#<?php echo $group_id; ?>-info-mentions"><?php echo elgg_echo('river:mentions'); ?></a></li>
</ul>
<ul class="elgg-body">
	<li id="<?php echo $group_id; ?>-info-profile" class="mts">
		<div class="elgg-avatar elgg-avatar-large float">
			<a href="<?php echo $group->getURL(); ?>" title="<?php echo $group->title; ?>">
				<span class="gwfb hidden"><br><?php echo elgg_echo('deck_river:go_to_profile'); ?></span>
				<div class="avatar-wrapper center">
					<?php
						echo elgg_view('output/img', array(
							'src' => elgg_format_url($group->getIconURL('large')),
							'alt' => $group->title,
							'title' => $group->title,
							'width' => '200px'
						));
					?>
				</div>
			</a>
		</div>

		<div class="elgg-body plm">
			<h1 class="mbm"><?php echo $group->name; ?></h1>
			<div><?php echo deck_river_wire_filter($group->briefdescription); ?></div>

			<?php
				$profile_actions = '<ul class="elgg-menu profile-action-menu mvm float">';
				// group members
				if ($group->isMember($user) && !$group->canEdit()) {
					if ($group->getOwnerGUID() != $user->guid && !is_user_group_admin($user, $group)) {
						$profile_actions .= '<li class="elgg-menu-item-groups-leave">' .
							'<a href="'. elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/groups/leave?group_id}") . '" class="elgg-button elgg-button-action leave-button gwfb">' .
								elgg_echo('groups:leave') .
							'</a></li>';
					}
				} elseif (elgg_is_logged_in() && !in_array($group->getSubtype(), array('metagroup', 'typogroup'))) {
					if ($group->isPublicMembership() || $group->canEdit()) {
						$profile_actions .= '<li class="elgg-menu-item-groups-join">' .
							'<a href="'. elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/groups/join?group_id}") . '" class="elgg-button elgg-button-action join-button gwfb">' .
								elgg_echo('groups:join') .
							'</a></li>';
					} else {
						// request membership
						$profile_actions .= '<li class="elgg-menu-item-groups-join">' .
							'<a href="'. elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/groups/join?group_id}") . '" class="elgg-button elgg-button-action join-button gwfb">' .
								elgg_echo('groups:joinrequest') .
							'</a></li>';
					}
				}

				echo $profile_actions . '</ul>';

			?>
		</div>

		<?php
			echo elgg_view_menu('owner_block', array(
				'entity' => $group,
				'class' => 'profile-content-menu tiny',
			));
		?>

		<!-- <ul class="groups-stats float">
			<li class="members">
				<div class="stats mls mrm"><?php echo $group->getMembers(0, 0, TRUE); ?></div>
				<h3><?php echo elgg_echo('groups:summary:members'); ?></h3>
				<div class="mls mtm"><?php echo elgg_echo('groups:summary:created', array(strtolower(elgg_view('output/friendlytime', array('time' => $group->time_created))))); ?></div>
			</li>

			<li>
				<h3><?php echo elgg_echo("groups:owner"); ?></h3>
				<?php
					$owner = $group->getOwnerEntity();
					echo elgg_view_entity_icon($owner, 'small', array('class' => 'float mts mrs'));
					echo elgg_view('output/url', array(
						'text' => $owner->name,
						'value' => $owner->getURL(),
						'is_trusted' => true,
					));
				?>
			</li>

			<?php echo elgg_view('groups_admins_elections/list_mandats'); ?>

		</ul> -->

	</li>
	<li id="<?php echo $group_id; ?>-info-activity" class="column-river hidden">
		<ul class="column-header hidden" data-network="elgg" data-river_type="entity_river" data-entity="<?php echo $group_id; ?>"></ul>
		<?php
			echo elgg_view('output/url', array(
				'text' => elgg_view_icon('search'),
				'title' => elgg_echo('deck_river:filter'),
				'href' => "#",
				'class' => "elgg-column-filter-button tooltip s",
			));
			echo elgg_view('page/layouts/content/deck_river_column_filter', array(
				'column_settings' => array('type' => 'group')
			));
		?>
		<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
	</li>
	<li id="<?php echo $group_id; ?>-info-mentions" class="column-river hidden">
		<ul class="column-header hidden" data-network="elgg" data-river_type="entity_mention" data-entity="<?php echo $group_id; ?>"></ul>
		<?php
			echo elgg_view('output/url', array(
				'text' => elgg_view_icon('search'),
				'title' => elgg_echo('deck_river:filter'),
				'href' => "#",
				'class' => "elgg-column-filter-button tooltip s",
			));
			echo elgg_view('page/layouts/content/deck_river_column_filter', array(
				'column_settings' => array('type' => 'group_mention')
			));
		?>
		<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
	</li>
</ul>
