<?php

    $user = $vars['user'];
    $wallet = $vars['wallet'];
    $loggedin = elgg_get_logged_in_user_entity();
    
?><div class='wallet'>
    
    <?php
	if ($wallet) {
	    
	    if ($user->guid == $loggedin->guid) {
		
		try {

		    // Always display a generic bitcoin address (so we can get notifications of payments)
		  $wallet_address = minds\plugin\bitcoin\bitcoin()->createReceiveAddressForUser($user);
		} catch(Exception $e) {

		}
		
		if (!$wallet_address) $wallet_address = $wallet->wallet_address;
	    ?>
    
    <div class="header">
	<img class="qr-code" src="http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=<?php echo $wallet->wallet_address;?>&chld=H|0"/>
	<p>
	    <label>Wallet ID: </label> <a href="<?php echo $wallet->wallet_link; ?>" target="_blank"><?php echo $wallet->wallet_guid; ?></a>
	</p>
	<p>
	    <label>Wallet bitcoin address: </label> <?php echo $wallet_address; ?>
	</p>
	<p class="balance">
	    <label>Balance: </label> <?php
	    try {
		echo sprintf("%f", \minds\plugin\bitcoin\bitcoin()->getWalletBalance($wallet->guid));
		echo " BTC";
	    } catch (\Exception $e) {
		//echo $e->getMessage();
		echo elgg_view('input/unlockwallet', array('wallet'=>$wallet));
	    }
	    ?>
	</p>
	
	<p>
	    <a class="sendpayment button" href="<?php echo elgg_get_site_url(); ?>bitcoin/send">Send a payment...</a> 
	</p>
    </div>
    
    <div class="report">
	<h3>Transactions</h3>
	
	<?php
	    echo elgg_list_entities(array(
		'type' => 'object',
		'subtype' => 'bitcoin_transaction',
		'owner_guid' => $user->guid,
		'full_view' => false
	    ));
	?>
    </div>
    
    <?php
	    } else {
		?>
	    <div class="header">
		
		<p>
		    <a class="sendpayment button" href="<?php echo elgg_get_site_url(); ?>bitcoin/send?address=<?php echo minds\plugin\bitcoin\bitcoin()->getReceiveAddressForUser($loggedin); ?>">Send Bitcoin to <?php echo $user->name; ?>...</a> 
		</p>
	    </div>
    <?php
	    }
	} else {
	  ?>
    
    <p>No wallet registered, <a href="<?php echo elgg_get_site_url(); ?>settings/plugins/<?php echo $user->username; ?>">why not create one?</a></p>
    
    <?php
	}
	?>
    
</div>