<?php 

$returnData = array();

$returnData['valid'] = 0;

$guid = get_input("guid");

if(!empty($guid) && ($eventDay = get_entity($guid)))
{
	if($eventDay->getSubtype() == EventDay::SUBTYPE)
	{
		if($eventDay->delete())
		{
			$returnData['valid'] = 1;
		} 
	}
}

echo json_encode($returnData);

exit;