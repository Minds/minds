<?php
/**
 * Describe plugin here
 */

elgg_register_event_handler('init', 'system', 'honeypot_init');

function honeypot_init() {

	elgg_extend_view('forms/register', 'honeypot/hidden_input');
	elgg_extend_view('forms/user/requestnewpassword', 'honeypot/hidden_input');        
        
	// Extend the main CSS file
	elgg_extend_view('css/elgg', 'honeypot/css');
        
        // Register a function that provides some default override actions
        elgg_register_plugin_hook_handler('actionlist', 'honeypot', 'honeypot_actionlist_hook');   
        
        // Register actions to intercept
        $actions = array();
        $actions = elgg_trigger_plugin_hook('actionlist', 'honeypot', null, $actions);    

        if (($actions) && (is_array($actions))) {
            foreach ($actions as $action) {
                elgg_register_plugin_hook_handler("action", $action, "honeypot_verify_action_hook");
            }
        }        

}


function honeypot_actionlist_hook($hook, $entity_type, $returnvalue, $params) {
    if (!is_array($returnvalue)) {
        $returnvalue = array();
    }

    $returnvalue[] = 'register';
    $returnvalue[] = 'user/requestnewpassword';

    return $returnvalue;
}


function honeypot_verify_action_hook($hook, $entity_type, $returnvalue, $params) {

    $input = get_input('email_address');

    if (!empty($input)) {  
            $emailme = elgg_get_plugin_setting('emailme', 'honeypot');
            if($emailme==="yes"){
                $emailaddress = elgg_get_plugin_setting('emailaddress', 'honeypot');
                if(!empty($emailaddress)){
                    mail($emailaddress, elgg_echo('honeypot:spammercaught'), elgg_echo('honeypot:spammerdetails', array($input)));
                }
            }
        forward(elgg_get_site_url());
        return false;
    }

    return true;
}
