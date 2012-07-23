<?php 

$registrationId = get_input("guid");
$approve = get_input("approve", 0);

if(!empty($registrationId) && $entity = get_entity($registrationId))
{
	if($entity->getSubtype() == EventRegistration::SUBTYPE)
	{
		$registration = $entity;
		if($registration->canEdit())
		{
			$registration->approved = $approve;
		}
		forward(REFERER);
	}
}


register_error(elgg_echo("event_manager:registration_not_found"));
forward(REFERER);