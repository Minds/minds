<?php
    /**
    * Elgg Membership plugin
    * Authorize.net action page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */

?>
<?php
    
    global $CONFIG;
    require('AuthnetAIM.class.php');

    access_show_hidden_entities(true);
    elgg_set_ignore_access(true);

    if(elgg_is_logged_in()) {
        $status = 'upgrade';
    } else {
        $status = 'register';
    }
    
    $usertype = $_POST["user_type"];
    $userid = $_POST["user_id"];
    if(elgg_is_logged_in()){
        $user = get_loggedin_user();
    }
    
    $entity = get_entity($usertype);
    
    $amount = $entity->amount;

    $plugin_settings = $CONFIG->plugin_settings;
    $apiloginid = $plugin_settings->authorizenet_apiloginid;
    $transactionkey = $plugin_settings->authorizenet_transactionkey;
    $accounttype = $plugin_settings->authorizenet_environment;
    if($accounttype == "yes")
    {
        $accounttype = "true";
        $arb_server = "1";
    }
    else
    {
        $accounttype = "";
        $arb_server = "0";
    }
    
    $allow_trial = ($plugin_settings->allow_trial)?$plugin_settings->allow_trial:'0';
    $trial_period_units = ($plugin_settings->trial_period_units)?$plugin_settings->trial_period_units:'D';
    $trial_period_duration = $plugin_settings->trial_period_duration;
    $trial_amount = ($plugin_settings->trial_amount)?$plugin_settings->trial_amount:0;

    $subscr_period_units = ($plugin_settings->subscr_period_units)?$plugin_settings->subscr_period_units:'D';
    $subscr_period_duration = $plugin_settings->subscr_period_duration;
    
    $allow_recurring = ($plugin_settings->allow_recurring)?$plugin_settings->allow_recurring:'0';
    $recurring_times = ($plugin_settings->recurring_times)?$plugin_settings->recurring_times:1;

    ////////////////////////////////////////////////////////////////////
     if($trial_period_units == 'D'){
            $add_date = $trial_period_duration;
    } else if($trial_period_units == 'W'){
            $add_date = $trial_period_duration * 7;
    } else if($trial_period_units == 'M'){
            $add_month = $trial_period_duration;
    } else if($trial_period_units == 'Y'){
            $add_year = $trial_period_duration;
    }
    ////////////////////////////////////////////////////////////////////
    if($subscr_period_units == 'D'){
            $interval_unit = 'days';
            $sub_add_date = $subscr_period_duration;
    } else if($subscr_period_units == 'W'){
            $interval_unit = 'days';
            $sub_add_date = $subscr_period_duration = $subscr_period_duration * 7;
    } else if($subscr_period_units == 'M'){
            $interval_unit = 'months';
            $sub_add_month = $subscr_period_duration;
    } else if($subscr_period_units == 'Y'){
            $interval_unit = 'months';
            $sub_add_month = $subscr_period_duration;
    }
    /////////////////////////////////////////////////////////////////////

    if($allow_recurring) {
        if($recurring_times == '1')
        {
            $totalOccurrences = "9999";
        }
        else
        {
            $totalOccurrences = $recurring_times;
        }
    }
    else
    {
        $totalOccurrences = "1";
    }
    
    ////////////////////////////////////////////////////////////////////
    if($status == 'upgrade'){
        /////////////////// Amount Calculation /////////////////////////////////
        //Take last transaction detail that is active to calculate upgrade amount
        /*if(strtolower($user->user_type)!= 'free' && (!isset($_SESSION['coupon_code']['amount']))) {
            $options = array('types'=>'object',
                    'subtypes'=>'mem_transaction',
                    'owner_guids'=>$guid,
                    'limit'=>1,
                    'metadata_name_value_pairs' => array('payment_type' => 'authorizenet','payment_status' => "Approved"),
                    'metadata_case_sensitive' => false
                    );
            $transactions = elgg_get_entities_from_metadata($options);
            if($transactions) {
                foreach($transactions as $transaction) {
                    // If the user has a transaction then take last transaction duration to calculate the amount to upgrade
                    $tran_subscr_period_units = $transaction->interval_unit ? $transaction->interval_unit :  $CONFIG->plugin_settings->subscr_period_units;
                    $tran_subscr_period_duration = $transaction->subscr_period_duration ? $transaction->subscr_period_duration:  $CONFIG->plugin_settings->subscr_period_duration;
                }
            } else {
                $tran_subscr_period_units = $subscr_period_units;
                $tran_subscr_period_duration = $subscr_period_duration;
            }
            if($user->amount) {
                if($transactions) {// Only if transaction is active
                    $total_duration = abs(strtotime(date('F j Y')) - $user->update_date);
                    $duration_days = floor($total_duration/(60*60*24));// Difference between the dates
                    if($duration_days) {
                        if($tran_subscr_period_units == 'D') {
                            $duration = round($duration_days / $tran_subscr_period_duration,2);
                            $remaining_days =  $tran_subscr_period_duration - $duration_days;
                        } else if($tran_subscr_period_units == 'W') {
                            $duration = round($duration_days / ($tran_subscr_period_duration * 7),2);
                            $remaining_days = ($tran_subscr_period_duration * 7) - $duration_days;
                        } else if($tran_subscr_period_units == 'M') {
                            $duration = round($duration_days / ($tran_subscr_period_duration * 30),2);
                            $remaining_months =  floor((($tran_subscr_period_duration * 30) - $duration_days) / 30);
                        } else if($tran_subscr_period_units == 'Y') {
                            // 1 Year = 365.2425 Days
                            $duration = round($duration_days / ($tran_subscr_period_duration * 365.2425),2);
                            $remaining_months =  floor((($tran_subscr_period_duration * 365.2425) - $duration_days) / 30);
                        }
                        $tran_amount = round($amount - ($user->amount / $duration),2);
                    } else { // if upgrades on the same day
                        $tran_amount = $amount - $user->amount;
                    }
                }
            }
        }
        if(empty ($tran_amount)) {
            $tran_amount = $amount;
        }*/
        
        /////////////////// Amount Calculation End/////////////////////////////////
        $old_amount = $user->amount;
        if(isset($_SESSION['coupon_code']['amount']))
        {
            $aim_amount = $_SESSION['coupon_code']['amount'];
            $date = date("Y-m-d",  mktime(0,0,0,date("m") + $sub_add_month, date("d") + $sub_add_date, date("Y")));
        }
        /*else if($tran_amount != $entity->amount) {
            $aim_amount = $tran_amount;
            $date = date("Y-m-d",  mktime(0,0,0,date("m") + $remaining_months, date("d") + $remaining_days, date("Y")));
        }*/
        else
        {
            //$aim_amount = $amount - $old_amount;//0.01;
            $aim_amount = 0.01;
            $date = date("Y-m-d",  mktime(0,0,0,date("m"), date("d"), date("Y")));
        }

    }
    else
    {
        //////////////////////////////////////////////////////////////////////////
        if($allow_trial)
        {
            if(isset($_SESSION['coupon_code']['amount']))
            {
                $aim_amount = 0.01;
                $trial_amount = $_SESSION['coupon_code']['amount'];
            }
            else
            {
                $aim_amount = 0.01;
            }
            
            $start_date = mktime(0,0,0,date("m")+$add_month,date("d")+$add_date,date("Y")+$add_year);
            $date = date("Y-m-d", $start_date);
        }
        else
        {
            if(isset($_SESSION['coupon_code']['amount']))
            {
                $aim_amount = $_SESSION['coupon_code']['amount'];
            }
            else
            {
                $aim_amount = 0.01;
            }
            
            $start_date = mktime(0,0,0,date("m"),date("d"),date("Y"));
            $date = date("Y-m-d", $start_date);
        }
    }
   
    ////////////////////////////////////////////////////////////////////////////////
    if($status == 'upgrade'){
        if($user->subscription_id)
        {
            $user_subscription_id = $user->subscription_id;
        }
    }
    ////////////////////////////////////////////////////////////////////////////////

    if(elgg_is_logged_in()){
        $email   = $user->email;
    }
    else{
        $email  = $_SESSION['register']['email'];
    }

    $product = 'Membership Fee';
    $business_firstname = $_POST["bill_first_name"];
    $business_lastname  = $_POST["bill_last_name"];
    $business_address   = $_POST["billing_address1"].', '.$_POST["billing_address2"];
    $business_city      = $_POST["billing_city"];
    $business_state     = $_POST["billing_state"];
    $business_zipcode   = $_POST["billing_zip"];

    $creditcard = $_POST["credit_card_number"];
    $expiration = $_POST["expiration_month"].'-'.$_POST["expiration_year"];
    $expiration_arb = $_POST["expiration_year"].'-'.$_POST["expiration_month"];
    $cvv        = $_POST["security_code"];

    $payment = new AuthnetAIM($apiloginid, $transactionkey, $accounttype);
    $payment->setTransaction($creditcard, $expiration, $aim_amount, $cvv);
    $payment->setParameter("x_duplicate_window", 180);
    $payment->setParameter("x_customer_ip", $_SERVER['REMOTE_ADDR']);
    $payment->setParameter("x_email", $email);
    $payment->setParameter("x_email_customer", FALSE);
    $payment->setParameter("x_first_name", $business_firstname);
    $payment->setParameter("x_last_name", $business_lastname);
    $payment->setParameter("x_address", $business_address);
    $payment->setParameter("x_city", $business_city);
    $payment->setParameter("x_state", $business_state);
    $payment->setParameter("x_zip", $business_zipcode);

    $payment->setParameter("x_description", $product);
    $payment->process();

    if ($payment->isApproved())
    {

        // Get info from Authnet to store in the database
        $approval_code  = $payment->getAuthCode();
        $avs_result     = $payment->getAVSResponse();
        $cvv_result     = $payment->getCVVResponse();
        $transaction_id = $payment->getTransactionID();
 	$ResponseText 	= $payment->getResponseText();

        $_SESSION['approval_code'] =$approval_code;
        $_SESSION['transaction_id'] = $transaction_id;
        $_SESSION['responese_text'] = $ResponseText;
        $_SESSION['aim_amount'] = $aim_amount;
        $_SESSION['arb_amount'] = $amount;
        if($status == 'register'){
            $_SESSION['allow_trial'] = $allow_trial;
        }
        if($trial_amount > 0){
            $_SESSION['arb_amount'] = $trial_amount;
        }

        //////////////////////////////////////////////////////////////////
        require('AuthnetARB.class.php');

        // Set up the subscription. Use the developer account for testing..
        $subscription = new AuthnetARB($apiloginid, $transactionkey, $arb_server);

        if($status == 'upgrade'){
            if($user_subscription_id)
            {
                 // Set subscription id
                $subscription->setParameter('subscrId', $user_subscription_id);

                // Delete the subscription
                $subscription->deleteAccount();
            }
        }

        // Set subscription information
        $subscription->setParameter('amount', $amount);
        $subscription->setParameter('cardNumber', $creditcard);
        $subscription->setParameter('expirationDate', $expiration_arb);
        $subscription->setParameter('firstName', $business_firstname);
        $subscription->setParameter('lastName', $business_lastname);
        $subscription->setParameter('address', $business_address);
        $subscription->setParameter('city', $business_city);
        $subscription->setParameter('state', $business_state);
        $subscription->setParameter('zip', $business_zipcode);
        $subscription->setParameter('customerEmail', $email);

        // Set the billing cycle for every three months
        $subscription->setParameter('interval_length', $subscr_period_duration);
        $subscription->setParameter('interval_unit', "$interval_unit");
        $subscription->setParameter('startDate', $date);
        $subscription->setParameter('totalOccurrences', $totalOccurrences);

        if($allow_trial && $status == 'register')
        {
            if(isset($_SESSION['coupon_code']['amount']))
            {
                // Set up a trial subscription for three months at a reduced price
                $subscription->setParameter('trialOccurrences', 1);
                $subscription->setParameter('trialAmount', $trial_amount);
            }
            else
            {
                // Set up a trial subscription for three months at a reduced price
                $subscription->setParameter('trialOccurrences', 0);
                $subscription->setParameter('trialAmount', 0);
            }
        }
        else
        {
            // Set up a trial subscription for three months at a reduced price
            $subscription->setParameter('trialOccurrences', 0);
            $subscription->setParameter('trialAmount', 0);
        }

        // Create the subscription
        $subscription->createAccount();

        // Check the results of our API call
        if ($subscription->isSuccessful())
        {
            // Get the subscription ID
            $subscription_id = $subscription->getSubscriberID();
            $subscription->getResponse();

            //$dateNextSubscription = strtotime(date("Y-m-d", strtotime($date)) . "+$subscr_period_duration $interval_unit");
            //$_SESSION['dateNextSubscription'] = $dateNextSubscription;
            $_SESSION['subscr_period_duration'] = $subscr_period_duration;
            $_SESSION['interval_unit'] = $interval_unit;
            $_SESSION['subscription_id'] = $subscription_id;

            $redirect = "$CONFIG->wwwroot"."membership/authorizenet_success/$userid/$usertype";
            forward($redirect);
            exit;

        }
        else
        {
            // The subscription was not created!
            $subscription->getResponseCode();
            $result = $subscription->getResponse();

            $_SESSION['result'] = $result;

            $redirect = "$CONFIG->wwwroot"."membership/authorizenet_decline/$userid/$usertype";
            forward($redirect);
            exit;

        }

        //////////////////////////////////////////////////////////////////////////
        //
        // Do stuff with this. Most likely store it in a database.
        // Direct the user to a receipt or something similiar.

        $redirect = "$CONFIG->wwwroot"."membership/authorizenet_success/$userid/$usertype";
        forward($redirect);
        exit;

    }
    else if ($payment->isDeclined())
    {
        // Get reason for the decline from the bank. This always says,
        // "This credit card has been declined". Not very useful.
        $reason = $payment->getResponseText();

        // Politely tell the customer their card was declined
        // and to try a different form of payment.

        $_SESSION['reason'] = $reason;

        $redirect = "$CONFIG->wwwroot"."membership/authorizenet_decline/$userid/$usertype";
        forward($redirect);
        exit;

    }
    else if ($payment->isError())
    {
        // Get the error number so we can reference the Authnet
        // documentation and get an error description.
        $error_number  = $payment->getResponseSubcode();
        $error_message = $payment->getResponseText();

        // OR

        // Capture a detailed error message. No need to refer to the manual
        // with this one as it tells you everything the manual does.
        $full_error_message =  $payment->getResponseMessage();

        $_SESSION['full_error_message'] = $full_error_message;
        $redirect = "$CONFIG->wwwroot"."membership/authorizenet_decline/$userid/$usertype";
        forward($redirect);
        exit;

    }


?>