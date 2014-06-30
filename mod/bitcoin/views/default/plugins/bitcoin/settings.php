<p>
    <label>Blockchain API key: <br />
	<?= elgg_view('input/text',array('name'=>'params[api_code]','value'=> elgg_get_plugin_setting('api_code', 'bitcoin'))); ?>
    </label>
</p>

<p>
    <label>Amount of bitcoin to give each new user (in satoshi): <br />
	<?= elgg_view('input/text',array('name'=>'params[satoshi_to_new_user]','value'=> elgg_get_plugin_setting('satoshi_to_new_user', 'bitcoin'))); ?>
    </label>
</p>


<p>
    <label>Minds central bitcoin address: <br />
	This is the central bitcoin account, used as a bin for bitcoins that are sent or earned by users who don't have a bitcoin account.
	<?= elgg_view('input/text',array('placeholder' => 'You haven\'t yet got a bitcoin wallet yet...', 'disabled' => 'true', 'id' => 'bitcoin_wallet', 'name'=>'params[central_bitcoin_account]','value'=> elgg_get_plugin_setting('central_bitcoin_account', 'bitcoin'))); ?>
	
	<?php 
	if ($wallet_guid = elgg_get_plugin_setting('central_bitcoin_wallet_guid',  'bitcoin')) {
	    $wallet = get_entity(elgg_get_plugin_setting('central_bitcoin_wallet_object_guid',  'bitcoin'));
	?>
	<p>Your wallet's ID (should you wish to export it) is <strong><a href="<?php echo $wallet->wallet_link; ?>" target="_blank"><?php echo $wallet_guid; ?></a></strong></p>
	<?php
	    try {
		?>
		<p>The current balance is <strong><?php echo \minds\plugin\bitcoin\bitcoin()->getWalletBalance($wallet->guid); ?></strong></p>
	    <?php
	    } catch (\Exception $e) {
		echo $e->getMessage();
	    } ?>
	<?php } ?>
    </label>
    
</p>

<a href="#import" rel="toggle">Import existing wallet...</a>
<div id="import" style="display: none;">
    <?= elgg_view('input/import_wallet'); ?>
</div>

<input id="bitcoin_generate_wallet" type="button" value="Generate new system wallet and bitcoin address..." />
<script>
    $(document).ready(function() {
	$('#bitcoin_generate_wallet').click(function() {

	    elgg.action("<?= elgg_get_site_url(); ?>action/bitcoin/generatesystemwallet", { 
		contentType : 'application/json',
		success : function(data) {
		    if (data['status']==0)
		    {
			$('#bitcoin_wallet').val(data['output']);
		    }
		}
            });
	});
    });
</script>
