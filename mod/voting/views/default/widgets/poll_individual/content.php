<?php
/**
 * Individual poll view
 *
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 *
 */

elgg_load_library('elgg:polls');

$options = array(
	'type' => 'object',
	'subtype'=>'poll',
	'owner_guid' => elgg_get_page_owner_guid(),
	'limit' => 1,
);

$polls = elgg_get_entities($options);

if(!empty($polls)){
  $body = elgg_view('polls/poll_widget',array('entity'=>$polls[0]));
}
else{
  $body = sprintf(elgg_echo('polls:widget:no_polls'),elgg_get_page_owner_entity()->name);
}

echo $body;
