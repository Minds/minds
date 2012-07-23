<?php

global $CONFIG;

$desc = '<div>' . elgg_echo('oauth:authorize:request') . '</div>';
// todo: better HTML, maybe a sub-view?
$desc .= '<blockquote><h3>' . $vars['consumer']->name . '</h3>';
$desc .= $vars['consumer']->desc . '</blockquote>';

$tok = elgg_view('input/hidden', array('name' => 'oauth_token',
					       'value' => $vars['token']->requestToken));

if (!($vars['consumer']->revA)) {
	// pass along the callback if we're not doing Rev A
	$cb = elgg_view('input/hidden', array('name' => 'oauth_callback',
					      'value' => $vars['callback']));
}
  
$submit = elgg_view('input/submit', array('value' => elgg_echo('oauth:authorize:authorize')));  

$formbody .= $desc;
$formbody .= $tok;
$formbody .= $cb;
$formbody .= $submit;

echo elgg_view('input/form', array('action' => $CONFIG->wwwroot . 'action/oauth/authorize', 
				   'body' => $formbody));
