<?php
/**
 * Group poll view
 *
 * @package Elggpoll_extended
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author John Mellberg <big_lizard_clyde@hotmail.com>
 * @copyright John Mellberg - 2009
 *
 */

elgg_load_library('elgg:polls');
elgg_load_js('elgg.polls');

$group = elgg_get_page_owner_entity();

if (polls_activated_for_group($group)) {
	elgg_push_context('widgets');
	$all_link = elgg_view('output/url', array(
		'href' => "polls/group/$group->guid/all",
		'text' => elgg_echo('link:view:all'),
		'is_trusted' => true,
	));
	$new_link = elgg_view('output/url', array(
		'href' => "polls/add/$group->guid",
		'text' => elgg_echo('polls:addpost'),
		'is_trusted' => true,
	));

	$limit = 4;
	//$objects = list_entities_from_metadata("content_owner",page_owner(), "object","poll",0, 5, false,false,false);
	$options = array('type'=>'object','subtype'=>'poll','container_guid'=>elgg_get_page_owner_guid());
	$content = '';
	if ($polls = elgg_get_entities($options)) {
		foreach ($polls as $poll) {
			$content .= '<div class="polls-group-widget-box">'.elgg_view('polls/poll_widget',array('entity'=>$poll)).'</div>';
		}
	}
	elgg_pop_context();
	if (!$content) {
	  $content = '<p>'.elgg_echo("group:polls:empty").'</p>';
	}
	
	echo elgg_view('groups/profile/module', array(
		'title' => elgg_echo('polls:group_polls'),
		'content' => $content,
		'all_link' => $all_link,
		'add_link' => $new_link,
	));
}
