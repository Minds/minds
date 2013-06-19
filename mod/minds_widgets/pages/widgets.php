<?php
    gatekeeper();

    $tab = get_input('tab', 'remind');

    $params = array(
            'title' => elgg_echo('minds_widgets:tab:'.$tab),
            'content' => $content,
            'sidebar' => elgg_view('forms/minds_widgets/' . $tab, array('user' => elgg_get_logged_in_user_entity())),
    );
    $body = elgg_view_layout('one_sidebar', $params);

    echo elgg_view_page(elgg_echo('minds_widgets:tab:'.$tab), $body);