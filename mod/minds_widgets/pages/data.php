<?php
    $tab = get_input('tab', 'remind');

    echo elgg_view('minds_widgets/data/data' , array('tab' => $tab, 'user' => elgg_get_logged_in_user_entity()));
    