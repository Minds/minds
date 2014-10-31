<?php


echo "<p>Paypal API Key</p>";
echo elgg_view('input/text',array('value' => $categories, 'name' => 'params[paypalKey]', 'value'=>elgg_get_plugin_setting('paypalKey', 'payments')));

echo "<p>Paypal API Secret</p>";
echo elgg_view('input/text',array('value' => $categories, 'name' => 'params[paypalSecret]', 'value'=>elgg_get_plugin_setting('paypalSecret', 'payments')));
