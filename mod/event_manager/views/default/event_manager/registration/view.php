<?php 

	$registration = $vars["entity"];
	$owner = $registration->getOwnerEntity();
	
	$output .= '<div class="event_manager_registration_info">';
		$output .= '<a class="user" href="'.$owner->getURL().'">'.$owner->name.'</a> - '.friendly_time($registration->time_created).'<br />';
	$output .= '</div>';
	
	$answers = $registration->getAnnotations('answer', 100, 0, 'a.id asc');
	
	if($answers)
	{
		foreach($answers as $answer)
		{
			$answerExplode = explode('|', $answer->value);
				$answerId = $answerExplode[0]; 
				$answerValue = $answerExplode[1];
			
			$question = get_annotation($answerId);
			
			$output .= '<br /><h3>'.$question->value.'</h3>';
			$output .= $answerValue.'<br />';
		}
	}
	
	echo elgg_view_module("main", "", $output);