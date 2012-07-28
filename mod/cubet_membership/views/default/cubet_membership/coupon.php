<?php
   /**
    * Elgg Membership plugin
    * Membership coupon page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    $order_by = array('name' => "amount",'direction' => 'ASC','as' => integer);
    $options = array(
            'types' => 'object',
            'subtypes' => 'premium_membership',
            'limit'=>9999,
            'offset'=>0,
            'wheres' => $wheres,
            'order_by_metadata' => $order_by);
    $membership = elgg_get_entities_from_metadata($options);

?>
<div class="contentWrapper">
    <div class="mem_coupons">
        <div style="margin:10px 0;"><?php echo elgg_echo('mem_coupon:code:desc'); ?></div>
        <div id="mem_coupcode_container">
            <div id="mem_coupon_list_view">
                <div class="list_coupon_membership">
                    <?php echo elgg_view("cubet_membership/list_coupon");?>
                </div>
                <?php echo elgg_view('input/button', array('type'=>'button', 'name'=>'coupon_btn','class'=>'elgg-button-submit', 'value'=>elgg_echo('mem_coupon:code:btn'), 'js'=>'onclick="mem_add_coupon();"'))?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>