<?php

$app_id = elgg_get_plugin_setting('yahoo_app_id');
$consumer_key = elgg_get_plugin_setting('yahoo_consumer_key');
$consumer_secret = elgg_get_plugin_setting('yahoo_consumer_secret');
$redirect_uri = elgg_get_site_url() . 'invite/handler/yahoo';
$max_results = 10000;

$oauthapp = new YahooOAuthApplication($consumer_key, $consumer_secret, $app_id, $redirect_uri);

if(get_input('openid_mode')){
		
	// extract approved request token from open id response
    $request_token = new YahooOAuthRequestToken($_REQUEST['openid_oauth_request_token'], '');
    $_SESSION['yahoo_oauth_request_token'] = $request_token->to_string();

    // exchange request token for access token
    $oauthapp->token = $oauthapp->getAccessToken($request_token);

    // store access token for later
    $_SESSION['yahoo_oauth_access_token'] = $oauthapp->token->to_string();
}


if(isset($_SESSION['yahoo_oauth_access_token'])){
	$oauthapp->token = YahooOAuthAccessToken::from_string($_SESSION['yahoo_oauth_access_token']);
	
	$contacts = $oauthapp->getContacts()->contact;
	
	foreach($contacts as $contact){
	
		$fields = $contact->fields;
		foreach($fields as $field){
			if($field->type == 'name'){
				$name = $field->value->givenName . ' ' . $field->value->familyName;
			}
			if($field->type == 'name'){
				$name = $field->value->givenName . ' ' . $field->value->familyName;
			}
			if($field->type == 'email'){
				$email = $field->value;
			}
		}
		$imported_contacts[$name] = $email;
	}
	unset($_SESSION['yahoo_oauth_access_token']);
} else {
	forward($oauthapp->getOpenIDUrl($oauthapp->callback_url));
}

$form = elgg_view_form('minds_inviter/invite', '', array('contacts'=>$imported_contacts));
echo elgg_view_page('', $form);