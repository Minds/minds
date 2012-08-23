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
if (polls_activated_for_group($vars['entity'])) {
?>

<div id="group_pages_widget">
<h2><?php echo elgg_echo('polls:group_polls'); ?></h2>
<?php
$limit = 4;
set_context("search");
//$objects = list_entities_from_metadata("content_owner",page_owner(), "object","poll",0, 5, false,false,false);
if($polls = get_entities('object','poll',0,'time_created desc',$limit,0,false,0,page_owner())){	
	foreach($polls as $pollpost){
		echo elgg_view("polls/widget", array('entity' => $pollpost));
	}	
} else{
  echo elgg_view('page_elements/contentwrapper',array('body'=>elgg_echo("group:polls:empty")));
}

?></div>
<?php } ?>
