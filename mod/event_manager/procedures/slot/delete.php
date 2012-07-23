<?php 

$returnData = array();

$returnData['valid'] = 0;

$guid = get_input("guid");

if(!empty($guid) && $eventSlot = get_entity($guid))
{
	if($eventSlot->getSubtype() == EventSlot::SUBTYPE)
	{
		if($eventSlot->delete())
		{
			$returnData['valid'] = 1;
		} 
	}
}

echo json_encode($returnData);

exit;