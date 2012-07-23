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
	 * Elgg view - list address
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 

	global $CONFIG;
	
	$addresses = $vars['entity'];
	$display = $vars['display'];
	$selected = $vars['selected'];
	$type = $vars['type'];
	
	if($addresses){
		$i = 0;
		$address_list = "";
		if($display == 'list_with_action'){
			$address_list_js = <<<EOF
				<script>
					var time_out;
					
					function view_action_details(guid){
						var myaddress_show = $("#myaddress_show").val();
						if(myaddress_show != guid){
							if(myaddress_show){
								$("#myaddress_action_"+$("#myaddress_show").val()).fadeOut('slow');
								$("#myaddress_show").val('');
							}
								
							$("#myaddress_action_"+guid).fadeIn('slow');
							$("#myaddress_show").val(guid);
						}
					}
					function process_view_action_details(guid){
						if(time_out)
							clearTimeout(time_out);
						time_out = setTimeout ("view_action_details('"+guid+"')", 200 );
					}
					function hide_action_details(){
						var myaddress_show = $("#myaddress_show").val();
						if(myaddress_show){
							$("#myaddress_action_"+$("#myaddress_show").val()).fadeOut('slow');
							$("#myaddress_show").val('');	
						}
					}
					function process_hide_action_details(guid){
						if(time_out)
							clearTimeout(time_out);
						time_out = setTimeout ("hide_action_details('"+guid+"')", 400 );
					}
				</script>
EOF;
			echo $address_list_js;
		}
		foreach ($addresses as $address){
			$address_guid = $address->getGUID();
			$firstname = $address->first_name;
			$lastname = $address->last_name;
			$address_line_1 = $address->address_line_1;
			$address_line_2 = $address->address_line_2;
			$city = $address->city;
			$state = $address->state;
			$country = get_name_by_fields('iso3',$address->country);
			$pincode = $address->pincode;
			$mobileno = $address->mobileno;
			$phoneno = $address->phoneno;
			
			if($display == "list"){
				if($selected){
					if($selected == $address_guid)
						$checked = "checked";
					else 
						$checked = "";
				}else {
					if($i == 0)
						$checked = "checked";
					else 	
						$checked = "";
				}
				$i++;
				$address_list = <<<EOF
					<input style="margin-bottom:10px;" {$checked} name="{$type}_address_guid" type="radio" value="{$address_guid}"/>
					{$firstname} {$lastname}, {$address_line_1}, {$address_line_2}, {$city}, {$state}, {$pincode}, {$country}
					<br>
EOF;
				echo $address_list;
			}else if($display == 'list_with_action'){
				$address_list = <<<EOF
					<div onmouseover="process_view_action_details($address_guid)" onmouseout="process_hide_action_details($address_guid)" style="margin:1px 0 10px;">
						<div class="myaccount_address">
							<div class="myaccount_address_action" id="myaddress_action_{$address_guid}">
								<a onclick="delete_myaddress({$address_guid});" class="myaddress_delete" style="float:right;"> </a>
								<a onclick="edit_myaddress({$address_guid});" class="myaddress_edit" style="float:right;"> </a>
							</div>
							<div style="padding:5px 10px;">
								{$firstname} {$lastname}, {$address_line_1}, {$address_line_2}, {$city}, {$state}, {$pincode}, {$country}
							</div>
							<input type="hidden" name="myaddress_show" id="myaddress_show" value="">
						</div>
					</div>
EOF;
				echo $address_list;
			}else{
				
				$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
?>
				<div class="address_listing_info">
						<table cellpadding="10">
							<tr><?php echo "<td>".elgg_echo("first:name")."</td><td> : </td><td>".$firstname."</td>" ?></tr>
							<tr><?php echo "<td>".elgg_echo("last:name")."</td><td> : </td><td>".$lastname."</td>" ?></tr>
							<tr><?php echo "<td>".elgg_echo("address:line:1")."</td><td> : </td><td>".nl2br($address_line_1)."</td>" ?></tr>
							<tr><?php echo "<td>".elgg_echo("address:line:2")."</td><td> : </td><td>".nl2br($address_line_2)."</td>" ?></tr>
							<tr><?php echo "<td>".elgg_echo("city")."</td><td> : </td><td>".$city."</td>" ?></tr>
							<tr><?php echo "<td>".elgg_echo("state")."</td><td> : </td><td>".$state."</td>" ?></tr>
							<tr><?php echo "<td>".elgg_echo("country")."</td><td> : </td><td>".$country."</td>" ?></tr>
							<tr><?php echo "<td>".elgg_echo("pincode")."</td><td> : </td><td>".$pincode."</td>" ?></tr>
							<tr><?php echo "<td>".elgg_echo("mob:no")."</td><td> : </td><td>".$mobileno."</td>" ?></tr>
							<?PHP if($phoneno > 0){?>
							<tr><?php echo "<td class='address_left'>".elgg_echo("phone:no")."</td><td class='address_sep'> : </td><td class='address_right'>".$phoneno."</td>" ?></tr>
							<?php } ?>
						</table>
					</div>
			
<?php
				if ($address->canEdit()) {
?>
					<div class="storesrepo_controls">
						<a onclick="edit_address(<?php echo $address->getGUID(); ?>)" href="javascript: void(0);"><?php echo elgg_echo('edit:address'); ?></a>&nbsp; 		
					</div>
<?php
				}
			}
		}
		echo elgg_view('input/securitytoken');
	}
?>