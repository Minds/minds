<div class="bitcoin-transaction <?php echo $vars['entity']->action; ?>">
    <p>
	
	
	<span class="action"><?php echo $vars['entity']->action; ?></span>
	
	<span class="amount"><?php echo sprintf("%f", minds\plugin\bitcoin\bitcoin()->toBTC($vars['entity']->amount_satoshi)); ?> BTC</span> <br />
	
	<span class="address">
	<?php
	    if ($vars['entity']->action == 'sent') {
		echo elgg_echo('bitcoin:to'); echo "&nbsp;";
		echo $vars['entity']->to_address;
	    } else {
		echo elgg_echo('bitcoin:from'); echo "&nbsp;";
		echo $vars['entity']->from_address;
	    } ?>
	</span>
	    
	<br /><span class="date"<?php echo elgg_view_friendly_time($vars['entity']->time_created); ?></span>
    </p>
</div>