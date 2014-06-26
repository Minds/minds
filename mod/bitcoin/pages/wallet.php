<?php

gatekeeper();

if ($user = get_user_by_username(get_input('username'))) {
    
    $title = $user->name . "'s wallet";
    
    $body = elgg_view_layout("content", array(
	'title' => $title,
	'content' => elgg_view('bitcoin/pages/wallet', array('user' => $user, 'wallet' => \minds\plugin\bitcoin\bitcoin()->getWallet($user))),
    ));

    echo elgg_view_page($title, $body);
} else
    forward();
    
    