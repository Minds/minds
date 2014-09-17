
<div class="paypal-payout">
    <p>Your Paypal Address. We will issue payment once you reach $25 in credit</p>
	    <input type="text" name="paypal_email" placeholder="eg. someone@email.com" value="<?= elgg_get_plugin_user_setting('paypal_address', elgg_get_logged_in_user_guid(), 'payments')?>"/>

	
	<input type="submit" value="Save" class="elgg-button elgg-button-action"/>
</div>
