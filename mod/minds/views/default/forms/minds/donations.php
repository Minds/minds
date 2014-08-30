<?php
/**
 * 
 */
 ?>
 
<div>
	<h3>PayPal</h3>
	<input type="text" name="paypal" placeholder="Enter your paypal email address here" value="<?php echo elgg_get_plugin_setting('paypal','minds');?>"/>
</div>

<div>
	<h3>Bitcoin</h3>
	<input type="text" name="bitcoin" placeholder="Enter your bitcoin address here" value="<?php echo elgg_get_plugin_setting('bitcoin','minds');?>"/>
</div>

<input type="submit" class="elgg-button elgg-button-action" value="Save"/>
