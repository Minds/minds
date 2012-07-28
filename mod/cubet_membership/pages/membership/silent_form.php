<?php
    /**
     * Authorize.net silent post url action page
     *
     * @package Elgg Membership
     * @author Cubet Technologies
     * @copyright Cubet Technologies 20010-2011
     * @link http://elgg.in/
     */
?>
<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");
    global $CONFIG;
    $subscription_id = (int) $_REQUEST['x_subscription_id'];

    // Check to see if we got a valid subscription ID.
    // If so, do something with it.
    if ($subscription_id)
    {
        access_show_hidden_entities(true);
        set_context('upgrade_membership');
        // Get the subscription ID if it is available.
        // Otherwise $subscription_id will be set to zero.

        $options = array('types'=>'user', 'limit'=>1, 'offset'=>0, 'metadata_names'=>'subscription_id', 'metadata_values'=>$subscription_id);
        $users = elgg_get_entities_from_metadata($options);
        if($users && is_array($users)){
            $user = $users[0];
        }
        
        if ($user instanceof ElggUser)
        {
            // Get the response code. 1 is success, 2 is decline, 3 is error
            $response_code = (int) $_REQUEST['x_response_code'];

            // Get the reason code. 8 is expired card.
            $reason_code = (int) $_REQUEST['x_response_reason_code'];

            $subscr_period_duration = $user->subscr_period_duration;
            $interval_unit = $user->interval_unit;
            $date = strtotime(date("Y-m-d", strtotime(date("F j Y"))) . " +{$subscr_period_duration} $interval_unit");
            $expiry_date = strtotime(date("Y-m-d", $date) . " +1 days");

            $user_disable = false;
            if ($response_code == 1)
            {
                // Approved!
                $status =  'Approved';
                // Some useful fields might include:
                $user->payment_status = $status;
                $user->amount = $_REQUEST['x_amount'];
                $user->update_date = strtotime(date("F j Y"));
                $user->expiry_date = $expiry_date;
                $user->notify_date = strtotime(date("Y-m-d", $user->expiry_date) . " -2 days");
                $user->subscr_period_duration = $subscr_period_duration;
                $user->interval_unit = $interval_unit;
                $user->subscription_id = $_REQUEST['x_subscription_id'];
            }
            else if ($response_code == 2)
            {
                // Declined
                $status =  'Declined';
                $user->user_type = 'Free';
                $user->payment_status = $status;
                $user->amount = 0;
                $user->update_date = strtotime(date("F j Y"));
                $user->expiry_date = "";
                $user->notify_date = "";
                $user->subscription_id = 0;
                $user_disable = true;
            }
            else if ($response_code == 3 && $reason_code == 8)
            {
                // An expired card
                $status =  'ExpiredCard';
                $user->user_type = 'Free';
                $user->payment_status = $status;
                $user->amount = 0;
                $user->update_date = strtotime(date("F j Y"));
                $user->expiry_date = "";
                $user->notify_date = "";
                $user->subscription_id = 0;
                $user_disable = true;
            }
            else
            {
                // Other error
                $status =  'Generalerror';
                $user->user_type = 'Free';
                $user->payment_status = $status;
                $user->amount = 0;
                $user->update_date = strtotime(date("F j Y"));
                $user->expiry_date = "";
                $user->notify_date = "";
                $user->subscription_id = 0;
                $user_disable = true;
            }

            // To save all transactions
            // Initialise a new ElggObject
            $transaction = new ElggObject();
            // Tell the system it's a leave type
            $transaction->subtype = "mem_transaction";
            // Set its owner to the current user
            $transaction->owner_guid =$user->guid;
            // Set it's container
            $transaction->container_guid = $user->guid;
            $transaction->access_id = ACCESS_PRIVATE;
            $transaction->payment_status = $status;
            $transaction->payment_type = "authorize";
            $transaction->amount = $_REQUEST['x_amount'];
            $transaction->expiry_date = $expiry_date;
            $transaction->subscr_period_duration = $subscr_period_duration;
            $transaction->interval_unit = $interval_unit;
            $transaction->transaction_id = $_REQUEST['x_trans_id'];
            $transaction->subscription_id = $_REQUEST['x_subscription_id'];
            $transaction->response_code = $_REQUEST['x_response_code'];
            $transaction->response_reason_code = $_REQUEST['x_response_reason_code'];
            $transaction->response_reason_text = $_REQUEST['x_response_reason_text'];
            $transaction->subscription_paynum = $_REQUEST['x_subscription_paynum'];
            $transaction->save();
            $trans_guid = $transaction->guid;

            $user->last_trans_id =  $trans_guid;
            $user->save();

            if($user_disable){
                // Make the user free if payment fails
                $user->user_type = 'Free';
                $user->expired_reason = $status;
                $user->amount = 0;
                $user->duration = 0;
                $user->update_date = strtotime(date("F j Y"));
                $user->expiry_date = '';
                $user->notify_date = 0;
                $user->save();
                /////////////////////////////////////////////
                // Work out validate link
                $link = $CONFIG->wwwroot . "mod/cubet_membership/confirm.php?guid=".$user->guid;

                // Send validation email
                $result = notify_user($user->guid, $CONFIG->site->guid, sprintf(elgg_echo('cancel:validate:subject'), $user->username),sprintf(elgg_echo('cancel:validate:body'), $user->name, $link), NULL, 'email');
                ////////////////////////////////////////////
            }
        }else{
                // send an email to administrator for notify that the system could not find out the user based on subscription id
                $admin_guid = get_administrator_guid();
                $result = notify_user($admin_guid, $CONFIG->site->guid, elgg_echo('expired:subscription:subject'),sprintf(elgg_echo('expired:subscription:admin:body'),get_entity($admin_guid)->name,$subscription_id), NULL, 'email');
        }
    }
    exit;
?>