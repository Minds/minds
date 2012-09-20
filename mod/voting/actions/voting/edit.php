<?php
/*
 *
 * Elgg polls: add action
 *
 */

elgg_load_library('elgg:polls');
// start a new sticky form session in case of failure
elgg_make_sticky_form('polls');

// Get input data
$question = get_input('question');
$number_of_choices = (int) get_input('number_of_choices',0);
$front_page = get_input('front_page');
$tags = get_input('tags');
$access_id = get_input('access_id');
$container_guid = get_input('container_guid');
$guid = get_input('guid');

// Convert string of tags into a preformatted array
$tagarray = string_to_tag_array($tags);

//get response choices
$count = 0;
$new_choices = array();
if ($number_of_choices) {
	for($i=0;$i<$number_of_choices;$i++) {
		$text = get_input('choice_text_'.$i,'');
		if ($text) {
			$new_choices[] = $text;
			$count ++;
		}
	}
}

$user = elgg_get_logged_in_user_entity();

if ($guid) {
	// editing an existing poll
	$poll = get_entity($guid);
	if (elgg_instanceof($poll,'object','poll') && $poll->canEdit()) {
		$container_guid = $poll->container_guid;
		// Make sure the question / responses aren't blank
		if (empty($question) || ($count == 0)) {
			register_error(elgg_echo("polls:blank"));
			forward("polls/edit/".$guid);
			exit;
			// Otherwise, save the poll
		} else {
			$poll->access_id = $access_id;
		
			$poll->question = $question;
			$poll->title = $question;
				
			if (!$poll->save()) {
				register_error(elgg_echo("polls:error"));
				if ($container_guid) {
					forward("polls/add/".$container_guid);
				} else {
					forward("polls/add");
				}
				exit;
			}
			
			elgg_clear_sticky_form('polls');
			
			polls_delete_choices($poll);
			polls_add_choices($poll,$new_choices);
			polls_manage_front_page($poll,$front_page);
		
			if (is_array($tagarray)) {
				$poll->tags = $tagarray;
			}
			
			// Success message
			system_message(elgg_echo("polls:edited"));
		}
	}
} else {
	if (!$container_guid) {
		$polls_site_access = elgg_get_plugin_setting('site_access', 'polls');
		$allowed = (elgg_is_logged_in() && ($polls_site_access != 'admins')) || elgg_is_admin_logged_in();		
		if (!$allowed) {
			register_error(elgg_echo('polls:can_not_create'));
			elgg_clear_sticky_form('polls');
			forward('polls/all');
			exit;
		}
	}
	// Make sure the question / responses aren't blank
	if (empty($question) || ($count == 0)) {
		register_error(elgg_echo("polls:blank"));
		if ($container_guid) {
			forward("polls/add/".$container_guid);
		} else {
			forward("polls/add");
		}		
	} else {
		// Otherwise, save the poll
	
		// Initialise a new ElggObject
		$poll = new ElggObject();
	
		// Tell the system it's a poll
		$poll->subtype = "poll";
	
		// Set its owner to the current user
		$poll->owner_guid = $user->guid;
		$poll->container_guid = $container_guid;
	
		$poll->access_id = $access_id;
	
		$poll->question = $question;
		$poll->title = $question;

		if (!$poll->save()) {
			register_error(elgg_echo("polls:error"));
			if ($container_guid) {
				forward("polls/add/".$container_guid);
			} else {
				forward("polls/add");
			}
			exit;
		}
		
		elgg_clear_sticky_form('polls');

		polls_add_choices($poll,$new_choices);
		polls_manage_front_page($poll,$front_page);
	
		if (is_array($tagarray)) {
			$poll->tags = $tagarray;
		}
		
		$polls_create_in_river = elgg_get_plugin_setting('create_in_river','polls');
		if ($polls_create_in_river != 'no') {	
			add_to_river('river/object/poll/create','create',elgg_get_logged_in_user_guid(),$poll->guid);
		}
	
		// Success message
		system_message(elgg_echo("polls:added"));
	}
}

// Forward to the poll page
forward($poll->getURL());
exit;
