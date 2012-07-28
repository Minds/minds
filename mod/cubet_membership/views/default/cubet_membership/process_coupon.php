<?php
   /**
    * Elgg Membership plugin
    * Membership Coupon Process page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */

    global $CONFIG;
	
    $options = array('types' =>	"object",
            'subtypes'=>"mem_coupons",
            'count'=>TRUE);
    $count = elgg_get_entities($options);
    if($count > 0){
        $manage_coupon = $CONFIG->wwwroot."membership/manage_coupon";
?>
        <script type="text/javascript">
            function mem_apply_couponcode(){
                var couponcode = $("#couponcode").val();
                if($.trim(couponcode) == ''){
                    $("#coupon_apply_result").html("<?php echo elgg_echo('mem:coupon:empty');?>");
                    $("#couponcode").focus();
                    $("#coupon_apply_result").css({"color":"#9F1313"});
                    $("#coupon_apply_result").show();
                }else{
                    $.post("<?php echo $manage_coupon;?>", {
                            code: couponcode,
                            cat_guid: "<?php echo $vars['cat_guid'];?>",
                            guid: "<?php echo $vars['guid'];?>",
                            amount: "<?php echo $vars['amount'];?>",
                            manage_action: "coupon_process"
                    },
                    function(data){
                        data = data.split(",");
                        switch(data[0]){
                                case 'no_coupon':
                                                $("#coupon_apply_result").html("<?php echo elgg_echo('mem:coupon:not:in:couponcode');?>");
                                                $("#coupon_apply_result").css({"color":"#9F1313"});
                                        break;
                                case 'exp_date':
                                                $("#coupon_apply_result").html("<?php echo elgg_echo('mem:coupon:exp_date');?>"+data[1]);
                                                $("#coupon_apply_result").css({"color":"#9F1313"});
                                        break;
                                case 'not_applied':
                                                $("#coupon_apply_result").html("<?php echo elgg_echo('mem:coupon:not_applied');?>");
                                                $("#coupon_apply_result").css({"color":"#9F1313"});
                                        break;
                                case 'coupon_maxuses':
                                                $("#coupon_apply_result").html("<?php echo elgg_echo('mem:oupon:maxuses:limit');?>");
                                                $("#coupon_apply_result").css({"color":"#9F1313"});
                                        break;
                                case 'coupon_applied':
                                                $("#coupon_apply_result").html("<?php echo elgg_echo('mem:coupon:applied');?>");
                                                $("#coupon_apply_result").css({"color":"#099F10"});
                                                <?php if(!elgg_is_logged_in()) { ?>
                                                    $("#auth_form").attr("action", window.location);
                                                <?php } else { ?>
                                                    $("#auth_form").attr("action", window.location+"<?php echo $vars['guid'];?>"+'/'+"<?php echo $vars['cat_guid'];?>");
                                                <?php } ?>
                                                $("#auth_form").submit();
                                                //$("#couponcode").val('');
                                                //window.location.reload(true);
                                        break;
                                default:
                                        $("#coupon_apply_result").html("Unknown Error");
                                        $("#coupon_apply_result").css({"color":"#9F1313"});
                                break;
                        }
                        $("#coupon_apply_result").show();
                    });
                }
            }
        </script>
        <div style="" class="mem_coupon_back">
            <div id="coupon_apply_result"></div>
            <h4><?php echo elgg_echo('mem:coupon:header');?></h4>
            <p><?php echo elgg_echo('mem:coupon:description');?></p>
            <p>
                <b>Code:</b>
                <input type="text" style="width: 140px;padding:2px;" id="couponcode" name="couponcode"/>
                <?php echo elgg_view('input/button', array('type'=>'button', 'name'=>'apply_code', 'value'=>elgg_echo('mem:coupon:apply'), 'class'=>'elgg-button-submit','js'=>'onclick="mem_apply_couponcode();"'))?>
            </p>
        </div>
<?php 		
    }
?>