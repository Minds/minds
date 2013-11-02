<?php

/**
 * oauth2 settings
 */

$options = array(
    'type' => 'object',
    'subtype' => 'oauth2_refresh_token',
    'owner_guid' => elgg_get_logged_in_user_guid(),
    'limit' => 99,
);

$entities = elgg_get_entities($options);

if (!empty($entities)) {

    $oauth2_disconnect = get_input('oauth2_disconnect');

    $applications = array();

    $access = elgg_get_ignore_access();
    elgg_set_ignore_access(true);

    foreach ($entities as $e) {

        $app = get_entity($e->container_guid,'object');

        if (elgg_instanceof($app, 'object', 'oauth2_client')) {

            if ($app->guid == $oauth2_disconnect) {
                $e->delete();
            } else {            
                $applications[] = $app;
            }
        }
    }

    elgg_set_ignore_access($access);

} else {
    return true;
}

    
?> 

<h3> Connected Applications </h3>

<div style="margin: 10px 0 10px 0;">

    <?php foreach ($applications as $app): ?>

        <div style="margin-bottom:5px;">
            <span style="font-size:1.1em;"><?php echo $app->title; ?></span> - 
            <small>
                <a href="<?php echo current_page_url(); ?>?oauth2_disconnect=<?php echo $app->guid; ?>" rel="Are you sure you want to disconnect this application?" class="elgg-requires-confirmation">disconnect</a>
            </small>
        <div>

    <?php endforeach; ?>

</div>

<br />
