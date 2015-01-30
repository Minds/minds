<?php

             $pages = elgg_get_entities(array('type'=>'object', 'subtype'=>'anypage', 'limit'=>0));
        foreach ($pages as $page) {
                elgg_register_menu_item('page', array(
                        'name' => $page->title,
                        'href' => $page->getURL(),
                        'text' => $page->title,
                        'priority' => 150
	));
}
