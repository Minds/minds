<?php
    /**
    * Elgg Membership plugin
    * Membership manage payment page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    
    global $CONFIG;

    elgg_set_context('upgrade_membership');
    elgg_set_ignore_access(true);
    access_show_hidden_entities(true);

    $manage_action=get_input('manage_action','makepayment');
    $status = get_input('status');

    $coupon_guid = get_input('coupon', 0);
    if($coupon_guid){
        $coupon = get_entity($coupon_guid);
    }

    $guid = get_input("guid");
    $new_user = get_entity($guid);
    
    $cat_guid = get_input("cat_guid");
    $entity = get_entity($cat_guid);
    
    $type =  $CONFIG->plugin_settings->payment_type;
    $email =  $CONFIG->plugin_settings->paypal_email;
    $allow_recurring =  $CONFIG->plugin_settings->allow_recurring;
    $allow_trial =  $CONFIG->plugin_settings->allow_trial;
    $trial_amount =  $CONFIG->plugin_settings->trial_amount;
    $process_payment = false;

    /*---- Manage action    ----*/
    if($manage_action=="makepayment"){

        if($type == "sandbox"){
        $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errnum, $errstr, 30);
        }elseif ($type == "paypal"){
        $fp = fsockopen ('ssl://www.paypal.com', 443, $errnum, $errstr, 30);
        }
        
        //$fp = fsockopen($ssl.$web['host'], $web['port'], $errnum, $errstr, 30);
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        global $registering_admin;
        $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen ($req) . "\r\n\r\n";

        if (!$fp) {
            // HTTP error
            // log an error
        } else {
            // POST the data using the file pointer created above
            fputs ($fp, $header . $req);
            while (!feof($fp)) {
                // read the response from the PayPal server
                $res = fgets ($fp, 1024);
                // check if the request has been VERIFIED by PayPal
                if (strcmp ($res, "VERIFIED") == 0) {
                    // check the transaction type for the subscription sent by PayPal
                    // and take action accordingly
                    if(isset($_POST["txn_type"])) {
                            if((strtolower($_POST["txn_type"]) == "subscr_payment")||(strtolower($_POST["txn_type"]) == "subscr_signup")) {
                                // a subscriber just paid
                                // check that receiver_email is your Primary PayPal email
                                if($_POST['receiver_email'] == $email){
                                    // check the payment_status is Completed for subscr_payment
                                    switch($_POST["txn_type"]) {
                                        case "subscr_payment":
                                            switch($_POST['payment_status']) {
                                                case "Completed":
                                                    $process_payment = true;
                                                    break;
                                                case "Pending":
                                                     $process_payment = true;
                                                     break;
                                                default:
                                                     request_reconfirm($guid);
                                                     break;
                                            }
                                            break;
                                        case "subscr_signup":
                                             if($status == 'register' && $allow_trial) {
                                                if($trial_amount == 1) {
                                                    $process_payment = false;
                                                } else if($trial_amount == 0) {
                                                    $process_payment = true;
                                                }
                                            }
                                            break;
                                    }
                                }
                            } else if(strtolower($_POST["txn_type"]) == "subscr_cancel") {
                                //Take the latest transaction details with status 'Active' and the returned subscription_id.
                                $options = array('types'=>'object',
                                        'subtypes'=>'mem_transaction',
                                        'owner_guids'=>$guid,
                                        'limit'=>1,
                                        'metadata_name_value_pairs' => array('payment_type' => 'paypal','subscription_id'=>$_POST['subscr_id'],'tran_status'=>'active', 'active_status'=>'active'),
                                        'metadata_case_sensitive' => false);
                                $transactions = elgg_get_entities_from_metadata($options);
                                if($new_user->subscription_id == $_POST['subscr_id']) {
                                    if($new_user->disabled_user) {
                                        // Notify user that he has deleted by canceling the subscription.
                                        if($transactions) {
                                            // Send Notifications to user
                                            foreach($transactions as $transaction) {
                                                $transaction->active_status = 'inactive';
                                                $transaction->save();
                                                $result = notify_user($new_user->guid, $CONFIG->site->guid, elgg_echo('cancelled:membership:subject'), sprintf(elgg_echo('cancelled:delete:membership:body'), $new_user->name, $transaction->user_type,$_POST['subscr_id']), NULL, 'email');
                                                $admin_guid = get_administrator_guid();
                                                // Send Notifications to administrator
                                                $result = notify_user($admin_guid, $CONFIG->site->guid, elgg_echo('cancelled:membership:subject'), sprintf(elgg_echo('cancelled:delete:membership:admin:body'),get_entity($admin_guid)->name, $new_user->name,$transaction->user_type,$_POST['subscr_id']), NULL, 'email');
                                            }
                                        }
                                        // Disabled User. So delete that user.
                                        $new_user->delete();
                                    } else {
                                        // Cancel current subscription
                                       if($transactions) {
                                            // Send Notifications to user
                                            foreach($transactions as $transaction) {
                                                $transaction->active_status = 'inactive';
                                                $transaction->save();
                                                $result = notify_user($new_user->guid, $CONFIG->site->guid, elgg_echo('cancelled:membership:subject'), sprintf(elgg_echo('cancelled:membership:body'), $new_user->name, $transaction->user_type,$_POST['subscr_id'],$CONFIG->wwwroot.'membership/confirm/'), NULL, 'email');
                                                $admin_guid = get_administrator_guid();
                                                // Send Notifications to administrator
                                                $result = notify_user($admin_guid, $CONFIG->site->guid, elgg_echo('cancelled:membership:subject'), sprintf(elgg_echo('cancelled:membership:admin:body'),get_entity($admin_guid)->name, $new_user->name, $transaction->user_type,$_POST['subscr_id'],$CONFIG->wwwroot.'membership/upgrade/'.$new_user->guid), NULL, 'email');
                                            }
                                        }
                                        // When the subscription cancelled by user
                                        $prev_user_type = $new_user->user_type;

                                        $new_user->user_type = 'Free';
                                        $new_user->amount = 0;
                                        $new_user->expiry_date = '';
                                        $new_user->notify_date = '';
                                        $new_user->update_date = strtotime(date("F j Y"));
                                        $user->expired_reason = 'Subscription Cancelled';
                                        $new_user->save();
                                    }
                                } else {
                                    // previous subscription cancellation
                                    //Take the transaction details with status 'Active' and the returned subscription_id.
                                    //If such a transaction exists, we need to mail that transaction details to the user to cancel.
                                    if($transactions) {
                                        foreach($transactions as $transaction) {
                                            $transaction->active_status = 'inactive';
                                            $transaction->save();
                                            $result = notify_user($new_user->guid, $CONFIG->site->guid, elgg_echo('cancelled:subscription:subject'), sprintf(elgg_echo('cancelled:previous:membership:body'), $new_user->name, $_POST['subscr_id'],elgg_get_site_entity()->url), NULL, 'email');
                                            $admin_guid = get_administrator_guid();
                                            // Send Notifications to administrator
                                            $result = notify_user($admin_guid, $CONFIG->site->guid, elgg_echo('cancelled:subscription:subject'), sprintf(elgg_echo('cancelled:previous:membership:admin:body'),get_entity($admin_guid)->name, $new_user->name, $_POST['subscr_id'],$CONFIG->wwwroot.'membership/upgrade/'.$new_user->guid), NULL, 'email');
                                        }
                                    } 
                                }
                                // To save all transaction details for all cancellations
                                // Initialise a new ElggObject
                                $transaction = new ElggObject();
                                // Tell the system it's a mem_transaction type
                                $transaction->subtype = "mem_transaction";
                                // Set its owner to the current user
                                $transaction->owner_guid =$guid;
                                // Set it's container
                                $transaction->container_guid = $guid;
                                // To specify which payment type
                                $transaction->payment_type = 'paypal';
                                if($_POST['txn_id']) {
                                    $transaction->transaction_id = $_POST['txn_id'];
                                }
                                if($_POST['subscr_id']) {
                                    $transaction->subscription_id = $_POST['subscr_id'];
                                }
                                if($_POST['subscr_date']) {
                                    $transaction->subscr_date = $_POST['subscr_date'];
                                }
                                $transaction->prev_user_type = $prev_user_type;
                                $transaction->user_type = $new_user->user_type;
                                $transaction->payment_status = $_POST['payment_status'];
                                $transaction->pay_email =$email;
                                $transaction->tran_status = "Cancel";
                                $transaction->active_status = 'inactive';
                                $transaction->access_id = 2;
                                $transaction_guid = $transaction->save();
                            } else {
                                // incorrect transaction type
                            }
                    } else {
                        // incorrect transaction type
                        // log an error
                    }
                } else if (strcmp ($res, "INVALID") == 0) {
                    // an INVALID transaction
                    // log an error
                }
            }
            fclose ($fp);
        }

        if($process_payment){
            $prev_user_type = $new_user->user_type;
            $new_user->user_type = $entity->title;
            $new_user->amount = $entity->amount;
            $new_user->update_date = strtotime(date("F j Y"));
            if($status == 'register' && $allow_trial) {
                $new_user->expiry_date = calculate_membership_expiry($guid,'',TRUE);
            } else {
                $new_user->expiry_date = calculate_membership_expiry($guid);
            }
            $new_user->notify_date = strtotime(date("Y-m-d", $new_user->expiry_date) . " -15 days");
            if($_POST['txn_id']) {
                $new_user->last_trans_id = $_POST['txn_id'];
            }
            if($_POST['subscr_id']) {
                $new_user->subscription_id = $_POST['subscr_id'];
            }
            $new_user->payment_status = $_POST['payment_status'];
            if($coupon instanceof ElggObject){
                $new_user->coupon_guid = $coupon->guid;
                $new_user->coupon_code = $coupon->coupon_code;
                $new_user->coupon_discount = $coupon->coupon_amount;
                add_entity_relationship($coupon->guid, 'coupon_code_user', $new_user->guid);
            }
            // Store the latest subscription duration and unit
            $new_user->interval_unit = $CONFIG->plugin_settings->subscr_period_units;
            $new_user->subscr_period_duration = $CONFIG->plugin_settings->subscr_period_duration;
            $new_user->save();
            // To save all transaction details
            // Initialise a new ElggObject
            $transaction = new ElggObject();
            // Tell the system it's a mem_transaction type
            $transaction->subtype = "mem_transaction";
            // Set its owner to the current user
            $transaction->owner_guid =$guid;
            // Set it's container
            $transaction->container_guid = $guid;
            // To specify which payment type
            $transaction->payment_type = 'paypal';
            if($_POST['txn_id']) {
                $transaction->transaction_id = $_POST['txn_id'];
            }
            if($_POST['subscr_id']) {
                $transaction->subscription_id = $_POST['subscr_id'];
            }
            if($_POST['subscr_date']) {
                $transaction->subscr_date = $_POST['subscr_date'];
            }
            $transaction->payment_status = $_POST['payment_status'];
            $transaction->amount = (float)$_POST['mc_gross']; //full amount of payment. payment_gross in US
            $transaction->pay_email = $email;
            $transaction->payment_fee = (float)$_POST['payment_fee'];
            /* To save transaction status */
            $transaction->tran_status = "active";
            $transaction->active_status = 'active';
            $transaction->prev_user_type = $prev_user_type;
            $transaction->user_type = $new_user->user_type;
            /* paypal subscription settings for each transaction */
            $transaction->allow_trail = $CONFIG->plugin_settings->allow_trial;
            $transaction->allow_recurring = $CONFIG->plugin_settings->allow_recurring;
            $transaction->trial_period_units = $CONFIG->plugin_settings->trial_period_units;
            $transaction->trial_period_duration = $CONFIG->plugin_settings->trial_period_duration;
            $transaction->trial_amount = $CONFIG->plugin_settings->trial_amount;
            $transaction->interval_unit = $CONFIG->plugin_settings->subscr_period_units;
            $transaction->subscr_period_duration = $CONFIG->plugin_settings->subscr_period_duration;
            $transaction->recurring_times = $CONFIG->plugin_settings->recurring_times;
            $transaction_guid = $transaction->save();
            if($transaction_guid) {
                // Add a relationship with the transaction and user
                add_entity_relationship($transaction_guid, 'user_membership_transaction', $new_user->guid);
            }
            // set the access
            if(!$new_user->validate_status) {
                if($status == 'register') {
                    uservalidationbyemail_request_validation($guid);
                    $new_user->validate_status = true;
                    $new_user->save();
                    if (!$new_user->admin) {
                        $new_user->disable('new_user', false);
			elgg_set_user_validation_status($guid, FALSE);
                    }
                }
            }
        } else {
            $new_user->payment_status=$_POST['payment_status'];
            if(!$new_user->validate_status) {
                if($status == 'register') {
                    uservalidationbyemail_request_validation($guid);
                    $new_user->validate_status = true;
                    $new_user->save();
                    if (!$new_user->admin) {
                        $new_user->disable('new_user', false);
			elgg_set_user_validation_status($guid, FALSE);
                    }
                }
            } else {
                $new_user->save();
            }
            
        }
    }

    access_show_hidden_entities(false);
?>
