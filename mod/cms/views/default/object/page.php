<?php

$wallet = $vars['entity'];

$balance = $wallet->balance();
$address = $wallet->getReceivingAddress('bitcoin/receive/'.$wallet_guid);
?>
<div class="wallet">
	<img class="qr-code" src="https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=<?php echo $address;?>&chld=H|0"/>
	
	
	<h2 class="balance">
		<?php echo minds\plugin\bitcoin\start::toBTC($balance); ?> BTC
	</h2>
	
	<h3 class="address">
		<?php echo $address;?>
	</h4>
	
	<a href="<?php echo elgg_get_site_url();?>bitcoin/send" class="elgg-button elgg-button-action">Send</a>

</div>
