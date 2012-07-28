<?php
    /**
    * Elgg Membership plugin
    * Membership delete user page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    global $CONFIG;
    // block non-admin users - require since this action is not registered
    admin_gatekeeper();
    // Get the user
    $guid = get_input('guid');
    $user = get_entity($guid);
    if(strtolower($user->user_type) != strtolower('Free')) {
        // Get last transaction
        $options = array('types'=>'object',
                'subtypes'=>'mem_transaction',
                'owner_guids'=>$guid,
                'limit'=>1,
                'metadata_name_value_pairs' => array('subscription_id'=>$user->subscription_id),
                'metadata_case_sensitive' => false);
        $transactions = elgg_get_entities_from_metadata($options);
        if($transactions) {
            // If transactions
            foreach($transactions as $transaction) {
                if($transaction->payment_type == 'paypal') { //if paypal
                    if($transaction->allow_recurring) {
                        if($transaction->active_status=='active') {
                            //Send notifications to user
                            $result = notify_user($guid, elgg_get_site_entity()->guid, elgg_echo('membership:disable:user:subject'), sprintf(elgg_echo('membership:disable:user:body'), $user->name, $user->subscription_id), NULL, 'email');
                            $user->disable('disable_user', false);
			    elgg_set_user_validation_status($guid, FALSE);
                            $user->disabled_user = 1;
                            system_message(sprintf(elgg_echo('admin:user:disabled:yes'), $user->name));
                            // forward to user administration if on a user's page as it no longer exists
                            $forward = REFERER;
                            if (strpos($_SERVER['HTTP_REFERER'], $user->username) != FALSE) {
                                $forward = "/admin/users/newest";
                            }
                            forward($forward);
                        } else {
                            //delete user
                            $auth_result = 1;
                        }
                    } else {
                        //delete user
                        $auth_result = 1;
                    }
                } else { //if authorize.net
                    if($user->subscription_id)
                    {
                        $auth_result = membership_delete_subscription($guid);
                    }
                    else {
                        $auth_result = 1;
                    }

                }
            }
        }  else {
            //delete user
            $auth_result = 1;
        }
    } else {
        //delete user
        $auth_result = 1;
    }
    if($auth_result == "1") {
        if (($user instanceof ElggUser) && ($user->canEdit())) {
            if ($user->delete()) {
                    system_message(sprintf(elgg_echo('admin:user:delete:yes'), $user->name));
            } else {
                    register_error(elgg_echo('admin:user:delete:no'));
            }
        } else {
            register_error(elgg_echo('admin:user:delete:no'));
        }
    } else {
        register_error(elgg_echo('auth:admin:user:delete:no'));
    }
    // forward to user administration if on a user's page as it no longer exists
    $forward = REFERER;
    if (strpos($_SERVER['HTTP_REFERER'], $user->username) != FALSE) {
        $forward = "/admin/users/newest";
    }
    forward($forward);
