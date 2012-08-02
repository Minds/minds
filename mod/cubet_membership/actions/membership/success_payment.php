<?php
    /**
    * Elgg Membership plugin
    * Membership paypal payment success action
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    
    global $CONFIG;
    elgg_set_ignore_access(true);
    access_show_hidden_entities(true);
    $type = $CONFIG->plugin_settings->payment_type;
    if($type=='paypal') {
        $url="https://www.paypal.com/cgi-bin/webscr";
    } else if($type=='sandbox') {
        $url="https://www.sandbox.paypal.com/cgi-bin/webscr";
    }
    $guid=get_input("guid");
    $new_user = get_entity($guid);
    $cat_guid=get_input("cat_guid");
    $entity=get_entity($cat_guid);

    if(isset($_SESSION['coupon_code'])){
        unset($_SESSION['coupon_code']);
    }

    access_show_hidden_entities(false);
    $manage_action=get_input('manage_action');

    if($manage_action=="cart_success"){
        if(!elgg_is_logged_in()) {
            // Register
            system_message(elgg_echo('uservalidationbyemail:registerok'));
            system_message(sprintf(elgg_echo("registerok"),$CONFIG->sitename));
        } else {
            // Upgrade Membership
            $receive_notifications = $CONFIG->plugin_settings->receive_notifications;
            if($receive_notifications == '1') {
                //get admin's guid
                $admin_guid = get_administrator_guid();
                $report_url = $CONFIG->wwwroot . "membership/report";
                // Send Notifications to admin
                $result = notify_user($admin_guid, $CONFIG->site->guid, sprintf(elgg_echo('receive:notifications:subject'), $new_user->username), sprintf(elgg_echo('receive:notification:admin:body'),get_entity($admin_guid)->name, $new_user->name.' has','the', $entity->title,$report_url), NULL, 'email');
            }
            // Send Notifications
            //Take last transaction details
            $joins = array(
                    "JOIN {$CONFIG->dbprefix}metadata md on e.guid = md.entity_guid",
                    "JOIN {$CONFIG->dbprefix}metastrings ms_n on md.name_id = ms_n.id",
                    "JOIN {$CONFIG->dbprefix}metastrings ms_v on md.value_id = ms_v.id");
            $wheres = array("(ms_n.string = 'subscription_id' AND ms_v.string != '{$new_user->subscription_id}' )");
            $options = array('types'=>'object',
                    'subtypes'=>'mem_transaction',
                    'owner_guids'=>$guid,
                    'limit'=>1,
                    'joins'=>$joins,
                    'wheres'=>$wheres,
                    'metadata_name_value_pairs' => array('payment_type' => 'paypal','tran_status'=>'active', 'active_status'=>'active'),
                    'metadata_case_sensitive' => false,
                    );
            $transactions = elgg_get_entities_from_metadata($options);
            if($transactions) {
                foreach($transactions as $transaction) {
                    if($transaction->allow_recurring) { 
                        // Mail with transaction details
                        $result = notify_user($guid, $CONFIG->site->guid, sprintf(elgg_echo('receive:notifications:subject'), $new_user->username), sprintf(elgg_echo('transaction:receive:notification:body'),get_entity($guid)->name, 'You have', 'your',$entity->title,$transaction->subscription_id,$url,$transaction->subscription_id,$transaction->pay_email), NULL, 'email');
                    } else {
                        $result = notify_user($guid, $CONFIG->site->guid, sprintf(elgg_echo('receive:notifications:subject'), $new_user->username), sprintf(elgg_echo('receive:notification:success:body'),get_entity($guid)->name, 'You have', 'your',$entity->title,elgg_get_site_entity()->url), NULL, 'email');
                    }
                }
            } else {
                $result = notify_user($guid, $CONFIG->site->guid, sprintf(elgg_echo('receive:notifications:subject'), $new_user->username), sprintf(elgg_echo('receive:notification:success:body'),get_entity($guid)->name, 'You have', 'your',$entity->title,elgg_get_site_entity()->url), NULL, 'email');
            }
            system_message(elgg_echo('upgrade:ok'));
        }
        forward();
    } else if($manage_action=="cart_cancel"){
        if(!elgg_is_logged_in())
        {
           register_error(elgg_echo('membership:premiumfailed'));
            /*system_message(sprintf(elgg_echo("registerok"),$CONFIG->sitename));
            request_reconfirm($guid);

            if (!$new_user->admin) {
                $new_user->disable('new_user', false);
		elgg_set_user_validation_status($guid, FALSE);
            }*/
        }
        forward();
    }
?>
