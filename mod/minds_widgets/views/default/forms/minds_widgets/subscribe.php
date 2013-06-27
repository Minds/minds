<?php

    echo elgg_view('input/minds_widget_form', array('tab' => $vars['tab'], 'form-body' => 
        '<label>Subscribe to @'.  elgg_get_logged_in_user_entity()->username.'</label>'
    ));