<?php

global $CONFIG;

gatekeeper();

$minds_user_id = get_input('minds_user_id');
$domains = get_input('domains');

$endpoint = $CONFIG->multisite_endpoint . 'webservices/edit_domains.php';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$endpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "minds_user_id=$minds_user_id&domains[]=" . implode('&domains[]=', $domains));

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = json_decode(curl_exec ($ch));
if ($server_output->message) register_error($server_output->message);

curl_close ($ch);
forward(REFERER);