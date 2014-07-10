<p>
    <label>Bitcoin address: <br />
	<?= elgg_view('input/text', array('placeholder' => 'You haven\'t yet got a bitcoin wallet yet...', 'disabled' => 'true', 'id' => 'bitcoin_wallet', 'name' => 'params[bitcoin_address]', 'value' => elgg_get_plugin_user_setting('bitcoin_address', elgg_get_logged_in_user_guid(), 'bitcoin'))); ?>
    </label>
    
    <?php if ($wallet_guid = elgg_get_plugin_user_setting('bitcoin_wallet', elgg_get_logged_in_user_guid(), 'bitcoin')) { ?>
	<p>Your wallet's ID (should you wish to export it) is <strong><?php echo $wallet_guid; ?></strong></p>
<?php } ?>
</p>

<a href="#import" rel="toggle">Import existing wallet...</a>
<div id="import" style="display: none;">
    <?= elgg_view('input/import_wallet'); ?>
</div>


<input id="bitcoin_generate_wallet" type="button" value="Generate new wallet and bitcoin address..." />
<script>
    $(document).ready(function() {
	$('#bitcoin_generate_wallet').click(function() {

	    elgg.post("<?= elgg_add_action_tokens_to_url(elgg_get_site_url() . 'action/bitcoin/generatewallet'); ?>", { 
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
