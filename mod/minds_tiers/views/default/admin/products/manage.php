<?php

if (!get_input('guid')) {
    echo elgg_list_entities(array(
       'types'  => array('object'),
        'subtypes' => array('minds_tier'),
        'full_view' => false,
        'limit' => 0
    ));
}

    echo elgg_view_form('minds/products/new');