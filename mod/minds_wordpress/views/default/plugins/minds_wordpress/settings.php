<?php

global $CONFIG;

elgg_load_library('oauth2');

if ($clients = elgg_get_entities(
        array(
            'type' => 'object',
            'subtype' => 'oauth2_client',
            'owner_guid' => elgg_get_logged_in_user_guid(),
            'limit' => 9999
            //'metadata_name_value_pairs' => array('attached_plugin' => 'minds_wordpress')
        )
)) {
    // Quick and dirty
    foreach ($clients as $c)
        if ($c->attached_plugin == 'minds_wordpress')
            $client = $c;
}

// Create client if it doesn't exist
if (!$client) {
    $client = new ElggObject();
    $client->subtype    = 'oauth2_client';
    $client->owner_guid = elgg_get_logged_in_user_guid();
    $client->access_id  = ACCESS_PRIVATE;
    
    $client->title = "Minds Wordpress Connector";
    $client->description = "Connect your minds site to wordpress";
    
    $client->save();
    
    $client->attached_plugin = 'minds_wordpress';
    $client->client_id     = $client->guid;
    $client->client_secret = oauth2_generate_client_secret();
}

$key = $client->client_id;
$secret = $client->client_secret;

/*
$key = elgg_get_plugin_setting('api_key', 'minds_wordpress');
$secret = elgg_get_plugin_setting('api_secret', 'minds_wordpress');

if (!$api_user = get_api_user($CONFIG->site_id, $key))
        $key = $secret = "";


if (!$key || !$secret) {
    $api_key = create_api_user($CONFIG->site_id);
    
    elgg_set_plugin_setting('api_key', $api_key->api_key, 'minds_wordpress');
    elgg_set_plugin_setting('api_secret', $api_key->secret, 'minds_wordpress');

    
    $key = elgg_get_plugin_setting('api_key', 'minds_wordpress');
    $secret = elgg_get_plugin_setting('api_secret', 'minds_wordpress');
}*/

?>
<p>Download and install the minds wordpress plugin in your Wordpress installation, then enter the following API settings.</p>

<p>
    <label>Public Key: </label><?php echo $key; echo elgg_view('input/hidden', array('name'=>'params[client_id]', 'value'=>$key))?><br />
    <label>Secret Key: </label><?php echo $secret; ?>
</p>

<p>For single sign on, please enter your wordpress site</p>

<p>
	<?php echo elgg_view('input/text', array('name'=>'params[url]', 'value'=>$vars['entity']->url, 'placeholder'=> 'eg. http://wp.minds.com/')); ?>
</p>

