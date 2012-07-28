<?php
   /**
    * Elgg Membership plugin
    * Membership Coupon page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    global $CONFIG;
    $baseurl = $CONFIG->wwwroot."membership/coupon/";

    $options = array('types'=>	"object",
            'subtypes'	    =>	"mem_coupons",
            'owner_guids'   =>	$_SESSION['user']->guid,
            'count'	    =>	TRUE,
            );
    $count = elgg_get_entities($options);

    $offset = get_input('offset');
    if(!$offset) {
        $offset = 0;
    }
    $limit = 10;

    $nav = elgg_view('navigation/pagination',array(
            'base_url' => $baseurl,
            'offset'  => $offset,
            'count'   => $count,
            'limit'   => $limit
            ));

    $options = array('types'=>	"object",
            'subtypes'	  =>	"mem_coupons",
            'owner_guids' =>	$_SESSION['user']->guid,
            'limit'	  =>	$limit,
            'offset'	  =>	$offset,
            );
    $coupons = elgg_get_entities($options);

    if($coupons){
        $coupon_list = "";
        foreach($coupons as $coupon){
                $amount = $coupon->coupon_amount."%";

                if($coupon->exp_date) {
                    $exp_date = date("d M Y",$coupon->exp_date);
                } else {
                    $exp_date = '';
                }
                $coupon_list .= <<<EOF
                    <tr>
                        <td>{$coupon->coupon_name}</td>
                        <td>{$coupon->coupon_code}</td>
                        <td>{$amount}</td>
                        <td>{$exp_date}</td>
                        <td>{$coupon->coupon_maxuses}</td>
                        <td>
                            <a onclick="mem_edit_coupon({$coupon->guid});" class="mem_coupon_edit"> </a>
                            <a onclick="mem_delete_coupon({$coupon->guid});" class="mem_coupon_delete"> </a>
                        </td>
                    </tr>
EOF;
        }
    }else{
        $coupon_list = '<tr><td colspan="6">'.elgg_echo("mem:no:coupon").'</td></tr>';
    }
    ?>

    <div class="mem_list_coupons">
        <?php echo $nav; ?>
        <table>
            <tr>
                <th><?php echo elgg_echo('mem:coupon:name');?></th>
                <th><?php echo elgg_echo('membership:coupon:code');?></th>
                <th><?php echo elgg_echo('mem:coupon:discount');?></th>
                <th><?php echo elgg_echo('mem:coupon:exp:date');?></th>
                <th><?php echo elgg_echo('mem:coupon:no:of:users');?></th>
                <th><?php echo elgg_echo('mem:coupon:actions');?></th>
            </tr>
            <?php echo $coupon_list; ?>
        </table>
        <?php echo elgg_view('input/securitytoken'); ?>
    </div>