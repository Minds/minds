<?php 

	$question = $vars["entity"];
	$value = $vars["value"];
	$register = elgg_extract("register", $vars, false);
	
	if(!empty($question) && ($question instanceof EventRegistrationQuestion)) {
		if($question->canEdit() && !$register) {
			$edit_question = " <a href='javascript:void(0);' class='event_manager_questions_edit' rel='" . $question->getGUID() . "' title='" . elgg_echo("edit") . "'>" . elgg_view_icon("settings-alt") . "</a>";
			$delete_question = "<a href='javascript:void(0);' class='event_manager_questions_delete' rel='" . $question->getGUID() . "' title='" . elgg_echo("delete") . "'>" . elgg_view_icon("delete") . "</a>";
			
			$tools .= $edit_question . " " . $delete_question;
		}
		
		$fieldtypes = event_manager_get_registration_fiedtypes();
		if(array_key_exists($question->fieldtype, $fieldtypes)) {			
			$field_options = $question->getOptions();
			
			if($question->required) {
				$required = ' *';
			}
			
			if($question->fieldtype == 'Checkbox') {
				$field_options = array($question->title.$required => '1');
				
				$result = $tools.elgg_view('input/'.$fieldtypes[$question->fieldtype], array('name' => 'question_'.$question->getGUID(), 'value' => $value, 'options' => $field_options));
			} else {
				
				if(!$register){
					$result = elgg_view_icon("cursor-drag-arrow") . " ";
				}
				$result .= '<label>'.$question->title.$required.'</label>'.$tools.'<br />'.elgg_view('input/'.$fieldtypes[$question->fieldtype], array('name' => 'question_'.$question->getGUID(), 'value' => $value, 'options' => $field_options));
			}
		}
		if(!$register){
			$class = " class='elgg-module-popup'";
		}
		
		echo '<li' . $class . ' id="question_'.$question->getGUID().'">'.$result.'</li>';
	}