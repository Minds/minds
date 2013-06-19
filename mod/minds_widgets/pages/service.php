<?php
    $tab = get_input('tab', 'remind');

    // Logged in, display login box and remember where to go
    if (!elgg_is_logged_in()) {
        global $SESSION;
        $SESSION['last_forward_from'] = current_page_url();
        $content = elgg_view_form('login');
    }
    else
    {
        $content = elgg_view('minds_widgets/service/' . $tab, array('tab' => $tab, 'user' => elgg_get_logged_in_user_entity()));
    }
    
    $params = array(
            'title' => elgg_echo('minds_widgets:tab:'.$tab),
            'content' => $content,
            'sidebar' => ''
    );
    $body = elgg_view_layout('default', $params);

    
    echo elgg_view_page(elgg_echo('minds_widgets:tab:'.$tab), $body);