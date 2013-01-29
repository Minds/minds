<?php

/**
 * Minds OAuth2 client implementation
 *
 * @author Billy Gunn (billy@arckinteractive.com)
 * @copyright Minds.com 2013
 * @link http://minds.com
 */

elgg_register_event_handler('init','system','minds_connect_init');

function minds_connect_init() {

    // Our callback page handler
    elgg_register_page_handler('minds_connect', 'minds_connect_page_handler');

}

function minds_connect_page_handler($page) {

    $base = elgg_get_plugins_path() . 'minds_connect';

    $pages = $base . '/pages/minds_connect';

    switch ($page[0]) {

        case 'authorized':
            require $pages . "/authorized.php";        
            break;
    }

    return true;
}
