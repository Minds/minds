<p>
    <label>Bitcoin wallet: <br />
	<?= elgg_view('input/text', array('id' => 'bitcoin_wallet', 'name' => 'params[bitcoin_address]', 'value' => elgg_get_plugin_user_setting('bitcoin_address', elgg_get_logged_in_user_guid(), 'bitcoin'))); ?>
    </label>
</p>

<p>Or, if you don't have one, you can generate one...</p>


<input id="bitcoin_generate_wallet" type="button" value="Generate new wallet..." />
<script>
    $(document).ready(function() {
	$('#bitcoin_generate_wallet').click(function() {

	    elgg.action("<?= elgg_get_site_url(); ?>action/bitcoin/generatewallet", { 
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