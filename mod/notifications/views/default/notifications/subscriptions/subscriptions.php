<?php
/**
 * @uses $vars['user'] ElggUser
 */

/* @var ElggUser $user */
$user = $vars['user'];

?>
<div class="notification_subscriptions">
	<div class="current_email">
		<h3>Email Address</h3>
		<p>Your current email address: <b><?php echo $user->email;?></b></p>
	</div>
	<div class="recieve-update">
		<label>Recieve updates:</label>
		<?php echo elgg_view('input/dropdown', array(	'name'=>'subscription', 
														'options_values'=> array(	'daily'=> 'Daily',
																			'weekly'=> 'Weekly',
																			'never'=>'Never'
																			),
														'value'=>$user->notification_subscription ?: 'weekly'
		));?>
	</div>
</div>