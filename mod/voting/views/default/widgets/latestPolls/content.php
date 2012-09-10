<?php
	/**
	 * Elgg Poll post widget view
	 *  
	 * @package Elggpoll
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author John Mellberg
	 * @copyright John Mellberg 2009
	 * 
	 * @uses $vars['entity'] Optionally, the poll post to view
	 */

	elgg_load_library('elgg:polls');
	
	//get the num of polls the user want to display
	$limit = $vars['entity']->limit;
		
	//if no number has been set, default to 5
	if(!$limit) $limit = 5;
	
	//the page owner
	$owner_guid = $vars['entity']->owner_guid;
	$owner = elgg_get_page_owner_entity();
	$options = array(
		'type' => 'object',
		'subtype'=>'poll',
		'limit' => $limit,
		'wheres' => array("e.owner_guid != $owner_guid"),
	);
	
	$polls = elgg_get_entities($options);
	
	if ($polls){	
		foreach($polls as $poll) {
			echo elgg_view("polls/widget", array('entity' => $poll));
		}	
	} else {
		echo "<p>" . elgg_echo("polls:widget:nonefound") . "</p>";	
	}
