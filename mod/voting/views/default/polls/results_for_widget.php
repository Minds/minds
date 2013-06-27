<?php
/**
 * Elgg Poll plugin
 * @package Elggpoll
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @Original author John Mellberg
 * website http://www.syslogicinc.com
 * @Modified By Team Webgalli to work with ElggV1.5
 * www.webgalli.com or www.m4medicine.com
 */


if (isset($vars['entity'])) {

	//set img src
	$img_src = $vars['url'] . "mod/polls/graphics/poll.gif";

	$question = $vars['entity']->question;

	//get the array of possible responses
	$responses = polls_get_choice_array($vars['entity']);

	//get the array of user responses to the poll
	$user_responses = $vars['entity']->getAnnotations('vote',9999,0,'desc');

	//get the count of responses
	$user_responses_count = $vars['entity']->countAnnotations('vote');

	//create new array to store response and count
	//$response_count = array();

	$user_guid = elgg_get_logged_in_user_guid();
	$can_vote = polls_check_for_previous_vote($vars['entity'], elgg_get_logged_in_user_guid());

	//populate array
	foreach($responses as $response)
	{
		//get count per response
		$response_count = polls_get_response_count($response, $user_responses);
	
		//find the users vote
		foreach($user_responses as $user_response){
			if($user_response->owner_guid == elgg_get_logged_in_user_guid()){
				$selected = true;
			} 
		}

		//calculate %
		if ($response_count && $user_responses_count) {
			$response_percentage = round(100 / ($user_responses_count / $response_count));
		} else {
			$response_percentage = 0;
		}
					
		if(!$can_vote){
			$response_count = '...';
			$response_percentage = 0;
		}
		//html
		?>
<a href="<?php echo elgg_add_action_tokens_to_url('action/vote/vote?guid='.$vars['entity']->guid. '&response='.urlencode($response)); ?>">
<div class="progress_indicator" class="<?php if($selected){ echo 'selected'; } ?>" title="Click to place vote">
	<div class="progressBarContainer" align="left">
		<div class="polls-filled-bar"
			style="width: <?php echo $response_percentage; ?>%"></div>
		<label><?php echo $response . " (" . $response_count . ")"; ?> </label>
	</div>
</div>
</a>
		<?php
	}
	?>

<?php

}
else
{
	register_error(elgg_echo("polls:blank"));
	forward("mod/polls/all");
}
