<?php
    gatekeeper();

    $tab = get_input('tab', 'remind');

    $params = array(
            'title' => elgg_echo('minds_widgets:tab:'.$tab),
            'content' => elgg_view('forms/minds_widgets/' . $tab, array('tab' => $tab, 'user' => elgg_get_logged_in_user_entity())),
            'sidebar' => ''
    );
    $body = elgg_view_layout('one_sidebar', $params);

    echo elgg_view_page(elgg_echo('minds_widgets:tab:'.$tab), $body);