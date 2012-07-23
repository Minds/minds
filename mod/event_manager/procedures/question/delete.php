<?php 

//annotationId
$returnData = array();

$question_guid = get_input("guid");

if(!empty($question_guid) && $question = get_entity($question_guid))
{
	if(!empty($question) && ($question instanceof EventRegistrationQuestion))
	{
		$question->delete();
	}
	$returnData['valid'] = 1;
}

echo json_encode($returnData);

exit;