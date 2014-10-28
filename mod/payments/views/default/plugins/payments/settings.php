<?php


echo "<p>Paypal API Key</p>";
echo elgg_view('input/text',array('value' => $categories, 'name' => 'params[paypalKEY]'));

echo "<p>Paypal API Secret</p>";
echo elgg_view('input/text',array('value' => $categories, 'name' => 'params[paypalSecret]'));
