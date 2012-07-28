<?php
    /**
    * Elgg Membership plugin
    * Membership edit coupon page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */

    admin_gatekeeper();
    global $CONFIG;

    // Get variables
    $coupon_code = get_input('coupon_code');
    $coupon_name = get_input('coupon_name');
    $coupon_amount = get_input('coupon_amount');
    $exp_date = get_input('exp_date');
    $coupon_maxuses = get_input('coupon_maxuses');
    $coupon_memberships = get_input('coupon_memberships');
    if(!is_array($coupon_memberships)) {
        $coupon_memberships = array($coupon_memberships);
    }
    $guid = (int) get_input('coupon_guid');

    $result = false;
    //Validation
    if(empty($coupon_code)) {
        $error_field = elgg_echo("mem:coupon:code");
    }
    if(empty($coupon_name)) {
        $error_field .= $error_field ? ','.elgg_echo("mem:coupon:name") : elgg_echo("mem:coupon:name");
    }
    if(empty($coupon_amount)) {
        $error_field .= $error_field ? ','.elgg_echo("mem:coupon:discount") : elgg_echo("mem:coupon:discount");
    }

    if(!empty($error_field)) {
        $vars['coupon']['coupon_code'] = $coupon_code;
        $vars['coupon']['coupon_name'] = $coupon_name;
        $vars['coupon']['coupon_amount'] = $coupon_amount;
        $vars['coupon']['exp_date'] = $exp_date;
        $vars['coupon']['coupon_maxuses'] = $coupon_maxuses;

        register_error(sprintf(elgg_echo("mem:coupon:validation:null"),$error_field));
        $redirect = $CONFIG->wwwroot . "membership/coupon/";
    }else{
        $coupon =  new ElggObject($guid);
        $coupon->subtype = 'mem_coupons';
        $coupon->access_id = 2;

        $coupon->coupon_code = $coupon_code;
        $coupon->coupon_name = $coupon_name;
        $coupon->coupon_amount = $coupon_amount;
        if($exp_date){
            $exp_date = strtotime($exp_date);
            $coupon->exp_date = $exp_date;
        }
        $coupon->coupon_maxuses = $coupon_maxuses;
        $coupon->coupon_memberships = $coupon_memberships;
        $result = $coupon->save();

        if ($result){
            if($coupon_memberships){
                remove_entity_relationships($result, 'coupon_membership');
                foreach($coupon_memberships as $coupon_membership){
                    add_entity_relationship($result,'coupon_membership',$coupon_membership);
                }
            }
            system_message(elgg_echo("mem:coupon:saved"));
            unset($_SESSION['coupon']);
        }else{
            register_error(elgg_echo("mem:coupon:addfailed"));
        }

        $container_user = get_entity($container_guid);
        $redirect = $CONFIG->wwwroot . "membership/coupon/";
    }
    forward($redirect);
?>