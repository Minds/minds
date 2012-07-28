<?php
	/**
	 * Elgg payment success page
	 *
	 * @package Elgg Membership
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgg.in/
	 */

        access_show_hidden_entities(true);
        elgg_set_ignore_access(true);
        
	$guid=get_input("guid");
        $cat_guid=get_input("cat_guid");

	$entity=get_entity($cat_guid);

        $new_user = get_entity($guid);

        $expiry_date = calculate_membership_expiry($new_user->guid, strtotime(date("F j Y")), $_SESSION['allow_trial']);

        $new_user->user_type = $entity->title;
        $new_user->payment_status = "Approved";
        $new_user->amount = $entity->amount;
        $new_user->update_date = strtotime(date("F j Y"));
        $new_user->expiry_date = $expiry_date;
        $new_user->notify_date = strtotime(date("Y-m-d", $new_user->expiry_date) . " -15 days");
        $new_user->subscr_period_duration = $_SESSION['subscr_period_duration'];
        $new_user->interval_unit = $_SESSION['interval_unit'];
        $new_user->approval_code = $_SESSION['approval_code'];
        $new_user->transaction_id = $_SESSION['transaction_id'];
        $new_user->subscription_id = $_SESSION['subscription_id'];
        // To save all transactions
        // Initialise a new ElggObject
        $transaction = new ElggObject();
        // Tell the system it's a leave type
        $transaction->subtype = "mem_transaction";
        // Set its owner to the current user
        $transaction->owner_guid =$new_user->guid;
        // Set it's container
        $transaction->container_guid = $new_user->guid;
        $transaction->access_id = ACCESS_PRIVATE;
        $transaction->payment_status = "Approved";
        $transaction->payment_type = "authorizenet";
        if(isset($_SESSION['aim_amount'] ) && $_SESSION['aim_amount']  > 0){
            $transaction->amount = $_SESSION['aim_amount'] ;
        }
        $transaction->expiry_date = $expiry_date;
        $transaction->subscr_period_duration = $_SESSION['subscr_period_duration'];
        $transaction->interval_unit = $_SESSION['interval_unit'];
        $transaction->approval_code = $_SESSION['approval_code'];
        $transaction->transaction_id = $_SESSION['transaction_id'];
        $transaction->subscription_id = $_SESSION['subscription_id'];

        /*      if(!$_SESSION['allow_trial'] && !isset ($_SESSION['coupon_code'])){
                $transaction2 = new ElggObject();
                $transaction2->subtype = "mem_transaction";
                $transaction2->owner_guid =$new_user->guid;
                $transaction2->container_guid = $new_user->guid;
                $transaction2->access_id = ACCESS_PRIVATE;
                $transaction2->payment_status = "Approved";
                $transaction2->payment_type = "authorizenet";
                if(isset($_SESSION['arb_amount'] ) && $_SESSION['arb_amount']  > 0){
                    $transaction2->amount = $_SESSION['arb_amount'] ;
                }
                $transaction2->expiry_date = $expiry_date;
                $transaction2->subscr_period_duration = $_SESSION['subscr_period_duration'];
                $transaction2->interval_unit = $_SESSION['interval_unit'];
                $transaction2->approval_code = $_SESSION['approval_code'];
                $transaction2->transaction_id = $_SESSION['transaction_id'];
                $transaction2->subscription_id = $_SESSION['subscription_id'];
        }else{*/
                $transaction->coupon_guid = $_SESSION['coupon_code']['guid'];
                $transaction->coupon_code = $_SESSION['coupon_code']['code'];
                $transaction->coupon_discount = $_SESSION['coupon_code']['discount'];
        //}

        $transaction->save();
        $trans_guid = $transaction->guid;
        /*if(isset($transaction2) && is_object($transaction2)){
           $transaction2->save();
            $trans_guid = $transaction2->guid;
        }*/
        if(isset ($_SESSION['coupon_code']))
        {
            $new_user->coupon_guid = $_SESSION['coupon_code']['guid'];
            $new_user->coupon_code = $_SESSION['coupon_code']['code'];
            $new_user->coupon_discount = $_SESSION['coupon_code']['discount'];
            add_entity_relationship( $_SESSION['coupon_code']['guid'], 'coupon_code_user', $new_user->guid);
        }

        $new_user->last_trans_id =  $trans_guid;
        $new_user->save();

        if(!elgg_is_logged_in()) {
            if (!$new_user->isAdmin) {
                $new_user->disable('new_user', false);
		elgg_set_user_validation_status($guid, FALSE);
            }
            system_message(sprintf(elgg_echo("registerok"),$CONFIG->sitename));
            uservalidationbyemail_request_validation($guid);
        }else {
            $receive_notifications = $CONFIG->plugin_settings->receive_notifications;
            if($receive_notifications == '1') {
                    //get admin's guid
                    $admin_guid = get_administrator_guid();
                    $report_url = $CONFIG->wwwroot . "membership/report";
                    // Send Notifications to admin
                    $result = notify_user($admin_guid, $CONFIG->site->guid, sprintf(elgg_echo('receive:notifications:subject'), $new_user->username), sprintf(elgg_echo('receive:notification:admin:body'),get_entity($admin_guid)->name, $new_user->name.' has','the', $entity->title,$report_url), NULL, 'email');
            }
            // Send Notifications
            $result = notify_user($guid, $CONFIG->site->guid, sprintf(elgg_echo('receive:notifications:subject'), $new_user->username), sprintf(elgg_echo('auth:receive:notification:body'),get_entity($guid)->name, 'You have', 'your',$entity->title,$url), NULL, 'email');
            system_message(elgg_echo('upgrade:ok'));
        }

 	access_show_hidden_entities(false);
        
        $action = $CONFIG->wwwroot;
        
        $body = "<br>".elgg_echo('approvalcode')."".$_SESSION['approval_code'];
        $body .= "<br>".elgg_echo('transaction:id')."".$_SESSION['transaction_id'];
        $body .= "<br>".elgg_echo('subscription:id')."".$_SESSION['subscription_id'];
        $body .= "<br>".$_SESSION['responese_text'];

?>
        <div class="contentWrapper">
            <form action="<?php echo $action ?>" method="post">
                <p>
                    <?php echo $body; ?>
                </p>
                <input type="submit" name="btn_submit" class="elgg-button elgg-button-submit" value="<?php echo elgg_echo('back:text') ?>">
            </form>
        </div>
<?php
        if(isset ($_SESSION['aim_amount']))
        {
            unset($_SESSION['aim_amount']);
        }
        if(isset ($_SESSION['allow_trial']))
        {
            unset($_SESSION['allow_trial']);
        }
        if(isset ($_SESSION['arb_amount']))
        {
            unset($_SESSION['arb_amount']);
        }
        unset($_SESSION['subscr_period_duration']);
        unset($_SESSION['interval_unit']);
        unset($_SESSION['approval_code']);
        unset($_SESSION['transaction_id']);
        unset($_SESSION['responese_text']);
        unset($_SESSION['subscription_id']);
        if(isset ($_SESSION['coupon_code']))
        {
            unset($_SESSION['coupon_code']);
        }
        if(isset ($_SESSION['register']))
        {
            unset($_SESSION['register']);
        }
?>
