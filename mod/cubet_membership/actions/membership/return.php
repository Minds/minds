<?php
    /**
    * Elgg Membership plugin
    * Elgg return action
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");
    global $CONFIG;
    $guid=get_input('var_param');
    if(!$_SESSION['user']) {
        elgg_push_context('uservalidationbyemail_new_user');
        $hidden_entities = access_get_show_hidden_status();
	access_show_hidden_entities(TRUE);
        $new_user = get_entity($guid);
        if (!$new_user->admin) {
            $new_user->disable('new_user', FALSE);
        }
        // set user as unvalidated and send out validation email
	elgg_set_user_validation_status($guid, FALSE);
	uservalidationbyemail_request_validation($guid);
        elgg_pop_context();
        access_show_hidden_entities($hidden_entities);
        system_message(elgg_echo("registerok", array(elgg_get_site_entity()->name)));
    }
    
?>