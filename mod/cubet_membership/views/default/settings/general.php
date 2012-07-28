<?php 
   /**
    * Elgg Membership plugin
    * Membership General Settings page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    global $CONFIG;
    $plugin_settings = $CONFIG->plugin_settings;
    if($plugin_settings) {
        $settings_guid = $plugin_settings->guid;
    }
    $m_permissions = $plugin_settings->permission;
    if(!is_array($m_permissions)){
        if(empty($m_permissions)){
            $m_permissions = array();
        }else{
            $m_permissions = array($m_permissions);
        }
    }
    $upgrade_status = ($plugin_settings->upgrade_allow)?$plugin_settings->upgrade_allow:'0';
    $receive_notifications = ($plugin_settings->receive_notifications)?$plugin_settings->receive_notifications:'0';
    $show_membership = $plugin_settings->show_membership;
    if(!$show_membership && $show_membership!= '0') {
        $show_membership = '0';
    }
    // for free user type on 29-12-2011
    $allow_payment =($plugin_settings->allow_regpayment)?$plugin_settings->allow_regpayment:'0';
    if($allow_payment == '0') {
        $style2 = 'display:none';
    } else {
        $style2='display:block';
    }
    
    $show_checkouts = $plugin_settings->show_checkout;
    $allow_trial = ($plugin_settings->allow_trial)?$plugin_settings->allow_trial:'0';
    if($allow_trial == '0') {
        $style='display:none';
    } else {
        $style='display:block';
    }
    $trial_units_pulldown = array('D'=>'Days','W'=>'Weeks','M'=>'Months');
    $trial_period_units = ($plugin_settings->trial_period_units)?$plugin_settings->trial_period_units:'D';
    $trial_duration_pulldown = $subscr_duration_pulldown= array();
    $subscr_period_units = ($plugin_settings->subscr_period_units)?$plugin_settings->subscr_period_units:'D';
    /* Function to get duration */
    function get_duration($unit,$pulldown) {
        if($unit == 'D') {
            // Unit is D - Period is 7-90
            for($i=7;$i<=90;$i++) {
                $pulldown[$i] = $i;
            }
        } else if($unit == 'W'){
            // Unit is W - Period is 1-52
            for($i=1;$i<=52;$i++) {
                $pulldown[$i] = $i;
            }
        } else if($unit == 'M'){
            // Unit is M - Period is 1-12
            for($i=1;$i<=12;$i++) {
                $pulldown[$i] = $i;
            }
        } else if($unit == 'Y'){
            // Unit is Y - Period is 1-5
            for($i=1;$i<=5;$i++) {
                $pulldown[$i] = $i;
            }
        }
        return $pulldown;
    }
    $trial_duration_pulldown = get_duration($trial_period_units,$trial_duration_pulldown);
    $trial_period_duration = $plugin_settings->trial_period_duration;
    $trial_amount = ($plugin_settings->trial_amount)?$plugin_settings->trial_amount:0;
    $allow_recurring = ($plugin_settings->allow_recurring)?$plugin_settings->allow_recurring:'0';
    if($allow_recurring == '0') {
        $style1 = 'display:none';
    } else {
        $style1='display:block';
    }
    $recurring_times = ($plugin_settings->recurring_times)?$plugin_settings->recurring_times:1;
    $recurring_times_pulldown = array();
    $recurring_times_pulldown[1] = 'Unlimited';
    for($i=2;$i<=5;$i++) {
        $recurring_times_pulldown[$i] = $i;
    }

    $trial_occurrences = ($plugin_settings->trial_occurrences)?$plugin_settings->trial_occurrences:1;
    $trial_occurrences_pulldown = array();
    for($i=1;$i<=4;$i++) {
        $trial_occurrences_pulldown[$i] = $i;
    }

    $subscr_duration_pulldown = get_duration($subscr_period_units,$subscr_duration_pulldown);
    $subscr_period_duration = $plugin_settings->subscr_period_duration;
?>

<script>
    function permissionManage(pThis){
        var groupval =$(pThis).val();
        if ($(pThis).is(':checked')) {
            $(pThis).parents("#"+groupval).find("#perm_"+groupval).show();
            $(pThis).parents("#"+groupval).find("#perm_"+groupval).filter('input[name="m_permission"]').attr('checked', true);
        }else{
            $(pThis).parents("#"+groupval).find("#perm_"+groupval).hide();
            $(pThis).parents("#"+groupval).find("#perm_"+groupval).filter('input[name="m_permission"]').attr('checked', false);
        }
    }
</script>

