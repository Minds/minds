<?php
    set_input('feed_guid', elgg_get_logged_in_user_guid());
    echo elgg_view('input/minds_widget_form', array('tab' => $vars['tab'], 'form-body' => 
        elgg_view('minds_widgets/templates/newsfeed', array('feed_guid' => get_input('feed_guid'), 'tab' => $vars['tab'])) . '<br />' .
        elgg_view('input/hidden', array('name' => 'feed_guid', 'value' => get_input('feed_guid')))
    ));