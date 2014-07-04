<div class="bitcoin-tip">
    <p>
	<span class="date"><?php echo date('r', $vars['object']->time_created); ?></span>
	
	<span class="action"><?php echo $vars['object']->action; ?></span>
	
	<span class="amount"><?php echo $vars['object']->amount_satoshi; ?> BTC</span>
	
	<?php
	    if ($vars['object']->action == 'sent') {
		echo elgg_echo('to');
		echo $vars['object']->to_address;
	    } else {
		echo elgg_echo('from');
		echo $vars['object']->from_address;
	    } ?>
	
	
    </p>
</div>