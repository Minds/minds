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
	 * Elgg address - edit form
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$ajax = $vars['ajax'];
	$first = $vars['first'];
	$address_post_url = "{$CONFIG->checkout_base_url}action/{$action}";
	$address_reload_url = "{$CONFIG->checkout_base_url}{$CONFIG->pluginname}/view_address/gust";
	$selected_state = "";
	$selected_country = "USA";

	
	// Just in case we have some cached details
	if (isset($_SESSION['address'])) {
		$firstname = $_SESSION['address']['first_name'];
		$lastname = $_SESSION['address']['last_name'];
		$email = $_SESSION['address']['email'];
		$address_line_1 = $_SESSION['address']['address_line_1'];
		$address_line_2 = $_SESSION['address']['address_line_2'];
		$city = $_SESSION['address']['city'];
		$selected_state = $_SESSION['address']['state'];
		$selected_country = $_SESSION['address']['country'];
		$pincode = $_SESSION['address']['pincode'];
		$mobileno = $_SESSION['address']['mobileno'];
		$phoneno = $_SESSION['address']['phoneno'];		
	}
	
	
	$type = 'billing';
	if($CONFIG->country){
		$country_list = '<select onkeyup="find_state(\''.$type.'\')"  onkeydown="find_state(\''.$type.'\')" onchange="find_state(\''.$type.'\')" name="currency_country" id="'.$type.'_country" class="elgg-input-text1">';
		foreach ($CONFIG->country as $country){
			if($selected_country == $country['iso3']){
				$selected = "selected";
			}else{
				$selected = "";
			}
			$country_list .= "<option value='".$country['iso3']."' ".$selected.">".$country['name']."</option>";
		}
		$country_list .= "</select>";
		if($selected_country){
			$states = get_state_by_fields('iso3',$selected_country);
			if(!empty($states)){
				$state_list = '<select name="state" id="'.$type.'_state" class="elgg-input-text1">';
				foreach ($states as $state){
					if($selected_state == $state->name){
						$selected = "selected";
					}else{
						$selected = "";
					}
					$state_list .= "<option value='" . $state->name . "' " . $selected . ">" . $state->name . "</option>";
				}
				$state_list .= '</select>';
			}else{
				$state_list = '<input class="elgg-input-text1" type="text" value="'.$selected_state.'" id="'.$type.'_state" name="state"/>';
			}
		}
	}else {
		$country_list = '<input class="elgg-input-text1" type="text" value="'.$selected_country.'" id="'.$type.'_country" name="country"/>';
	}

	
?>
<script language="javascript" type="text/javascript">
var time_out;
function find_state_process(type){
	var country = $('#'+type+'_country').val();
	$('#'+type+'_state_list').load("<?php echo $address_reload_url;?>", {type:type,todo:'load_state',country:country});
}
function find_state(type){
	if(time_out)
		clearTimeout(time_out);
	time_out = setTimeout ("find_state_process('"+type+"')", 600 );
}

</script>
<div class="address_details">
					<span class="addres_labels"><span style="color:red">*</span><?php echo elgg_echo('first:name')?>:</span>
					<br /> 
					<input type="text" name="first_name" value="<?php echo $firstname;?>"></input>
					<br />
					<span class="addres_labels"><span style="color:red">*</span><?php echo elgg_echo('last:name')?>:</span>
					<br /> 
					<input type="text" name="last_name" value="<?php echo $lastname;?>"></input>
					<br />
					<span class="addres_labels"><span style="color:red">*</span><?php echo elgg_echo('email')?>:</span>
					<br /> 
					<input type="text" name="address_email" value="<?php echo $email;?>"></input>
					<br />
					<span class="addres_labels"><span style="color:red">*</span><?php echo elgg_echo('address:line:1')?>:</span>
					<br /> 
					<input type="text" name="add_line1" value="<?php echo $address_line_1;?>"></input>
					<br />
					<span class="addres_labels"><?php echo elgg_echo('address:line:2')?>:</span>
					<br /> 
					<input type="text" name="add_line2" value="<?php echo $address_line_2;?>"></input>
					<br />
					<span class="addres_labels"><span style="color:red">*</span><?php echo elgg_echo('city')?>:</span>
					<br /> 
					<input type="text" name="city" value="<?php echo $city;?>"></input>
					<br />
					<span class="addres_labels"><span style="color:red">*</span><?php echo elgg_echo('country')?>:</span>
					<br /> 
						<?php echo $country_list;?>
					<br />
					<span class="addres_labels"><span style="color:red">*</span><?php echo elgg_echo('state')?>:</span>
					<br /> 
					<div id="billing_state_list">
						<?php echo $state_list;?>
					</div>
					<br />
					<span class="addres_labels"><span style="color:red">*</span><?php echo elgg_echo('pincode')?>:</span>
					<br /> 
					<input type="text" name="pincode" value="<?php echo $pincode;?>"></input>
					<br />
					<span class="addres_labels"><?php echo elgg_echo('mob:no')?>:</span>
					<br /> 
					<input type="text" name="mobile" value="<?php echo $mobileno;?>"></input>
					<br />
					<span class="addres_labels"><?php echo elgg_echo('phone:no')?>:</span>
					<br /> 
					<input type="text" name="phone" value="<?php echo $phoneno;?>"></input>
				</div>