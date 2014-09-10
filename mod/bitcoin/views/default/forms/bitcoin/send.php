<?php

if ($vars['order']) {
	
	// This is in response to an order, so we fill out the details for it.
	
	$order = $vars['order'];
	
	$amount_btc = minds\plugin\bitcoin\bitcoin()->toSatoshi($order->amount_in_satoshi);
	$address = $order->minds_receive_address;
	
?>

<div class="order"><?php echo elgg_view_entity($order); ?></div>

<input type="hidden" name="order_guid" value="<?php echo $order->guid;?>" />

<?php
    } else {
	
	$amount_btc = get_input('amount');
	$address = get_input('address');
	
?>
<p><label>Enter the amount of Bitcoin you wish to send from your wallet</label></p>
	<input type="text" name="amount" value="<?php echo $amount_btc; ?>" placeholder="Amount in BTC" />
	
<p><label>Address</label></p>
	<input type="text" name="address" value="<?php echo $address; ?>" placeholder="Bitcoin address" />
<?php
  }
?>

<p><label>Wallet password</label></p>
	<input type="password" name="password" placeholder="Enter your wallet password to unlock your wallet" />

<input type="submit" name="Send" value="Send Payment..." class="elgg-button elgg-button-action"/>

	    
