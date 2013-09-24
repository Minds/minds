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
if($user instanceof ElggUser){
	
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
	 * Message button
	 * Subscribe/Un-Subscribe button
	 */
	
	if (elgg_is_logged_in()) {
		if (elgg_get_logged_in_user_guid() != $user->guid) {
				/*echo elgg_view('output/url', array(
										'text' => elgg_echo('chat:message'),
										'href' => "chat/add/?members=".$user->getGUID(),
										'class' => 'elgg-button elgg-button-action channel',
										'is_action' => true
									));*/
			if ($user->isFriend()) {
				echo elgg_view('output/url', array(
										'text' => elgg_echo('friend:remove'),
										'href' => "action/friends/remove?friend={$user->guid}",
										'class' => 'elgg-button elgg-button-action channel subscribed',
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
	} else {
		echo elgg_view('output/url', array(
                    	                              'text' => elgg_echo('friend:add'),
                                                      'href' => "action/friends/add?friend={$user->guid}",
                                                      'class' => 'elgg-button elgg-button-action channel',
                                                      'is_action' => true
                                                   ));

	}
}	
	if($user->canEdit())							
	echo elgg_view('output/url', array(
		'href' => '#widgets-add-panel',
		'text' => elgg_echo('widgets:add'),
		'class' => 'elgg-button elgg-button-action channel',
		'rel' => 'toggle',
		'is_trusted' => true,
	));
	
	/**
	 * Elements drop down
	 * 
	 */
	echo elgg_view_menu('channel_elements');
?>
</div>
