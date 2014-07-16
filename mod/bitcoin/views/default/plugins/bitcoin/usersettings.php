<p>
    <label>Bitcoin address: <br />
	<?= elgg_view('input/text', array('placeholder' => 'You haven\'t yet got a bitcoin wallet yet...', 'disabled' => 'true', 'id' => 'bitcoin_wallet', 'name' => 'params[bitcoin_address]', 'value' => elgg_get_plugin_user_setting('bitcoin_address', elgg_get_logged_in_user_guid(), 'bitcoin'))); ?>
    </label>
    
    <?php if ($wallet_guid = elgg_get_plugin_user_setting('bitcoin_wallet', elgg_get_logged_in_user_guid(), 'bitcoin')) { ?>
	<p>Your wallet's ID (should you wish to export it) is <strong><?php echo $wallet_guid; ?></strong></p>
<?php } ?>
</p>

<p><a href="#import" rel="toggle">Import existing wallet...</a>
<div id="import" style="display: none;">
    <?= elgg_view('input/import_wallet'); ?>
</div>
</p>

<?php
// Prevent accidental regeneration and loss of bitcoin
//if (!elgg_get_plugin_user_setting('bitcoin_address', elgg_get_logged_in_user_guid(), 'bitcoin')) { 
    ?>

<div class="generatewallet" style="padding:10px; border: 1px dotted #ccc;">
    <p><label>Enter a password and click the button to generate a new wallet</label>
	<input type="password" id="bitcoin_generate_password" /></p>
    <input id="bitcoin_generate_wallet" type="button" value="Generate new wallet and bitcoin address..." />
</div>
<script>
    $(document).ready(function() {
	$('#bitcoin_generate_wallet').click(function() {

	    elgg.post("<?= elgg_add_action_tokens_to_url(elgg_get_site_url() . 'action/bitcoin/generatewallet'); ?>", { 
		contentType : 'application/json',
		data: {
		    password: $('#bitcoin_generate_password').val()
		},
		success : function(data) {
		    if (data['status']==0)
		    {
			$('#bitcoin_wallet').val(data['output']);
			document.location.reload(true);
		    }
		}
            });
	});
    });
</script>
<br />
