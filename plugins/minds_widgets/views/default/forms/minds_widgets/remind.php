<?php

    echo elgg_view('input/minds_widget_form', array('tab' => $vars['tab'], 'form-body' => 
        "<label>Optional URL to reMind:</label><br />" .
            elgg_view('input/url', array('name' => 'url')) .
        "<label>Optional title:</label><br />" .
            elgg_view('input/text', array('name' => 'title')) .
        "<label>Optional description:</label><br />" .
            elgg_view('input/longtext', array('name' => 'description'))
    ));
    ?>