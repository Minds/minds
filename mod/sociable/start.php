<?php

/*
 * Project Name:            Sociable Theme
 * Project Description:     Theme for Elgg 1.8
 * Author:                  Shane Barron - SocialApparatus
 * License:                 GNU General Public License (GPL) version 2
 * Website:                 http://socia.us
 * Contact:                 sales@socia.us
 * 
 * File Version:            1.0
 * Last Updated:            5/11/2013
 */

elgg_register_event_handler('init', 'system', 'sociable_init');

function sociable_init() {
    global $CONFIG;

    if (elgg_get_context() === "admin") {
        elgg_unregister_css("twitter-bootstrap");
        elgg_unregister_css("ui-lightness");
        elgg_unregister_css("sociable");
        elgg_unregister_css("bubblegum");
        elgg_unregister_css("righteous");
        elgg_unregister_css("ubuntu");
        elgg_unregister_js("sociable");
        elgg_unregister_js("jquery-migrate");
        elgg_unregister_js("twitter-bootstrap");
    } else {
        elgg_register_css("twitter-bootstrap", $CONFIG->url . "mod/sociable/vendors/bootstrap/css/bootstrap.css");
        elgg_register_css("ui-lightness", $CONFIG->url . "mod/sociable/vendors/jquery-ui-1.10.2.custom/css/ui-lightness/jquery-ui-1.10.2.custom.min.css");
        elgg_register_css("sociable", $CONFIG->url . "mod/sociable/css/sociable.css");
        elgg_register_css("bubblegum", "http://fonts.googleapis.com/css?family=Bubblegum+Sans");
        elgg_register_css("righteous", "http://fonts.googleapis.com/css?family=Righteous");
        elgg_register_css("ubuntu", "http://fonts.googleapis.com/css?family=Ubuntu:400,300,300italic,400italic,500,500italic,700,700italic");
        elgg_register_js("sociable", $CONFIG->url . "mod/sociable/js/sociable.js");
        elgg_register_js("jquery", $CONFIG->url . "mod/sociable/vendors/jquery/jquery-1.9.1.min.js", "head", 0);
        elgg_register_js("jquery-migrate", $CONFIG->url . "mod/sociable/vendors/jquery/jquery-migrate-1.1.1.js", "head", 1);
        elgg_register_js("jquery-ui", $CONFIG->url . "mod/sociable/vendors/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom.min.js", "head", 2);
        elgg_register_js("twitter-bootstrap", $CONFIG->url . "mod/sociable/vendors/bootstrap/js/bootstrap.min.js");
        elgg_load_css("ui-lightness");
        elgg_load_css("twitter-bootstrap");
        elgg_load_js("jquery-migrate");
        elgg_load_js("sociable");
        elgg_load_js("twitter-bootstrap");
        elgg_load_css("righteous");
        elgg_load_css("ubuntu");
        elgg_load_css("bubblegum");
        elgg_load_css("sociable");
        set_view_location("navigation/menu/site", elgg_get_plugins_path() . "sociable/new_views/");
        set_view_location("navigation/menu/elements/item", elgg_get_plugins_path() . "sociable/new_views/");
        set_view_location("navigation/menu/elements/section", elgg_get_plugins_path() . "sociable/new_views/");
        set_view_location("navigation/tabs", elgg_get_plugins_path() . "sociable/new_views/");
        set_view_location("navigation/menu/widget", elgg_get_plugins_path() . "sociable/new_views/");
    }
}