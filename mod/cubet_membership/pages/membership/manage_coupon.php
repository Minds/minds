<?php
     /**
    * Elgg Membership plugin
    * Membership manage coupon page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
 	 	
    global $CONFIG;

    $manage_action = get_input('manage_action');

    switch ($manage_action){
        case "add_coupon":
                    $coupon_guid = get_input('coupon_guid');
                    if($coupon_guid){
                            $coupon = get_entity($coupon_guid);
                    }
                    $form_view = elgg_view("cubet_membership/edit_coupon",array('entity'=>$coupon));
                    $out = <<<EOF
                            <div id="coupon_edit_view">
                                <div class="coupon_contents" id="coupon_contents">
                                        {$form_view}
                                </div>
                            </div>
EOF;
                    echo $out;
                break;
            case "cancel":
                    $list_coupon = elgg_view("cubet_membership/list_coupon");
                    $create_btn = elgg_view('input/button', array('type'=>'button', 'name'=>'coupon_btn', 'value'=>elgg_echo('mem_coupon:code:btn'), 'class'=>'elgg-button-submit', 'js'=>'onclick="mem_add_coupon();"'));
                    $out = <<<EOF
                            <div id="coupon_list_view">
                                <div class="list_coupon_membership">
                                    {$list_coupon}
                                </div>
                                {$create_btn}
                                <div class="clear"></div>
                            </div>
EOF;
                    echo $out;
                break;
            case "delete":
                    $coupon_guid = get_input('coupon_guid');
                    if ($coupon = get_entity($coupon_guid)) {
                        if ($coupon->canEdit()) {
                            if (!$coupon->delete()) {
                                    echo elgg_echo("mem:coupon:deletefailed");
                            } else {
                                    echo 1;
                            }
                        } else {
                            echo elgg_echo("mem:coupon:deletefailed");
                        }

                    } else {
                        echo elgg_echo("mem:coupon:deletefailed");
                    }
                break;
            case "coupon_process":
                    $couponcode = get_input('code');
                    $type_guid = get_input('cat_guid');
                    $user_type = get_entity($type_guid);
                    $guid = get_input('guid');
                    $amount = get_input('amount');

                    $coupon = '';
                    $options = array('metadata_name_value_pairs'=>	array('coupon_code' => $couponcode),
                                                 'types' =>	"object",
                                                 'subtypes' =>	"mem_coupons");
                    $coupons = elgg_get_entities_from_metadata($options);
                    if($coupons){
                        $coupon = $coupons[0];
                    }

                    if($coupon){
                        if(check_entity_relationship($coupon->guid, "coupon_membership", $type_guid)){
                            $curren_datetime = mktime (0,0,0,date("n"),date("j"),date("Y"));
                            $exp_date = $coupon->exp_date;
                            if($exp_date && $curren_datetime > $exp_date){
                                echo "exp_date,".date("M d Y",$exp_date);
                                exit;
                            }
                            $coupon_maxuses = $coupon->coupon_maxuses;
                            if($coupon_maxuses != "Unlimited"){
                                $options = array('relationship' => 'coupon_code_user',
                                                             'relationship_guid' => $coupon->guid,
                                                             'types' =>	"user",
                                                             'count' => true);
                                $coupon_uses = elgg_get_entities_from_relationship($options);

                                if(!$coupon_uses){
                                    $coupon_uses = 0;
                                }

                                if(($coupon_maxuses - $coupon_uses) <= 0){
                                    echo "coupon_maxuses";
                                    exit;
                                }

                                $balance = ((int)$amount * (int)$coupon->coupon_amount) / 100;
                                $balance = $amount - $balance;
                                $_SESSION['coupon_code']['guid'] = $coupon->guid;
                                $_SESSION['coupon_code']['code'] = $couponcode;
                                $_SESSION['coupon_code']['original_amount'] = $amount;
                                $_SESSION['coupon_code']['discount'] = $coupon->coupon_amount;
                                $_SESSION['coupon_code']['amount'] = $balance;
                                echo "coupon_applied";
                                exit;
                            }
                        }else{
                            echo "not_applied";
                        }
                    }else{
                        echo "no_coupon";
                    }
                    break;
    }
    exit;
?>