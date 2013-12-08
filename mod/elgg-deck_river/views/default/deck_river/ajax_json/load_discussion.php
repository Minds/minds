<?php

$thread_id = get_input('discussion', 'false');
$network = get_input('network', 'elgg');

if ($network == 'elgg') {

	if (!$thread_id || !get_metastring_id($thread_id)) {
		echo elgg_echo('deck_river:thread-not-exist');
	} else {
		elgg_load_library('deck_river:river_loader');
		echo load_wire_discussion($thread_id);
	}

} else if ($network = 'twitter') {

	$twitter_consumer_key = elgg_get_plugin_setting('twitter_consumer_key', 'elgg-deck_river');
	$twitter_consumer_secret = elgg_get_plugin_setting('twitter_consumer_secret', 'elgg-deck_river');

	elgg_load_library('deck_river:authorize');
	$accounts = deck_river_get_networks_account('twitter_account');
	$account = $accounts[0]; // @todo why the first ? Check limit rate and take the most free ?

	elgg_load_library('deck_river:twitter_async');
	$twitterObj = new EpiTwitter($twitter_consumer_key, $twitter_consumer_secret, $account->oauth_token, $account->oauth_token_secret);

	$results = array();
	for($x=0;$x<=50;$x++) { // Maximum of 50 tweets ?
		try {
			$result = $twitterObj->get('/statuses/show/' . $thread_id . '.json', array(
				'inclued_entities' => '1'
			));
		} catch(Exception $e) {
			$result = json_decode($e->getMessage())->errors[0];
		}

		// check result
		if ($result->code == 200) {
			$results[] = $result->__get('response');
		} else {
			break;
		}

		$end = end($results);
		if ($end['in_reply_to_status_id_str'] !== null) {
			$thread_id = $end['in_reply_to_status_id_str'];
		} else {
			break;
		}
	}

	// check result
	if ($result->code == 200) {
		$jsonexport['column_type'] = 'discussion'; // only to say it's not direct link.
		$jsonexport['results'] = array_reverse($results);
	} else {
		$jsonexport['column_error'] = elgg_echo('deck_river:twitter:error:discussion');
		$jsonexport['results'] = '';
	}
	echo json_encode($jsonexport);
}
