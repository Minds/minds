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
	 * Elgg address - individual view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	
	$address = $vars['entity'];
	
	$address_guid = $address->getGUID();
	$title = $address->title;
	$firstname = $address->first_name;
	$lastname = $address->last_name;
	$address_line_1 = $address->address_line_1;
	$address_line_2 = $address->address_line_2;
	$city = $address->city;
	$state = $address->state;
	$country = $address->country;
	$pincode = $address->pincode;
	$mobileno = $address->mobileno;
	$phoneno = $address->phoneno;
	
	$owner = $vars['entity']->getOwnerEntity();
	$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
	
	if (elgg_get_context() == "confirm" or elgg_get_context() == "order") {
?>
		<div class="address_listing_info">
			<table>
				<tr>
					<th>&nbsp; Billing Address</th>
					<th width="50"></td>
					<th>&nbsp; Delivery Address</th>
				</tr>
				<tr>
					<td>
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
					</td>
					<td></td>
					<td>
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
					</td>
				</tr>
			</table>
			<?php
				$address_exp = explode("\n",$address_1);
			?>	
			<input type="hidden" name="address1" value="<?php echo $address_exp[0] ?>">	
			<input type="hidden" name="address2" value="<?php echo $address_exp[1] ?>">	
			<input type="hidden" name="city" value="<?php echo $city ?>">	
			<input type="hidden" name="country" value="IN">	
			<input type="hidden" name="first_name" value="<?php echo $firstname ?>">	
			<input type="hidden" name="last_name" value="<?php echo $lastname ?>">	
			</div>
<?php
	}else{
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
				<p>
					<a href="<?php echo $vars['url']; ?><?php echo $CONFIG->pluginname; ?>/edit_address/<?php echo $address->getGUID(); ?>"><?php echo elgg_echo('edit:address'); ?></a>&nbsp; 		
				</p>
			</div>
<?php
		}
	}
?>