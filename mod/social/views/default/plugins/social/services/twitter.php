<?php

?>
<h3>Twitter</h3>
<b>Api key:</b>
<?=  elgg_view('input/text',array('value' => elgg_get_plugin_setting('twitter_api_key', 'social'), 'name' => 'params[twitter_api_key]')); ?>
<b>Api secret:</b>
<?=  elgg_view('input/text',array('value' => elgg_get_plugin_setting('twitter_api_secret', 'social'), 'name' => 'params[twitter_api_secret]')); ?>
