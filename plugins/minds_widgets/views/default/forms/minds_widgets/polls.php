<?php

    echo elgg_view('input/minds_widget_form', array('tab' => $vars['tab'], 'form-body' => 
        elgg_view('minds_widgets/templates/polls', array('poll_guid' => get_input('poll_guid'), 'tab' => $vars['tab'])) . '<br />' .
        elgg_view('input/hidden', array('name' => 'poll_guid', 'value' => get_input('poll_guid')))
    ));