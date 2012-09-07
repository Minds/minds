<?php

foreach($vars['requests'] as $request) {
	if ($request instanceof ElggUser) {
		$icon = elgg_view("profile/icon", array(
			'entity' => $request,
			'size' => 'small'
		));
		$info = '<a href="' . $request->getURL() . '" >'.$request->name.'</a>';
		$info .= '<div style="margin-top: 5px;" ></div>';
		$info .=  elgg_view('output/confirmlink',
			array(
			'class' => "elgg-button elgg-button-delete",
			'href' => 'action/event_calendar/killrequest?user_guid='.$request->guid.'&event_guid=' . $vars['entity']->guid,
			'confirm' => elgg_echo('event_calendar:request:remove:check'),
			'text' => elgg_echo('event_calendar:review_requests:reject'),
			'title' => elgg_echo('event_calendar:review_requests:reject:title'),
		));
		$info .= '&nbsp;&nbsp;';
		$info .= elgg_view('output/url', array(
			'text' => elgg_echo('event_calendar:review_requests:accept'),
			'title' => elgg_echo('event_calendar:review_requests:accept:title'),
			'href' => "action/event_calendar/addtocalendar?user_guid={$request->guid}&event_guid={$vars['entity']->guid}",
			'class' => "elgg-button elgg-button-submit",
			'is_action' => TRUE,
		));
		//$url = elgg_add_action_tokens_to_url("{$vars['url']}action/event_calendar/addtocalendar?user_guid={$request->guid}&event_guid={$vars['entity']->guid}");
		echo elgg_view_image_block($icon,$info);
	}
}
