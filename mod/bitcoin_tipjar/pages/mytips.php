<?php
gatekeeper();

if ($user = get_user_by_username(get_input('username'))) {
    
    $title = $user->name . "'s tipjar";
    
    $body = elgg_view_layout("content", array(
	'title' => $title,
	'content' => elgg_view('tipjar/pages/mytips', array('user' => $user)),
    ));

    echo elgg_view_page($title, $body);
} else
    forward();
    
    