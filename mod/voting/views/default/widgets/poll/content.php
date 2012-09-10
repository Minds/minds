<?php
/**
 * Elgg Polls plugin
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 */

elgg_load_library('elgg:polls');

//get the num of polls the user want to display
$limit = $vars['entity']->limit;

//if no number has been set, default to 3
if(!$limit) $limit = 3;

//the page owner
$owner_guid = $vars['entity']->owner_guid;
$owner = elgg_get_page_owner_entity();
echo '<h3 class="poll-widget-title">'. sprintf(elgg_echo('polls:widget:think'),$owner->name) . "</h3>";

$options = array(
		'type' => 'object',
		'subtype' => 'poll',
		'owner_guid' => $owner_guid,
		'limit' => $limit
);
$polls = elgg_get_entities($options);
if ($polls){
	foreach($polls as $pollpost) {
		echo elgg_view("polls/widget", array('entity' => $pollpost));
	}
}
else
{
	echo "<p>" . sprintf(elgg_echo('polls:widget:no_polls'),$owner->name) . "</p>";
}
