<?php 
$user = elgg_get_logged_in_user_entity();
?>
<div class="blurb">
	We reward you for the traffic you drive on the network!
</div>

<div class="orientation-table">
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			Paypal email address
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/text', array('name'=>'paypal_email', 'placeholder'=>'eg. money@minds.com')); ?>
		</div>
	</div>
</div>
