<?php
    /**
    * Elgg Membership plugin
    * Membership register extend page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    global $CONFIG;
    $admin_option = false;
    $plugin_settings= $CONFIG->plugin_settings;
    $allow_payment = $plugin_settings->allow_regpayment;
    // Get cached values
    if (elgg_is_sticky_form('register')) {
	extract(elgg_get_sticky_values('register'));
	elgg_clear_sticky_form('register');
    }
    if($payment_method && $usertype) {
        $style = 'display:block;';
    } else {
        $style = 'display:none;';
    }
    if (($_SESSION['user']->admin) && ($vars['show_admin'])) {
        $admin_option = true;
    }
    $order_by = array('name' => "amount",'direction' => 'ASC','as' => integer);
    $options = array(
            'types' => 'object',
            'subtypes' => 'premium_membership',
            'limit'=>9999,
            'offset'=>0,
            'wheres' => $wheres,
            'order_by_metadata' => $order_by);
    $membership = elgg_get_entities_from_metadata($options);
    $pulldown["Free"]="Free";
    foreach($membership as $val) {
        $pulldown[$val->guid] = $val->title;
    }
    $show_membership = $CONFIG->plugin_settings->show_membership;
    $payment_methods = get_payment_methods_from_settings();
    if(!$payment_method) {
        if(in_array('authorizenet',$payment_methods)) {
            $payment_method = 'authorizenet';
        } else if(in_array('paypal',$payment_methods)) {
            $payment_method = 'paypal';
        }
    }
    if($show_membership == '1') {
    ?>
        <div>
            <label><?php echo elgg_echo('usertype'); ?></label><br />
            <?php
            echo elgg_view('input/dropdown', array(
                    'name' => 'usertype',
                    'class' => "input_usertype",
                    'options_values'=>$pulldown,
                    'js' => "onchange='showPaymentMethod();'",
                    'value'=> "{$usertype}"
                ));
            ?>
        </div>
        <?php 
        // for free user type on 29-12-2011
        if($allow_payment == '1'){?>
	        <div class="input_payment_method" style="<?php echo $style; ?>">
	            <label><b><?php echo elgg_echo('payment:method'); ?></b></label><br />
	            <?php
	            if(!empty($payment_methods)) {
	                echo elgg_view('input/radio_checkout', array(
	                        'name' => 'payment_method',
	                        'options'=>$payment_methods,
	                        'value'=> $payment_method
	                    ));
	            } else {
	                echo elgg_echo('no:payment:methods');
	            }
	            ?>
	        </div>
        <?php }?>
    <?php
    }
    ?>
    <script>
	function showPaymentMethod() {
            var usertype = $(".input_usertype").val();
            if(usertype != '0') {
                $(".input_payment_method").show();
            } else {
                $(".input_payment_method").hide();
            }
	}
    </script>