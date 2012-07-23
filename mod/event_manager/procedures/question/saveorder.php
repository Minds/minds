<?php

$result = array();

$result['valid'] = 0;

$guid_order = get_input('question');

if($guid_order)
{
	foreach($guid_order as $order => $question_guid)
	{
		if($question_guid && ($question = get_entity($question_guid)))
		{
			if($question instanceof EventRegistrationQuestion)
			{
				$question->order = $order;
			}
		}
	}
	$result['valid'] = 1;
}

echo json_encode($result);

exit();