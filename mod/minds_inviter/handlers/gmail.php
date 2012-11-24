<?php

$client_id = elgg_get_plugin_setting('gmail_client_id');
$client_secret = elgg_get_plugin_setting('gmail_client_secret');
$redirect_uri = elgg_get_site_url() . 'invite/handler/gmail';
$max_results = 10000;

$auth_code = $_GET["code"];

if(!$auth_code){
 	forward("https://accounts.google.com/o/oauth2/auth?client_id=$client_id&redirect_uri=$redirect_uri&scope=https://www.google.com/m8/feeds/&response_type=code");
}

function curl_file_get_contents($url) {
	$curl = curl_init();
	$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

	curl_setopt($curl, CURLOPT_URL, $url);
	//The URL to fetch. This can also be set when initializing a session with curl_init().
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
	//The number of seconds to wait while trying to connect.

	curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
	//The contents of the "User-Agent: " header to be used in a HTTP request.
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	//To follow any "Location: " header that the server sends as part of the HTTP header.
	curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
	//To automatically set the Referer: field in requests where it follows a Location: redirect.
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	//The maximum number of seconds to allow cURL functions to execute.
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	//To stop cURL from verifying the peer's certificate.
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);

	$contents = curl_exec($curl);
	curl_close($curl);
	return $contents;
}

$fields = array('code' => urlencode($auth_code), 'client_id' => urlencode($client_id), 'client_secret' => urlencode($client_secret), 'redirect_uri' => urlencode($redirect_uri), 'grant_type' => urlencode('authorization_code'));
$post = '';
foreach ($fields as $key => $value) {
	 $post .= $key . '=' . $value . '&';
}

$post = rtrim($post, '&');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
curl_setopt($curl, CURLOPT_POST, 5);
curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
$result = curl_exec($curl);
curl_close($curl);

$response = json_decode($result);
$accesstoken = $response -> access_token;

$url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=' . $max_results . '&alt=json&v=3.0&oauth_token=' . $accesstoken;
$response = curl_file_get_contents($url);

$temp = json_decode($response,true);

foreach($temp['feed']['entry'] as $contact){
	$name  = $contact['title']['$t'];
	$contacts[$name] = $contact['gd$email']['0']['address'];
}
echo elgg_view_form('minds_inviter/invite', '', array('contacts'=>$contacts));
