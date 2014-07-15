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
    } 
    else
    {
?>
<p><label>Enter the amount of Bitcoin you wish to send from your wallet<br />
	<input type="text" name="amount" value="<?php echo $amount_btc; ?>" placeholder="Amount in BTC" /></label></p>
	
<p><label>Address<br />
	<input type="text" name="address" value="<?php echo $address; ?>" placeholder="Bitcoin address" /></label></p>
<?php
    }
    ?>

<p><label>Wallet password<br />
	<input type="password" name="wallet_password" placeholder="Enter your wallet password to unlock your wallet" /></label></p>

<input type="submit" name="Send" value="Send Payment..." />

	    
