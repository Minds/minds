<?php 
   /**
    * Elgg Membership plugin
    * Membership Payment page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */

    global $CONFIG;
    access_show_hidden_entities(true);
    if(elgg_is_logged_in()) {
        $status = 'upgrade';
        $username = elgg_get_logged_in_user_entity()->username;
    } else {
        $status = 'register';
        $username = 'manage';
    }

    $cat_guid=get_input('cat_guid');
    if(!$cat_guid){
        register_error(sprintf(elgg_echo('You have not selected any category'),$entity->title));
        forward($CONFIG->wwwroot."membership/confirm");
    }

    $guid=get_input("guid");
    $new_user = get_entity($guid);

    $email = $CONFIG->plugin_settings->paypal_email;
    $type = $CONFIG->plugin_settings->payment_type;
    if((!$email) || (!$type)) {
        system_message(sprintf(elgg_echo("payment:settings:error")));
        forward($_SERVER['HTTP_REFERER']);
    }
    if($type == 'paypal'){
        $url = "https://www.paypal.com/cgi-bin/webscr";
    }else { //  if($type == 'sandbox'){
        $url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
    }

    $entity = get_entity($cat_guid);
    $amount = $entity->amount;
    $allow_trial = $CONFIG->plugin_settings->allow_trial ? $CONFIG->plugin_settings->allow_trial : '0';
    $allow_recurring = $CONFIG->plugin_settings->allow_recurring ? $CONFIG->plugin_settings->allow_recurring : '0';
    $trial_period_units =  $CONFIG->plugin_settings->trial_period_units ?  $CONFIG->plugin_settings->trial_period_units : 'D';
    $trial_period_duration =  $CONFIG->plugin_settings->trial_period_duration ?  $CONFIG->plugin_settings->trial_period_duration :1;
    $trial_amount =  $CONFIG->plugin_settings->trial_amount ?  $CONFIG->plugin_settings->trial_amount: 0;
    $recurring_times =  $CONFIG->plugin_settings->recurring_times ?  $CONFIG->plugin_settings->recurring_times : 1;
    $subscr_period_units =  $CONFIG->plugin_settings->subscr_period_units ?  $CONFIG->plugin_settings->subscr_period_units : 'D';
    $subscr_period_duration =  $CONFIG->plugin_settings->subscr_period_duration ?  $CONFIG->plugin_settings->subscr_period_duration : 1;
    /////////////////// Amount Calculation /////////////////////////////////
    //Take last transaction detail that is active to calculate upgrade amount
    /*$options = array('types'=>'object',
            'subtypes'=>'mem_transaction',
            'owner_guids'=>$guid,
            'limit'=>1,
            'metadata_name_value_pairs' => array('payment_type' => 'paypal','tran_status'=>'active', 'active_status'=>'active'),
            'metadata_case_sensitive' => false
            );
    $transactions = elgg_get_entities_from_metadata($options);
    if($transactions) {
        foreach($transactions as $transaction) {echo $transaction->guid,'@@'.$transaction->subscription_id.'@@'.$transaction->allow_recurring.'<br/>';
            // If the user has a transaction then take last transaction duration to calculate the amount to upgrade
            $tran_subscr_period_units = $transaction->interval_unit ? $transaction->interval_unit :  $CONFIG->plugin_settings->subscr_period_units;
            $tran_subscr_period_duration = $transaction->subscr_period_duration ? $transaction->subscr_period_duration:  $CONFIG->plugin_settings->subscr_period_duration;
        }
    } else {
        $tran_subscr_period_units = $subscr_period_units;
        $tran_subscr_period_duration = $subscr_period_duration;
    }
    
    if($new_user->amount) {
        if($status == 'upgrade' && $transactions) {// Only if upgrade and also if transaction is active
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
    if(empty($tran_amount)) {
        $tran_amount = $amount;
        $remaining_duration = $subscr_period_duration;
    }*/
    /////////////////// Amount Calculation /////////////////////////////////
    if($entity->title == $new_user->user_type){
        register_error(sprintf(elgg_echo('membership:alreadyregistered'),$entity->title));
        forward($CONFIG->wwwroot."membership/confirm");
    }
