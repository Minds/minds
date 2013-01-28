<?php

/**
 * Elgg OAuth2 client/application registration page
 *
 * https://tools.ietf.org/html/draft-ietf-oauth-v2-30#section-2
 *
 * @author Billy Gunn (billy@arckinteractive.com)
 * @copyright Minds.com 2013
 * @link http://minds.com
 */

$title = elgg_echo('oauth2:register:title');

$content = elgg_view_form('oauth2/register', $vars, $body_vars);

$params = array(
    'title'   => $title, 
    'content' => $content,
    'filter'  => ''
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
