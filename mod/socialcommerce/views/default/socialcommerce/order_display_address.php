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
	 * Elgg view - order display address
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	
	$order = $vars['entity'];
	$type = $vars['type'];
	if($order){
		$name = $type.'_first_name';
		$firstname = trim($order->$name);
		
		$name = $type.'_last_name';
		$lastname = trim($order->$name);
		
		if($firstname != '' || $lastname != '')
			echo "<div>".$firstname." ".$lastname."</div>";
			
		$name = $type.'_address_line_1';
		$address_line_1 = trim($order->$name);
		if($address_line_1 != '')
			echo "<div>".$address_line_1."</div>";
			
		$name = $type.'_address_line_2';
		$address_line_2 = trim($order->$name);
		if($address_line_2 != '')
			echo "<div>".$address_line_2."</div>";
			
		$name = $type.'_city';
		$city = trim($order->$name);
		if($city != '')
			echo "<div>".$city."</div>";
			
		$name = $type.'_state';
		$state = trim($order->$name);
		if($state != '')
			echo "<div>".$state."</div>";
			
		$name = $type.'_country';
		$country = trim(get_name_by_fields('iso3',$order->$name));
		if($country != '')
			echo "<div>".$country."</div>";
			
		$name = $type.'_pincode';
		$pincode = trim($order->$name);
		if($pincode != '')
			echo "<div>".$pincode."</div>";
			
		$name = $type.'_mobileno';
		$mobileno = trim($order->$name);
		if($mobileno != '')
			echo "<div>".$mobileno."</div>";
			
		$name = $type.'_phoneno';
		$phoneno = trim($order->$name);
		if($phoneno != '')
			echo "<div>".$phoneno."</div>";
	}
?>