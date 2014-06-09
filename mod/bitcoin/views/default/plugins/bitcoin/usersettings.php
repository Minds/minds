<p>
    <label>Bitcoin wallet: <br />
	<?= elgg_view('input/text', array('id' => 'bitcoin_wallet', 'name' => 'params[bitcoin_wallet]', 'value' => elgg_get_plugin_setting('bitcoin_wallet', 'bitcoin'))); ?>
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