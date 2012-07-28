<?php
    /**
    * Elgg Membership plugin
    * Authorize.net payment page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */

    global $CONFIG;

    elgg_set_ignore_access(true);
    
    $cat_guid=get_input('cat_guid');
    $guid=get_input("guid");
    
    elgg_pop_breadcrumb();
    
    $entity = get_entity($cat_guid);
    $new_user = get_entity($guid);
    $amount = $entity->amount;
    
    if(elgg_is_logged_in()) {
        if($entity->title == $new_user->user_type){
            register_error(sprintf(elgg_echo('membership:alreadyregistered'),$entity->title));
            forward($CONFIG->wwwroot."membership/confirm");
        }
        if(!$cat_guid){
            register_error(sprintf(elgg_echo('not selected any category'),$entity->title));
            forward($CONFIG->wwwroot."membership/confirm");
        }
    }else {
        if(!$cat_guid){
            if($guid = get_input('guid') > 0){
                $guid=get_input("guid");
                register_error(elgg_echo('not selected any category'));
                forward($CONFIG->wwwroot."mod/cubet_membership/confirm.php?guid=".$guid);
            }else{
                register_error(sprintf(elgg_echo('you must:register')));
                forward($CONFIG->wwwroot."pg/register/");
            }
        }
    }

    if(elgg_is_logged_in()) {
        $status = 'upgrade';
    } else {
        $status = 'register';
    }
    $subscr_period_units = $CONFIG->plugin_settings->subscr_period_units;
    $subscr_period_duration = $CONFIG->plugin_settings->subscr_period_duration;
    /////////////////// Amount Calculation /////////////////////////////////
    //Take last transaction detail that is active to calculate upgrade amount
    /*if($status == 'upgrade' && strtolower($new_user->user_type)!= 'free') {
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
        if($new_user->amount) {
            if($transactions) {// Only if transaction is active
                $total_duration = abs(strtotime(date('F j Y')) - $new_user->update_date);
                $duration_days = floor($total_duration/(60*60*24));// Difference between the dates
                if($duration_days) {
                    if($tran_subscr_period_units == 'D') {
                        $duration = round($duration_days / $tran_subscr_period_duration,2);
                        $remaining_duration =  $tran_subscr_period_duration - $duration_days;
                    } else if($tran_subscr_period_units == 'W') {
                        $duration = round($duration_days / ($tran_subscr_period_duration * 7),2);
                        $remaining_duration = floor((($tran_subscr_period_duration * 7) - $duration_days) / 7);
                    } else if($tran_subscr_period_units == 'M') {
                        $duration = round($duration_days / ($tran_subscr_period_duration * 30),2);
                        $remaining_duration =  floor((($tran_subscr_period_duration * 30) - $duration_days) / 30);
                    } else if($tran_subscr_period_units == 'Y') {
                        // 1 Year = 365.2425 Days
                        $duration = round($duration_days / ($tran_subscr_period_duration * 365.2425),2);
                        $remaining_duration =  floor((($tran_subscr_period_duration * 365.2425) - $duration_days) / 365.2425);
                    }
                    $tran_amount = round($amount - ($new_user->amount / $duration),2);
                } else { // if upgrades on the same day
                    $tran_amount = $amount - $new_user->amount;
                    $remaining_duration = $tran_subscr_period_duration;
                }
            } 
        } 
    }
    if(empty($tran_amount)) {
        $tran_amount = $amount;
        $remaining_duration = $subscr_period_duration;
    }*/
    /////////////////// Amount Calculation End/////////////////////////////////
    // set the title
    $title = elgg_echo("confirm:payment");
    
    $member_detail = "<b>".elgg_echo("membership details: ")."</b><br>";
    
    $member_fee = "<b>".elgg_echo("amount:")."</b><br>";
    if(isset($_SESSION['coupon_code']['original_amount'])){
        $member_fee .= "<span style='margin-right:10px;text-decoration:line-through;'>$".$entity->amount." USD</span> $".$_SESSION['coupon_code']['amount']." USD";
    } else {
         $member_fee .= "$<span id='authorize_coupon_applied'>".$entity->amount."<span> USD";
        /*if($tran_amount == $entity->amount) {
            $member_fee .= "$".$entity->amount." USD";
        } else {
            $member_fee .= "<span style='margin-right:10px;text-decoration:line-through;'>$".$entity->amount." USD</span> $".$tran_amount." USD";
        }*/
    }
    $member_title = "<b>".elgg_echo("title:")."</b><br>";
    $member_title .= $entity->title;
    $member_des = "<b>".elgg_echo("description:")."</b><br>";
    $member_des .= $entity->description;

    if($status == 'register') {

        $allow_trial = $CONFIG->plugin_settings->allow_trial;
        $trial_period_units = $CONFIG->plugin_settings->trial_period_units;
        $trial_period_duration = $CONFIG->plugin_settings->trial_period_duration;
        $trial_amount = $CONFIG->plugin_settings->trial_amount;

        $allow_recurring = $CONFIG->plugin_settings->allow_recurring;
        $recurring_times = $CONFIG->plugin_settings->recurring_times;
        
        //Trial period section
        if($allow_trial) {
            if($trial_period_units == 'D'){
                    $trial_units = 'Day(s)';
                    $start_time  = mktime(0, 0, 0, date("m")  , date("d")+$trial_period_duration, date("Y"));
                    $start = date("d/m/Y", $start_time);
            } else if($trial_period_units == 'W'){
                    $trial_units = 'Week(s)';
                    $trial_period = $trial_period_duration * 7;
                    $start_time  = mktime(0, 0, 0, date("m")  , date("d")+$trial_period, date("Y"));
                    $start = date("d/m/Y", $start_time);
            } else if($trial_period_units == 'M'){
                    $trial_units = 'Month(s)';
                    $start_time  = mktime(0, 0, 0, date("m")+$trial_period_duration , date("d"), date("Y"));
                    $start = date("d/m/Y", $start_time);
            } else if($trial_period_units == 'Y'){
                    $trial_units = 'Year(s)';
                    $start_time  = mktime(0, 0, 0, date("m")  , date("d"), date("Y")+$trial_period_duration);
                    $start = date("d/m/Y", $start_time);
            }
            
            $trial_note = elgg_echo("trial:note");
            $member_trial = "<p><b>". elgg_echo("trial:period: ") ."</b>";
            $member_trial .= $trial_period_duration." ".$trial_units."</p>";
            $member_trial .= "<p><b>". elgg_echo("trial:amount: ") ."</b>";
            $member_trial .= "$".$trial_amount." USD"."</p>";
            $member_trial .= "<p><b>". elgg_echo("start:date:") ."</b>";
            $member_trial .= $start."</p>";
            $member_trial .= "<p><a title='{$trial_note}'>".elgg_echo("note")."</a></p>";
        }
        
        //Subscription period section
        if($subscr_period_units == 'D'){
            $subscr_period = 'Days';
        } else if($subscr_period_units == 'W'){
            $subscr_period = 'Weeks';
        } else if($subscr_period_units == 'M'){
            $subscr_period = 'Months';
        } else if($subscr_period_units == 'Y'){
            $subscr_period = 'Years';
        }
        $member_trial .= "<p><b>". elgg_echo('subscr:period') ."</b>";
        $member_trial .= $subscr_period_duration." ".$subscr_period."</p>";

        //Recurring section
        if($allow_recurring) {
            $member_trial .= "<p><b>". elgg_echo('recurr:times') ."</b>";
            if($recurring_times == '1')
            {
                $member_trial .= "Unlimited </p>";
            }
            else
            {
                $member_trial .= $recurring_times."</p>";
            }
        }
        
    }
    
    if($status == 'upgrade'){
        
        if(isset($_SESSION['coupon_code']['original_amount'])){
            $new_amount = $discount_amount = $_SESSION['coupon_code']['amount'];
            //$new_amount = $discount_amount - $old_amount;
        }
        else {
            $new_amount = $amount;
        }

        $member_pay = "<b>".elgg_echo("You pay: ")."</b><br>";
        $member_pay .= "$".$new_amount." USD";

        $allow_recurring = $CONFIG->plugin_settings->allow_recurring;
        $recurring_times = $CONFIG->plugin_settings->recurring_times;

        //Subscription period section
        if($subscr_period_units == 'D'){
            $subscr_period = 'Days';
        } else if($subscr_period_units == 'W'){
            $subscr_period = 'Weeks';
        } else if($subscr_period_units == 'M'){
            $subscr_period = 'Months';
        } else if($subscr_period_units == 'Y'){
            $subscr_period = 'Years';
        }
        $member_trial .= "<p><b>". elgg_echo('subscr:period') ."</b>";
        $member_trial .= $subscr_period_duration." ".$subscr_period."</p>";

        //Recurring section
        if($allow_recurring) {
            $member_trial .= "<p><b>". elgg_echo('recurr:times') ."</b>";
            if($recurring_times == '1')
            {
                $member_trial .= "Unlimited </p>";
            }
            else
            {
                $member_trial .= $recurring_times."</p>";
            }
        }
    }
                        
    $area1 = <<<EOF
            <div style="border: 1px solid #4690D6; background: #EEEEEE; padding:5px;">
                    <p>{$member_detail}</p>
                    <p>{$member_fee}</p>
                    <p>{$member_pay}</p>
                    <p>{$member_title}</p>
                    <p>{$member_des}</p>
                    <p>{$member_trial}</p>
            </div>
EOF;


    // Add the form to this section
    $area2 .= elgg_view("cubet_membership/authorizenet",array('guid'=>$guid,'cat_guid'=>$cat_guid,'transaction_amt'=>$tran_amount));
    
    // Create a layout
    $body = elgg_view_layout('content', array(
            'filter' => '',
            'content' => $area2,
            'title' => $title,
            'sidebar' => $area1,
    ));
    
    // Finally draw the page
    echo elgg_view_page($title, $body);
