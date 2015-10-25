<?php

    echo elgg_view('input/minds_widget_form', array('tab' => $vars['tab'], 'form-body' => 
        "<label>URL to have voting enabled on:</label><br />" .
            elgg_view('input/url', array('name' => 'url', 'required' => true))));