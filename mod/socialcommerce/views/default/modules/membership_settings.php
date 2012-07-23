<?php
	/*****************************************************************************\
	+-----------------------------------------------------------------------------+
	| Elgg Socialcommerce Plugin                                                  |
	| Copyright (c) 2009-20010 Cubet Technologies <socialcommerce@cubettech.com>  |
	| All rights reserved.                                                        |
	+-----------------------------------------------------------------------------+
	| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
	| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
	| AT THE FOLLOWING URL: http://socialcommerce.elgg.in/license.html            |
	|                                                                             |
	| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
	| THIS  SOFTWARE   PROGRAM  AND   ASSOCIATED   DOCUMENTATION    THAT  CUBET   |
	| TECHNOLOGIES (hereinafter referred as "THE AUTHOR") IS FURNISHING OR MAKING |
	| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
	| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
	| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
	| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
	| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
	| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
	| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
	| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
	| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
	| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
	| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
	|                                                                             |
	+-----------------------------------------------------------------------------+
	\*****************************************************************************/
	
	/**
	 * Elgg modules - membership settings view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	global $CONFIG;
	if(elgg_is_active_plugin('cubet_membership')) {
		$settings = $vars['entity'];
		// Get membership types
		//Depricated function replace
		$options = array('types'			=>	"object",
						'subtypes'			=>	"premium_membership",
					);
		$memberships = elgg_get_entities($options);
		//$currency_settings=get_entities("object", "premium_membership", 0);
		// Get Selected membership types	
		if($settings){
			$settings = $settings[0];
			$settings_guid = $settings->guid;
			$selected_buy_methods = $settings->membership_buy_methods;
			$buy_count = count($selected_buy_methods);;
			$selected_sell_methods = $settings->membership_sell_methods;
			$sell_count = count($selected_sell_methods);
		}
		
		$action = $CONFIG->wwwroot.'action/'.$CONFIG->pluginname.'/manage_socialcommerce';
		
		
		//To check or uncheck Free Payment in BUY case
		if($selected_buy_methods) {
			$first_selected_buy_method = $selected_buy_methods[count($selected_buy_methods)-1];
			if($first_selected_buy_method == '0') {
				$selected1 = "checked = \"checked\"";
				//pop element	
				$first_element = array_pop($selected_buy_methods);
			} else {
				$selected1 = "";
			}
		} else {
			if($selected_buy_methods == '0') {
				$selected1 = "checked = \"checked\"";
			} else {
				$selected1 = "";
			}
		}
		//To check or uncheck Free Payment in SELL case 
		if($selected_sell_methods) {
			$first_selected_sell_method = $selected_sell_methods[count($selected_sell_methods)-1];
			if($first_selected_sell_method == '0') {
				$selected2 = "checked = \"checked\"";
				//pop element	
				$first_element = array_pop($selected_sell_methods);
			} else {
				$selected2 = "";
			}
		} else if($selected_sell_methods == '0'){
			if($selected_sell_methods == '0') {
				$selected2 = "checked = \"checked\"";
			} else {
				$selected2 = "";
			}
		}
		if($buy_count == (count($memberships)+1)) {
			$checked = "checked = \"checked\"";
		} else {
			$checked = "";
		}
		if($sell_count == (count($memberships)+1)) {
			$checked1 = "checked = \"checked\"";
		} else {
			$checked1 = "";
		}
		
		//Change CSS for selected memberships
		if($selected1) {
			$class1 = "membership_selected_div"; 
		} else {
			$class1 = "membership_selection_div";
		}
		if($selected2) {
			$class2 = "membership_selected_div"; 
		} else {
			$class2 = "membership_selection_div";
		}
		
		$membership_buy_methods .= '<div id="buy_right">';
		$membership_sell_methods .= '<div id="buy_right">';
		if($memberships){
			foreach ($memberships as $membership){
				
				//For Buy case
				if(is_array($selected_buy_methods)){
					if(!in_array($membership->guid,$selected_buy_methods)){
						$selected3 = "";
					}else{
						$selected3 = "checked = \"checked\"";
					}
				}else{
					if ($membership->guid != $selected_buy_methods) {
			            $selected3 = "";
			        } else {
			            $selected3 = "checked = \"checked\"";
			        }
				}
				if($selected3) {
					$class3 = "membership_selected_div"; 
				} else {
					$class3 = "membership_selection_div";
				}
				$membership_buy_methods .= '<div class='.$class3.'><input type="checkbox" name="membership_buy_method[]" value="'.$membership->guid.'" '.$selected3.'>'.$membership->title.'</input></div>';
				
				// For SELL case
				if(is_array($selected_sell_methods)){
					if(!in_array($membership->guid,$selected_sell_methods)){
						$selected4 = "";
					}else{
						$selected4 = "checked = \"checked\"";
					}
				}else{
					if ($membership->guid != $selected_sell_methods) {
			            $selected4 = "";
			        } else {
			            $selected4 = "checked = \"checked\"";
			        }
				}
				if($selected4) {
					$class4 = "membership_selected_div"; 
				} else {
					$class4 = "membership_selection_div";
				}
				$membership_sell_methods .= '<div class='.$class4.'><input type="checkbox" name="membership_sell_method[]" value="'.$membership->guid.'" '.$selected4.'>'.$membership->title.'</input></div>';
			}
			
		}
		$membership_buy_methods .= '<div class='.$class1.'><input type="checkbox" name="membership_buy_method[]" value=0 '.$selected1.'>Free</input></div>';
		$membership_buy_methods .= "</div>";
		$membership_sell_methods .= '<div class='.$class2.'><input type="checkbox" name="membership_sell_method[]" value=0 '.$selected2.'>Free</input></div>';
		$membership_sell_methods .= '</div>';
	
?>
<div class="basic">
	<form name='membership_form' id='membership_form' method="post" action="<?php echo $action; ?>">
		<div class="checkout_title"><B><?php echo elgg_echo('membership:settings'); ?></B></div>
		<div class="membership_body">
			<div class="buy_div">
				<div id='buy_left'>
					<input type="checkbox" name="buy" id="set_buy"  onClick ="checkAll('membership_form','buy','membership_buy_method[]');" value="1" <?php echo $checked; ?>><b><?php echo elgg_echo('Buy');?></b></input><br/>
				</div>
				<?php echo $membership_buy_methods; ?>
			</div>
			<div class="buy_div">
				<div id='buy_left'>
					<input type="checkbox" name="sell" id="set_sell"  onClick ="checkAll('membership_form','sell','membership_sell_method[]');" value="2" <?php echo $checked1; ?>><b><?php echo elgg_echo('Sell');?></b></input>
				</div>
				<?php echo $membership_sell_methods; ?>
			</div>	
		</div>
		<p>
			<div style="margin-left:10px;">
				<?php echo elgg_view('input/submit', array('name' => 'btn_save', 'value' => elgg_echo('save')));?>
				<input type="hidden" name="manage_action" value="membership">
				<input type="hidden" name="guid" value="<?php echo $settings_guid; ?>">
				<?php echo elgg_view('input/securitytoken'); ?>
			</div>
		</p>
	</form>
</div>
<?php } else {?>
	<div class="basic_membership">
	<?php echo (elgg_echo('enable:membership'));?>
	</div>	
<?php }?>
<script>
function checkAll(id,parent,name) {
	if($('input:checkbox[name='+parent+']').is(':checked')) {
		$('input[name='+name+']').attr('checked',true);
	} else {
		$('input[name='+name+']').attr('checked', false);
	}
}
</script>