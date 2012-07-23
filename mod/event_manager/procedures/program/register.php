<?php 

$returnData['valid'] = 0;

$eventGuid = get_input('event');
$guids = get_input('guids');

$user = elgg_get_logged_in_user_entity();

if(!empty($guids) && !empty($eventGuid) && ($event = get_entity($eventGuid)))
{
	foreach($event->getEventDays() as $eventDay)
	{
		foreach($eventDay->getEventSlots() as $eventSlot)
		{
			$user->removeRelationship($eventSlot->getGUID(), EVENT_MANAGER_RELATION_SLOT_REGISTRATION);
		}
	}
	$guidArray = explode(', ', str_replace(array('"', '[', ']'), '', $guids));
	
	foreach($guidArray as $slotGuid)
	{
		if($eventSlot = get_entity($slotGuid))
		{
			$user->addRelationship($eventSlot->getGUID(), EVENT_MANAGER_RELATION_SLOT_REGISTRATION);			 	
		}
	}
	$returnData['valid'] = 1;
}

echo json_encode($returnData);

exit;