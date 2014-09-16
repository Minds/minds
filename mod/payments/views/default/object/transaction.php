<?php

$transaction = new minds\plugin\payments\entities\transaction($vars['entity']);
//$card_details = $card->getCard();
?>

<div class="table">
	<div class="cell">
		<?php echo elgg_view_friendly_time($transaction->time_created); ?>
	</div>
	
	<div class="cell">
		<?php echo $transaction->description; ?>
	</div>
	
	<div class="cell">
		$<?php echo $transaction->amount; ?> USD
	</div>
</div>
