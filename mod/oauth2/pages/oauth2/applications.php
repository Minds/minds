<?php

/**
 * Elgg OAuth2 registered applications
 *
 * @author Billy Gunn (billy@arckinteractive.com)
 * @copyright Minds.com 2013
 * @link http://minds.com
 */

$options = array(
    'type'       => 'object',
    'subtype'    => 'oauth2_client',
    'limit'      => 10,    
    'full_view'  => false,
);
    
if (elgg_is_admin_logged_in() && $page[1] == 'all') {
    $title = elgg_echo('oauth2:applications:admin_title');
} else {
    $title = elgg_echo('oauth2:applications:title');
    $options['owner_guid'] = elgg_get_logged_in_user_guid();
}
    
elgg_register_title_button('oauth2', 'add');

$content = elgg_list_entities($options);

$params = array(
    'title'   => $title, 
    'content' => $content,
    'filter'  => ''
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