?>
<div class="contentWrapper"> 
    <?php 
         /*if($status == 'upgrade' && $transactions && strtolower($new_user->user_type) !='free') {
            echo elgg_view('cubet_membership/process_coupon', array('cat_guid'=>$cat_guid, 'guid'=>$guid, 'amount'=>$tran_amount));
         } else {*/
             echo elgg_view('cubet_membership/process_coupon', array('cat_guid'=>$cat_guid, 'guid'=>$guid, 'amount'=>$entity->amount));
         //}
    ?>
    <form action="<?php echo $url;?>" method="post" name="frm">
        <p><b><?php echo elgg_echo("Username: "); ?></b>
        <?php echo $new_user->username; ?></p>

        <p><b><?php echo elgg_echo("Email: "); ?></b>
        <?php echo $new_user->email; ?></p>
        <p>
            <b><?php echo elgg_echo("Amount: ") ?></b>
            <?php
                if(isset($_SESSION['coupon_code']['original_amount'])){
                    /*if($status == 'upgrade' && $transactions && strtolower($new_user->user_type) !='free' && $allow_recurring) {
                        echo "$".$amount." USD"."<br/><br/>";
                        echo "First Payment : $".$_SESSION['coupon_code']['amount']." USD";
                    } else {*/
                        echo "<span style='margin-right:10px;text-decoration:line-through;'>$".$amount." USD</span> $".$_SESSION['coupon_code']['amount']." USD";
                    //}
                } else {
                    /*if($status == 'upgrade' && $transactions && strtolower($new_user->user_type) !='free') {
                        if($allow_recurring) {
                            echo "$".$amount." USD"."<br/><br/>";
                            echo "First Payment : $".$tran_amount." USD";
                        } else {
                            echo "$".$amount." USD";
                        }
                    } else {*/
                         echo "$".$amount." USD";
                    //}
                }
            ?>
        </p>
        <p>
            <b><?php echo elgg_echo("Description: "); ?></b>
            <?php echo $entity->description; ?>
        </p>
        <?php
            if($status == 'register') {
                if($allow_trial) {
                    if($trial_period_units == 'D'){
                        $trial_units = 'Days';
                        $start_time  = mktime(0, 0, 0, date("m")  , date("d")+$trial_period_duration, date("Y"));
                        $start = date("d/m/Y", $start_time);
                    } else if($trial_period_units == 'W'){
                        $trial_units = 'Weeks';
                        $trial_period = $trial_period_duration * 7;
                        $start_time  = mktime(0, 0, 0, date("m")  , date("d")+$trial_period, date("Y"));
                        $start = date("d/m/Y", $start_time);
                    } else if($trial_period_units == 'M'){
                        $trial_units = 'Months';
                        $start_time  = mktime(0, 0, 0, date("m")+$trial_period_duration , date("d"), date("Y"));
                        $start = date("d/m/Y", $start_time);
                    } else if($trial_period_units == 'Y'){
                        $trial_units = 'Years';
                        $start_time  = mktime(0, 0, 0, date("m")  , date("d"), date("Y")+$trial_period_duration);
                        $start = date("d/m/Y", $start_time);
                    }
                    ?>
                    <p><b><?php echo elgg_echo("trial:period: ") ?></b>
                    <?php echo $trial_period_duration.' '.$trial_units; ?></p>
                    <p><b><?php echo elgg_echo("trial:amount: ") ?></b>
                    <?php echo "$".$trial_amount." USD"; ?></p>
                    <p><b><?php echo elgg_echo("start:date:") ?></b>
                    <?php echo $start ?></p>
                <?php
                }
            }
        ?>
        <input type="hidden" name="cmd" value="_xclick-subscriptions">
        <input type="hidden" name="business" value="<?php echo $email;?>">
        <input type="hidden" name="item_number" value="premium user">
        <input type="hidden" name="item_name" value="<?php echo $entity->title."Membership";?>">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="custom" value="<?php echo $new_user->username; ?>">
        <input type="hidden" value="1" name="no_shipping">
        <?php
        $trial1 = false;
        if($status == 'register') {
            // Trial period only for registration
            if($allow_trial) {?>
                <input type="hidden" name="a1" value="<?php echo $trial_amount?>">
                <input type="hidden" name="p1" value="<?php echo $trial_period_duration;?>">
                <input type="hidden" name="t1" value="<?php echo $trial_period_units;?>">
        <?php
                $trial1 = true;
            }
        }
        
        $onetime_payment = true;
        $allow_recur = $allow_recurring;
        if(isset($_SESSION['coupon_code']['amount'])){
            if($allow_recurring){
                if($recurring_times > 1) {
                    $recurring_times = $recurring_times - 1;
                }
                if($recurring_times == 1){
                    $allow_recurring = 0;
                }

                $onetime_payment = false;
                if($trial1) { // Register with trial period and coupon
            ?>
                    <input type="hidden" name="a2" value="<?php echo $_SESSION['coupon_code']['amount']?>">
                    <input type="hidden" name="p2" value="<?php echo $subscr_period_duration;?>">
                    <input type="hidden" name="t2" value="<?php echo $subscr_period_units;?>">
            <?php
                } else { // Register or upgrade with coupon only

                    /*if($status == 'upgrade' && $transactions && $allow_recur && strtolower($new_user->user_type) !='free') {
                        // If there is previous transaction then set the previous available amount as trial
            ?>
                        <input type="hidden" name="a1" value="<?php echo $_SESSION['coupon_code']['amount']?>">
                        <input type="hidden" name="p1" value="<?php echo $subscr_period_duration;?>">
                        <input type="hidden" name="t1" value="<?php echo $subscr_period_units;?>">

            <?php
                    } else  {*/
            ?>
                        <input type="hidden" name="a1" value="<?php echo $_SESSION['coupon_code']['amount']?>">
                        <input type="hidden" name="p1" value="<?php echo $subscr_period_duration;?>">
                        <input type="hidden" name="t1" value="<?php echo $subscr_period_units;?>">
            <?php
                    //}
                }
            }
        } /*else {
            // Add previous transaction as trial in without coupon case in a recurring case
            if($status == 'upgrade' && $transactions && $allow_recur && strtolower($new_user->user_type) !='free') { ?>
                <input type="hidden" name="a1" value="<?php echo $tran_amount?>">
                <input type="hidden" name="p1" value="<?php echo $remaining_duration;?>">
                <input type="hidden" name="t1" value="<?php echo $tran_subscr_period_units;?>">
            <?php
                if($recurring_times > 1){
                    $recurring_times = $recurring_times - 1;
                }
                // if recurring times is 2, then only need to recur 1 time other than trial
                if($recurring_times == 1){
                    $allow_recurring = 0;
                }
            }

        }*/

        if($onetime_payment && isset($_SESSION['coupon_code']['amount'])){// Non-recurrence case
        ?>
            <input type="hidden" name="a3" value="<?php echo $_SESSION['coupon_code']['amount'];?>">
        <?php
        } /*else if($status == 'upgrade' && $transactions && !$allow_recur) {
          // if it's a non-recurring case, just take the reduced amount
        ?>
             <input type="hidden" name="a3" value="<?php echo $tran_amount;?>">
        <?php }*/ else { ?>
             <input type="hidden" name="a3" value="<?php echo $amount;?>">
        <?php } ?>

        <input type="hidden" name="p3" value="<?php echo $subscr_period_duration;?>">
        <input type="hidden" name="t3" value="<?php echo $subscr_period_units;?>">
        <input type="hidden" name="src" value="<?php echo $allow_recurring; ?>">
        
        <!-- input type="hidden" name="sra" value="1"-->
        <?php
        if($allow_recurring) {
            if($recurring_times > 1) {
        ?>
                <input type="hidden" name="srt" value="<?php echo $recurring_times;?>">
        <?php
            }
        }
        ?>

        <input type="hidden" name="return" value="<?php echo $CONFIG->wwwroot."membership/success_payment/cart_success/$guid/$cat_guid";?>">
        <input type="hidden" name="cancel_return" value="<?php echo $CONFIG->wwwroot."membership/success_payment/cart_cancel/$guid/$cat_guid";?>">
        <input type="hidden" name="notify_url" value="<?php echo $CONFIG->wwwroot."membership/makepayment/$guid/$cat_guid/$status/".$_SESSION['coupon_code']['guid']; ?>">
        <p>
            <?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('membership:confirm'))); ?>&nbsp;
            <input class="elgg-button elgg-button-submit" type="button" name="cancel" value="Cancel" onclick="return check('<?php echo $guid;?>');">
        </p>
    </form>
<div style="clear:both;"></div>
</div>
<script type="text/javascript">
    function check(guid){
        $.post("<?php echo $CONFIG->wwwroot.'mod/cubet_membership/actions/membership/return.php';?>", { var_param: guid },
            function(data){
                window.location="<?php echo $CONFIG->wwwroot;?>";
        });
    }
</script>
<?php access_show_hidden_entities(false);?>
