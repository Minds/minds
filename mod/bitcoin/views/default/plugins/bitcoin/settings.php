<p>
    <label>Blockchain API key: <br />
	<?= elgg_view('input/text',array('name'=>'params[api_code]','value'=> elgg_get_plugin_setting('api_code', 'bitcoin'))); ?>
    </label>
</p>


<p>
    <label>Minds central bitcoin account: <br />
	<?= elgg_view('input/text',array('name'=>'params[central_bitcoin_account]','value'=> elgg_get_plugin_setting('central_bitcoin_account', 'bitcoin'))); ?>
    </label>
    This is the central bitcoin account, used as a bin for bitcoins that are sent or earned by users who don't have a bitcoin account.
</p>
