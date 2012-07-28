<?php 
   /**
    * Elgg Membership plugin
    * Membership confirm page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    global $CONFIG;
    $email = $CONFIG->plugin_settings->paypal_email;
    $type = $CONFIG->plugin_settings->payment_type;

    $plugin_settings = $CONFIG->plugin_settings;
    
    if($type=='paypal') {
        $url="https://www.paypal.com/cgi-bin/webscr";
    } else if($type=='sandbox') {
        $url="https://www.sandbox.paypal.com/cgi-bin/webscr";
    }
    $guid=get_input("guid");
    if(!$guid) {
        $guid=$_SESSION['user']->getGUID();
    }
    access_show_hidden_entities(true);
    $new_user = get_entity($guid);
    $payment_method = get_payment_methods_from_settings();
    $context = elgg_get_context();
    elgg_set_context('upgrade_membership');
    if($new_user) {
        if(!isset($new_user->amount) || empty($new_user->amount)){
            $new_user->amount = 0;
            $new_user->save();
        }
    }
    elgg_set_context($context);
    $joins = array("JOIN {$CONFIG->dbprefix}metadata md on e.guid = md.entity_guid","JOIN {$CONFIG->dbprefix}metastrings ms_n on md.name_id = ms_n.id","JOIN {$CONFIG->dbprefix}metastrings ms_v on md.value_id = ms_v.id");
    $wheres = array("(ms_n.string = 'amount' AND ms_v.string >= {$new_user->amount})");
    $options = array(
            'types' => 'object',
            'subtypes' => 'premium_membership',
            'limit'=>9999,
            'offset'=>0,
            'joins' => $joins,
            'wheres' => $wheres,
            'order_by' => '(ms_v.string + 0) ASC');
    $membership = elgg_get_entities($options);
    $upgrade_allow = $CONFIG->plugin_settings->upgrade_allow;
    $action = '';
    $style = '';
    // Get last transaction payment method to check whether the a payment used by user or not
    if($new_user->subscription_id) {
        $options = array('types'=>'object',
                'subtypes'=>'mem_transaction',
                'owner_guids'=>$guid,
                'limit'=>1,
                'metadata_name_value_pairs' => array('subscription_id'=>$new_user->subscription_id),
                'metadata_case_sensitive' => false);
        $transactions = elgg_get_entities_from_metadata($options);
        if($transactions) {
            foreach($transactions as $transaction) {
                // To check whether last payment method currently exists
                if(in_array($transaction->payment_type,$payment_method)) {
                    $style= 'display:none;';
                    if($transaction->payment_type == 'paypal') {
                        $action = $CONFIG->wwwroot."membership/payment/";
                    } else {
                        $action = $CONFIG->wwwroot."membership/authorizenet/";
                    }
                }
            }
        } 
    }
    if(empty($style)){
        $style= 'display:block;';
    }
    if(in_array('authorizenet',$payment_method)) {
        $payment_mode = 'authorizenet';
        if(empty($action)){
            $action = $CONFIG->wwwroot."membership/authorizenet/";
        }
    } else if(in_array('paypal',$payment_method)) {
        $payment_mode = 'paypal';
        if(empty($action)){
            $action = $CONFIG->wwwroot."membership/payment/";
        }
    }
       
    if($upgrade_allow == '1') {
?>
        <div class="contentWrapper">
            <form action="<?php echo $action;?>" method="post" name="frm" id="payment_form">
                <div style='display:block;'>
                    <div style='width:100px;float:left'><b><?php echo elgg_echo("Username :"); ?></b></div>
                    <div style='float:left'><?php echo $new_user->username; ?></div>
                </div>
                <div class="clear"></div>
                <div style='display:block;'>
                    <div style='width:100px;float:left'><b><?php echo elgg_echo("Email :"); ?></b></div>
                    <div style='float:left'><?php echo $new_user->email; ?></div>
                </div>
                <div class="clear"></div>
                <br/>

                <?php
                 foreach($membership as $val){

                        if($val->description)
                            $desc="($".$val->amount.")";
                        else
                            $desc='';
                        ?>
                        <input type="radio" name="cat_guid" value="<?php echo $val->guid;?>" <?php if($new_user->user_type==$val->title) {echo "checked";}?>><?php echo $val->title.$desc  ;?>
                        <br>
                        <?php
                }
                ?>
                <div class="clear"></div>
                <br/>
                <div class="input_payment_method" style="<?php echo $style; ?>">
                    <label><b><?php echo elgg_echo('payment:method'); ?></b></label><br />
                    <?php
                    if(!empty($payment_method)) {
                        echo elgg_view('input/radio_membership', array(
                                'name' => 'payment_method',
                                'options'=>$payment_method,
                                'class' => 'checkout_method',
                                'value'=> $payment_mode,
                                'js' => "onclick='changeFormAction();'",
                            ));
                    } else {
                        echo elgg_echo('no:payment:methods');
                    }
                    ?>
                </div>
                 <div class="clear"></div>
                <br/>
                <input type="hidden" name="guid" value="<?php echo $guid;?>">
                <p><?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('membership:confirm')));
                ?></p>
            </form>
        </div>
<?php } else {?>
        <div class="contentWrapper">
        <?php
            echo elgg_echo('no:permission:upgrade');
        ?>
        </div>
<?php } ?>
<script>
    function changeFormAction() {
        var payment_method = $(".checkout_method:checked").val();
        if(payment_method == 'authorizenet') {
           $("#payment_form").attr("action", "<?php echo $CONFIG->wwwroot."membership/authorizenet/" ?>");
        } else {
           $("#payment_form").attr("action", "<?php echo $CONFIG->wwwroot."membership/payment/" ?>");
        } 
    }
</script>
<?php
access_show_hidden_entities(false);
?>