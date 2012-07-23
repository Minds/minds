<?php

	$registration = $vars["entity"];
	$owner = $registration->getOwnerEntity();
	
	$output .= '<div class="event_manager_registration_info">';
		$output .= '<a class="user" href="'.$owner->getURL().'">'.$owner->name.'</a> - '.friendly_time($registration->time_created).'<br />';
		$output .= '<a href="'.$registration->getURL().'">View registration</a>';
	$output .= '</div>';
	
	$output .= '<div class="event_manager_registration_links">';
	
	/*if($registration->approved)
	{
		$output .= '<img border="0" src="/mod/event_manager/_graphics/icons/check_icon.png" />';
	}
	else
	{
		$output .= '<a href="javascript:void(0);" class="event_manager_registration_approve" rel="'.$registration->getGUID().'" title="Appove registration">Approve</a>';
	}*/
	
	if($registration->approved)
	{
		$output .= '<a href="'.elgg_add_action_tokens_to_url($vars["url"] . "action/event_manager/registration/approve?guid=" . $registration->getGUID()).'&approve=0">'.elgg_echo('disapprove').'</a>';
	}
	else
	{
		$output .= '<a href="'.elgg_add_action_tokens_to_url($vars["url"] . "action/event_manager/registration/approve?guid=" . $registration->getGUID()).'&approve=1">'.elgg_echo('approve').'</a>';
	}
	
	
	$output .= '</div>';

	$icon .= '<div class="event_manager_registration_icon">';
	$icon .= '<img src="/mod/event_manager/_graphics/icons/register_icon.png">';
	$icon .= '</div>';
	
	echo elgg_view_listing($icon, $output);