<div class="contentWrapper">
    <form id='membership_settings' action="<?php echo $vars['url']; ?>action/membership/settings" method="post">
        <?php echo elgg_echo('allow:upgrade');?><br/>
        <?php echo elgg_view('input/radio_membership',array(
                'name' => 'params[upgrade_allow]',
                'class'=>'allow_upgrade',
                'options'=>array('Yes'=>'1','No'=>'0'),
                'value' => $upgrade_status));
        ?><br/><br/>

        <?php echo elgg_echo('show:membership');?><br/>
        <?php echo elgg_view('input/radio_membership',array(
                'name' => 'params[show_membership]',
                'class'=>'show_membership',
                'options'=>array('Yes'=>'1','No'=>'0'),
                'value' => $show_membership));
        ?><br/><br/>
        
        <!-- payment details part starts -->
        <?php echo elgg_echo('allow:payment_registration');?><br/>
        <?php echo elgg_view('input/radio_membership',array(
                'name' => 'params[allow_regpayment]',
                'class'=>'allow_regpayment',
                'options'=>array('Yes'=>'1','No'=>'0'),
                'value' => $allow_payment,
        		'js' => 'onclick="allowRegpayment();"'));
        ?><br/><br/>
        
		<div id="payment_details" style=<?php echo $style2;?>>
		
			<?php echo elgg_echo('show:checkout');?><br/>
	        <?php echo elgg_view('input/checkboxes',array(
	                'name' => 'params[show_checkout]',
	                'class'=>'show_checkouts',
	                'options'=>array(
	                        'Authorize.net'=>'authorizenet',
	                        'Paypal'=>'paypal'
	                        ),
	                'value' => $show_checkouts,
	                ));
	        ?><br/>
	        
	        <b><?php echo elgg_echo('subscr:details');?></b>
		    <div id='subscr_details'>
		            <div class='block_class'>
		                <div class='left_class'>
		                    <?php
		                        echo elgg_echo('subscr:period:units');
		                    ?>
		                </div>
		                <div style='float:left;'>
		                    <?php
		                        echo elgg_view('input/dropdown' , array(
		                                'name' => 'params[subscr_period_units]',
		                                "options_values"=>$trial_units_pulldown,
		                                'value' => $subscr_period_units,
		                                'class' => "subscr_period_units_textarea",
		                                'js' => "onchange='get_subscr_duration();'"));
		                    ?>
		                </div><div class='clear'></div>
		            </div>
		            <br/>
		            <div style='display:block;padding-top:8px;'>
		                <?php
		                    echo "<div class='left_class'>".elgg_echo('subscr:period:duration')."</div>";
		                ?>
		                <div id='subscr_period_duration' style='float:left'>
		                    <?php
		                        echo elgg_view('input/dropdown' , array(
		                                'name' => 'params[subscr_period_duration]',
		                                "options_values"=>$subscr_duration_pulldown,
		                                'value' => $subscr_period_duration,));
		                    ?>
		                </div>
		                <div id='subscr_period' style='float:left'></div>
		            </div><div class='clear'></div>
		     </div><br/>
		       
		     <?php echo elgg_echo('allow:trial');?><br/>
		     <?php echo elgg_view('input/radio_membership',array(
		                'name' => 'params[allow_trial]',
		                'class'=>'allow_trial',
		                'options'=>array('Yes'=>'1','No'=>'0'),
		                'value' => $allow_trial,
		                'js' => 'onclick="toggleTrial();"'));
		     ?><br/><br/>
		     <?php // To show only if the trial is checked ?>
		
		     <div id='trial_details'  class="trial_authorize" style=<?php echo $style;?>>
		            <div class='block_class'>
		                <div class='left_class'>
		                    <?php
		                        echo elgg_echo('trial:period:units');
		                    ?>
		                </div>
		                    <?php
		                        echo elgg_view('input/dropdown' , array(
		                                'name' => 'params[trial_period_units]',
		                                "options_values"=>$trial_units_pulldown,
		                                'value' => $trial_period_units,
		                                'class' => "trial_period_units_textarea",
		                                'js' => "onchange='get_trial_duration();'"));
		                    ?>
		            </div><div class='clear'></div>
		            <div>
		                <?php
		                        echo "<div class='left_class'>".elgg_echo('trial:period:duration')."</div>";
		                ?>
		                <div id='trial_period_duration'>
		                    <?php
		                        echo elgg_view('input/dropdown' , array(
		                                'name' => 'params[trial_period_duration]',
		                                "options_values"=>$trial_duration_pulldown,
		                                'value' => $trial_period_duration,));
		                    ?>
		                </div>
		                <div id='trial_period'></div>
		            </div><div class='clear'></div>
		           <?php
		                echo elgg_view('input/hidden',array(
		                        'name'=>'params[trial_amount]',
		                        'value'=>0,
		                        'class'=>'small_textbox'));
		            ?>
		            <div class='clear'></div>
		      </div>
		      <br/>
		      
		       <?php echo elgg_echo('allow:recurring');?><br/>
		       <?php echo elgg_view('input/radio_membership',array(
		                'name' => 'params[allow_recurring]',
		                'class'=>'allow_recurring',
		                'options'=>array('Yes'=>'1','No'=>'0'),
		                'value' => $allow_recurring,
		                'js' => 'onclick="toggleRecurring();"'));
		       ?><br/><br/>
		       
		       <div  id='recurring_details' style=<?php echo $style1;?>>
		            <div class='block_class'>
		                <div class='left_class'>
		                    <?php
		                        echo elgg_echo('recurring:times');
		                    ?>
		                </div>
		                <?php
		                    echo elgg_view('input/dropdown',array(
		                            'name'=>'params[recurring_times]',
		                            'value'=>$recurring_times,
		                            "options_values"=>$recurring_times_pulldown));
		                ?>
		            </div>
		        </div><br/>
		        
		        <?php echo elgg_echo('receive:notifications:membership');?><br/>
		        <?php echo elgg_view('input/radio_membership',array(
		                'name' => 'params[receive_notifications]',
		                'options'=>array('Yes'=>'1','No'=>'0'),
		                'value' => $receive_notifications));
		       ?><br/><br/>
	        
		</div>
               
        <!-- payment details part ends -->
        <?php
        if(elgg_is_active_plugin('blog') || elgg_is_active_plugin('file') || elgg_is_active_plugin('groups') || elgg_is_active_plugin('pages') || elgg_is_active_plugin('thewire') || elgg_is_active_plugin('bookmarks') || elgg_is_active_plugin('messages')){
            echo "<p>".elgg_echo('membership:permissions:free').'</p>';
        }
        if(isset($CONFIG->membershipPermissions) && !empty($CONFIG->membershipPermissions)){
            echo "<ul class='permission''>";
            foreach($CONFIG->membershipPermissions as $group=>$permissions){
            $checked = "";
            $sub_class = "perm_hide";
                if(in_array($group,  $m_permissions)) {
                    $checked = "checked='checked'";
                    $sub_class = "perm_show";
                }
            ?>
                <li id="<?php echo $group; ?>" >
                    <div><input onclick="return permissionManage(this);" type="checkbox" <?php echo $checked; ?> name="params[permission][]" value="<?php echo $group; ?>" /><?php echo elgg_echo('membership:group:'.$group); ?></div>
                    <div class="sub_permission <?php echo $sub_class ?>" id="perm_<?php echo $group;?>">
                    <?php foreach($permissions as $permission) { ?>
                        <div>
                            <input type="checkbox" <?php if(in_array($permission['permission'], $m_permissions)) { echo "checked='checked'"; } ?> name="params[permission][]" value="<?php echo $permission['permission']; ?>" /> <?php echo elgg_echo('membership:permission:'.$permission['permission']); ?>
                        </div>
                    <?php } ?>
                    </div>
                </li>
            <?php
            }
            echo "</ul>";
        }
        ?>
        <p>
            <input type="hidden" value="<?php echo $settings_guid;?>" name="guid"/>
            <?php
            echo elgg_view('input/securitytoken');
            echo elgg_view('input/submit', array('value' => elgg_echo('save')));
            ?>
        </p>
    </form>
