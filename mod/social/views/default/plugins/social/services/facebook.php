<?php

?>
<h3>Facebook</h3>
<b>Api key:</b>
<?=  elgg_view('input/text',array('value' => elgg_get_plugin_setting('facebook_api_key', 'social'), 'name' => 'params[facebook_api_key]')); ?>
<b>Api secret:</b>
<?=  elgg_view('input/text',array('value' => elgg_get_plugin_setting('facebook_api_secret', 'social'), 'name' => 'params[facebook_api_secret]')); ?>
