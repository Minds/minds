<?php 
$guid = (int) get_input("guid");

if(!empty($guid) && $entity = get_entity($guid))
{
	if($entity->getSubtype() == Event::SUBTYPE)
	{
		$event = $entity;
		if($event->delete())
		{
			system_message(elgg_echo("event_manager:action:event:delete:ok"));
		} 
		forward(EVENT_MANAGER_BASEURL);
	}
}

system_message(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
forward(REFERER);