</div>
<script>
	function toggleTrial() {
            var trial_status = $(".allow_trial:checked").val();
            var payment_method = $(".show_checkout:checked").val();
            if(trial_status == '1') {
                $(".trial_authorize").show();
            } else {
                $(".trial_authorize").hide();
            }
	}
	function get_trial_duration() {
            var trial_period_units = $(".trial_period_units_textarea").val();
            $.ajax({
            type: "POST",
            url: "<?php echo $CONFIG->wwwroot.'mod/cubet_membership/pages/membership/trialDurations.php'; ?>",
            data: "trial_period_units=" + trial_period_units,
            success: function(result) {

                $("#trial_period").html(result);
                $("#trial_period_duration").hide();
                $("#trial_period").show();
            }
        });
	}
	function get_subscr_duration() {
            var subscr_period_units = $(".subscr_period_units_textarea").val();
            $.ajax({
                type: "POST",
                url: "<?php echo $CONFIG->wwwroot.'mod/cubet_membership/pages/membership/subscrDurations.php'; ?>",
                data: "subscr_period_units=" + subscr_period_units,
                success: function(result) {

                    $("#subscr_period").html(result);
                    $("#subscr_period_duration").hide();
                    $("#subscr_period").show();
                }
            });
	}
	function toggleRecurring() {
            var recurring_status = $(".allow_recurring:checked").val();
            if(recurring_status == '1') {
                $("#recurring_details").show();
            } else {
                $("#recurring_details").hide();
            }
	}
	function allowRegpayment() {
		var payment_status = $(".allow_regpayment:checked").val();
		if (payment_status == '1') {
			  $("#payment_details").show();
		}else{
			  $("#payment_details").hide();
		}			 
	}
</script>	