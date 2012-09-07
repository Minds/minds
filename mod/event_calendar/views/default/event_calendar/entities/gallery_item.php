<?php

	/**
	 * Elgg user display (gallery)
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */
	 
$icon = elgg_view(
		"profile/icon", array(
								'entity' => $vars['entity'],
								'size' => 'medium',
							  )
	);
	
$banned = $vars['entity']->isBanned();

$rel = "";
if (page_owner() == $vars['entity']->guid)
	$rel = 'me';
else if (check_entity_relationship(page_owner(), 'friend', $vars['entity']->guid))
	$rel = 'friend';

if (!$banned)
	$info .= "<p><b><a href=\"" . $vars['entity']->getUrl() . "\" rel=\"$rel\">" . $vars['entity']->name . "</a></b></p>";
else
	$info .= "<p><b><strike>" . $vars['entity']->name . "</b></strike><br />".elgg_echo('profile:banned')."</p>";

// TODO: look into a way to pass $authorised and $event_id in $vars
$authorised = FALSE;
$event_id = get_input('event_id', 0);
if ($event_id) {
	if(isadminloggedin()) {
		$authorised = TRUE;
	} else {
		// load the event from the database
		$event = get_entity($event_id);
		$user_id = get_loggedin_userid();
		if ($event && ($event->owner_guid == $user_id)) {
			$authorised = TRUE;
		}		
	}
}

if ($authorised) {
	$link = '<p><a href="#" ';
	$link .= 'onclick="javascript:event_calendar_personal_toggle('.$event_id.','.$vars['entity']->guid.'); return false;" ';
	$link .= ' >';
	$link .= '<span id="event_calendar_user_data_'.$vars['entity']->guid.'">'.elgg_echo('event_calendar:remove_from_the_calendar').'</span>';
	$link .= '</a></p>';
	$info .= $link;	
}

// echo elgg_view_listing($icon, $info);
echo elgg_view('search/gallery_listing',array('icon' => $icon, 'info' => $info));
			
?>