<?php
gatekeeper();

if ($user = get_user_by_username(get_input('username'))) {
    
    $title = "Send a tip to " . $user->name;
    
    $body = elgg_view_layout("content", array(
	'title' => $title,
	'content' => elgg_view('tipjar/pages/tip', array('user' => $user)),
    ));

    echo elgg_view_page($title, $body);
} else
    forward();
    
    