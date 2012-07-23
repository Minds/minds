<?php

  // Load Elgg engine
  //require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
// must be logged in to see this page
gatekeeper();


$user = get_loggedin_user();

$token = get_input('oauth_token');
$callback = get_input('oauth_callback');

$area2 = '';

$tokEnt = oauth_lookup_token_entity($token, 'request');

if ($tokEnt && !$tokEnt->getOwner()) {

	// new authorization

	$consumEnt = oauth_lookup_consumer_entity($tokEnt->consumerKey);
  
	$area2 .= elgg_view_title(elgg_echo('oauth:authorize:new'));

	$form = elgg_view('oauth/authform', array('consumer' => $consumEnt,
						  'token' => $tokEnt,
						  'callback' => $callback));
  
  
	$area2 .= $form;
  
} else {

	// show response messages if needed
	$authorized = get_input('authorized', false);
	$verifier = get_input('oauth_verifier', false);

	if ($authorized) {
		$tokEnt = oauth_lookup_token_entity($authorized);
		$consumEnt = oauth_lookup_consumer_entity($tokEnt->consumerKey);

		// note that the authorize action has already taken care of the callback URL for us

		$area2 .= elgg_view_title(elgg_echo('oauth:authorize:success'));

		$authTxt = elgg_view('oauth/continue', array('consumer' => $consumEnt,
							     'verifier' => $verifier));

		$area2 .= $authTxt;
			
	}

	// show existing tokens
	
// get the entities for the current user
	$toks = elgg_get_entities(array('types' => 'object', 'subtypes' => 'oauthtoken', 'owner_guids' => $user->getGUID())); 
//	$toks = get_entities('object', 'oauthtoken', $user->getGUID());
	
// split tokens into server and consumer tokens
	
	$cToks = array();
	$sToks = array();
	foreach ($toks as $tok) {
		$consumEnt = oauth_lookup_consumer_entity($tok->consumerKey);
		if ($consumEnt->consumer_type == 'outbound') {
			$sToks[] = $tok;
		} else {
			$cToks[] = $tok;
		}
	}

	$area2 .= elgg_view_title(elgg_echo('oauth:authorize:inbound'));
	
	if ($cToks) {
		$text = elgg_echo('oauth:authorize:inbound:desc');
		
		$area2 .= $text;
		
		$tokList = elgg_view_entity_list($cToks, array(
		    'count' => count($cToks),
		    'offset' => 0,
		    'limit' => 0,
		    'full_view' => true,
		    'list_type' => true,
		    'pagination' => false));
		
		$area2 .= $tokList;
	} else {
		$text = elgg_echo('oauth:authorize:inbound:none');

		$area2 .= $text;
	}

	$area2 .= elgg_view_title(elgg_echo('oauth:authorize:outbound'));

	if ($sToks) {
		$text = elgg_echo('oauth:authorize:outbound:desc');
		
		$area2 .= $text;
		
		$tokList = elgg_view_entity_list($sToks, array(
		    'count' => count($sToks),
		    'offset' => 0,
		    'limit' => 0,
		    'full_view' => true,
		    'list_type' => true,
		    'pagination' => false));

		
		$area2 .= $tokList;
	} else {
		$text = elgg_echo('oauth:authorize:outbound:none');

		$area2 .= $text;
	}

}
// format
$body = elgg_view_layout("two_column_left_sidebar", array('area2' => $area2));

// Draw page
echo elgg_view_page(elgg_echo('oauth:authorized'), $body);

?>