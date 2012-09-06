<?php
/**
 * Button area for showing the add widgets panel
 */
 $user = elgg_get_page_owner_entity();

//this needs to be loaded before the widget is added. 
elgg_load_js('elgg.wall');
?>
<div class="elgg-widget-add-control">
<?php
	echo elgg_view_title($user->name);
	
	if($user->canEdit())
	echo elgg_view('output/url', array(	'href' => '/avatar/edit/' . $user->username,
										'text' => elgg_echo('avatar:edit'),
										'class' => 'elgg-button elgg-button-action channel'
									));
	if($user->canEdit())								
	echo elgg_view('output/url', array(	'href' => '/channel/' . $user->username .'/edit',
										'text' => elgg_echo('profile:edit'),
										'class' => 'elgg-button elgg-button-action channel'
									));
	if($user->canEdit()){
		echo elgg_view('output/url', array(	'href' => '/channel/' . $user->username .'/custom',
										'text' => elgg_echo('channel:custom'),
										'class' => 'elgg-button elgg-button-action channel'
									));
	}
	
	/**
	 * Subscribe/Un-Subscribe button
	 */
	
	if (elgg_is_logged_in()) {
		if (elgg_get_logged_in_user_guid() != $user->guid) {
			if ($user->isFriend()) {
				echo elgg_view('output/url', array(
										'text' => elgg_echo('friend:remove'),
										'href' => "action/friends/remove?friend={$user->guid}",
										'class' => 'elgg-button elgg-button-action channel',
										'is_action' => true
									));
			} else {
				echo elgg_view('output/url', array(	
										'text' => elgg_echo('friend:add'),
										'href' => "action/friends/add?friend={$user->guid}",
										'class' => 'elgg-button elgg-button-action channel',
										'is_action' => true
									));
			}
		}
	}
									
	echo elgg_view('output/url', array(
		'href' => '#widgets-add-panel',
		'text' => elgg_echo('widgets:add'),
		'class' => 'elgg-button elgg-button-action channel',
		'rel' => 'toggle',
		'is_trusted' => true,
	));
?>
</div>
