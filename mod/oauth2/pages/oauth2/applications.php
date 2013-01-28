<?php

/**
 * Elgg OAuth2 registered applications
 *
 * @author Billy Gunn (billy@arckinteractive.com)
 * @copyright Minds.com 2013
 * @link http://minds.com
 */

$title = elgg_echo('oauth2:applications:title');

$options = array(
    'type'       => 'object',
    'subtype'    => 'oauth2_client',
    'owner_guid' => elgg_get_logged_in_user_guid(),
    'limit'      => 10,    
);
    
$content = elgg_list_entities($options);

$params = array(
    'title'   => $title, 
    'content' => $content,
    'filter'  => ''
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
