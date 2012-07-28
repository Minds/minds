<?php
     /**
    * Elgg Membership plugin
    * Membership save settings page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    $params = get_input('params');
    $guid = get_input('guid');
    if($guid) {
        $plugin_settings = get_entity($guid);
    } else {
        $plugin_settings = new ElggObject();
        $plugin_settings->subtype = 'membership_settings';
        $plugin_settings->access_id = 2;
        $plugin_settings->container_guid = $_SESSION['user']->guid;
    }
    foreach ($params as $key => $val) {
        if($key == 'paypal_email' && empty($val)) {
            register_error(elgg_echo("membership:paypal_email:error"));
            forward(REFERER);
        } else if(($key == 'authorizenet_apiloginid' && empty($val)) || ($key == 'authorizenet_transactionkey' && empty($val))) {
            register_error(elgg_echo("membership:fields:empty:error"));
            forward(REFERER);
        }
        $plugin_settings->$key = $val;
    }
    if($plugin_settings->save()) {
        system_message(sprintf(elgg_echo("membership:settings:saved")));
    } else {
        register_error(elgg_echo("membership:settings:error"));
    }
    forward(REFERER);
    