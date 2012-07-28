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
	 
    global $CONFIG;

    if (isset($vars['entity'])) {
        $entity = get_entity($vars['entity']->guid);
        $action = "cubet_membership/edit_coupon";
        $coupon_code = $entity->coupon_code;
        $coupon_name = $entity->coupon_name;
        $coupon_amount = $entity->coupon_amount;
        //$coupon_type = $entity->coupon_type;
        $exp_date = $entity->exp_date;
        $exp_date = date("d M Y",$exp_date);
        $coupon_maxuses = $entity->coupon_maxuses;
        if($coupon_maxuses == 'Unlimited') {
            $coupon_maxuses = 0;
        }
    } else {
        $action = "cubet_membership/add_coupon";
        $coupon_code = GenerateMemCouponCode();
        $coupon_name = '';
        $coupon_amount = '';
        //$coupon_type = '';
        $exp_date = '';
        $coupon_maxuses = '';
        $access_id = 2;
    }

    // Just in case we have some cached details
    if (isset($vars['coupon'])) {
        $coupon_code = $vars['coupon']['coupon_code'];
        $coupon_name = $vars['coupon']['coupon_name'];
        $coupon_amount = $vars['coupon']['$entity->coupon_amount'];
        //$coupon_type = $vars['coupon']['$entity->coupon_type'];
        $exp_date = $vars['coupon']['exp_date'];
        $coupon_maxuses = $vars['coupon']['coupon_maxuses'];
    }
    $action = $vars['url']."action/".$action;
?>
<div>
    <script>
        function mem_validate_coupon_form(){
            var coupon_code = $("#coupon_code").val();
            var coupon_name = $("#coupon_name").val();
            var coupon_amount = $("#coupon_amount").val();
            if(coupon_code == ''){
                alert("<?php echo elgg_echo('mem:coupon:validate:error');?>");
                $("#coupon_code").focus();
                return false;
            }
            if(coupon_name == ''){
                alert("<?php echo elgg_echo('mem:coupon:name:validate:error');?>");
                $("#coupon_name").focus();
                return false;
            }
            if(coupon_amount == ''){
                alert("<?php echo elgg_echo('mem:coupon:discount:validate:error');?>");
                $("#coupon_amount").focus();
                return false;
            }
            $('#mem_create_coupon_form').submit();
        }
        // To add calendar field
        if ($('.elgg-input-date').length) {
            elgg.ui.initDatePicker();
        }
    </script>
    <form id="mem_create_coupon_form" action="<?php echo $action; ?>" enctype="multipart/form-data" method="post">
            <table class="mem_edit_coupon">
                    <tr>
                            <td class="label">
                                    <span class="required">*</span> <?php echo elgg_echo('membership:coupon:code');?>:
                            </td>
                            <td>
                                    <input type="text" value="<?php echo $coupon_code; ?>" name="coupon_code" id="coupon_code" class="input-text"/>
                            </td>
                    </tr>
                    <tr>
                            <td class="label">
                                    <span class="required">*</span> <?php echo elgg_echo('mem:coupon:name');?>:
                            </td>
                            <td>
                                    <input type="text" value="<?php echo $coupon_name; ?>" name="coupon_name" id="coupon_name" class="input-text"/>
                            </td>
                    </tr>
                    <tr>
                            <td class="label">
                                    <span class="required">*</span> <?php echo elgg_echo('mem:coupon:discount');?>:
                            </td>
                            <td>
                                    <input type="text" class="input-text" style="width:50px" value="<?php echo $coupon_amount; ?>" name="coupon_amount" id="coupon_amount"/> %
                                    <!--<select class="input-text" style="width: 50px;" name="coupon_type" id="coupon_type">
                                            <option value="0" <?php if($coupon_type == 0){echo 'selected="selected"';}?> >%</option>
                                            <option value="1" <?php if($coupon_type == 1){echo 'selected="selected"';}?>><?php echo $CONFIG->default_currency_sign; ?></option>
                                    </select>-->
                            </td>
                    </tr>
                    <tr>
                            <td class="label">
                                       <?php echo elgg_echo('mem:coupon:exp:date');?>:
                            </td>
                            <td>
                                    <div class="date-outer">
                                            <?php echo elgg_view('input/date',array('name'=>'exp_date','value'=>$exp_date, 'class'=>"input-text"));?>
                                    </div>
                            </td>
                    </tr>
                    <tr>
                            <td class="label">
                                       <?php echo elgg_echo('mem:coupon:no:of:users');?>:
                            </td>
                            <td>
                                    <input type="text" class="input-text" style="width:50px;" value="<?php echo $coupon_maxuses; ?>" name="coupon_maxuses" id="coupon_maxuses"/>
                            </td>
                    </tr>
                    <tr>
                            <td class="label">
                                       <?php echo elgg_echo('mem:coupon:applay:products');?>:
                            </td>
                            <td>
                                    <div class="category_select_box" style="float:left">
                                            <ul>
                                                    <?php
                                                    $limit = 9999;
                                                    $options = array(
                                                                            'types' => 'object',
                                                                            'subtypes' => 'premium_membership',
                                                                            'limit'=>$limit);
                                                    $memberships = elgg_get_entities_from_metadata($options);
                                                    if($memberships){
                                                            foreach($memberships as $membership){
                                                                    $coupon_memberships = $entity->coupon_memberships;
                                                                    if(!is_array($coupon_memberships))
                                                                            $coupon_memberships = array($coupon_memberships);
                                                                    if(in_array($membership->guid,$coupon_memberships))
                                                                            $checked = 'checked="checked"';
                                                                    else
                                                                            $checked = '';
                                                    ?>
                                                                    <li>
                                                                            <input <?php echo $checked; ?> type="checkbox" value="<?php echo $membership->guid; ?>" name="coupon_memberships[]"/><?php echo $membership->title; ?>
                                                                    </li>
                                                    <?php
                                                            }
                                                    }
                                                    ?>
                                            </ul>
                                    </div>
                            </td>
                    </tr>
                    <tr>
                            <td colspan="2">
                                    <?php if(isset($vars['entity'])){?>
                                            <input type="hidden" name="coupon_guid" value="<?php echo $vars['entity']->guid; ?>" />
                                    <?php }?>
                                    <table>
                                            <tr>
                                                    <td>
                                                            <?php echo elgg_view('input/button', array('type'=>'button', 'name'=>'coupon_btn', 'class'=>'elgg-button-submit', 'value'=>elgg_echo('save'), 'js'=>'onclick="mem_validate_coupon_form();"'))?>
                                                    </td>
                                                    <td>&nbsp;</td>
                                                    <td>
                                                            <?php echo elgg_view('input/button', array('type'=>'button', 'name'=>'coupon_btn', 'class'=>'elgg-button-submit', 'value'=>elgg_echo('Cancel'), 'js'=>'onclick="mem_coupon_cancel();"'))?>
                                                    </td>
                                            </tr>
                                    </table>
                            </td>
                    </tr>
            </table>
            <?php echo elgg_view('input/securitytoken'); ?>
    </form>
</div>