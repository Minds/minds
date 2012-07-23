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
	 * Elgg address - add
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	global $CONFIG;
	
	$tax_name = get_input('tax_name');
	$based_on = get_input('based_on');
	$tax_country = get_input('tax_country');
	$tax_rate = get_input('tax_rate');
	$taxrate_country_id = get_input('taxrate_country_id');
	
	//Depricated function replace
	$options = array(	'metadata_name_value_pairs'	=>	array('tax_country' => $tax_country),
						'types'				=>	"object",
						'subtypes'			=>	"addtax_country",
						'limit'				=>	1,
					);
	$tax_entity = elgg_get_entities_from_metadata($options);
	//$tax_entity = get_entities_from_metadata('tax_country',$tax_country,'object','addtax_country','',1);
	foreach($tax_entity as $tax_entitys)
	{
		$tax_guid = $tax_entitys->guid;
	}

	$addtax_country = new ElggObject($tax_guid);
    $addtax_country->subtype = "addtax_country";
    $addtax_country->container_guid = $_SESSION['user']->guid;
    $addtax_country->owner_guid = $_SESSION['user']->guid;
    $addtax_country->access_id = 2;
    $addtax_country->taxrate_name = $tax_name;
    $addtax_country->based_on = $based_on;
    $addtax_country->tax_country = $tax_country;
    $addtax_country->taxrate = $tax_rate;
    $addtax_country->status = 1;
    $result = $addtax_country->save();
    if($result){
    	$_SESSION['getcontry_id'] = $result;
    	system_message(elgg_echo('tax:countrytax:add:success'));
    	forward($_SERVER['HTTP_REFERER']);
    }
    else
    {
    	register_error(elgg_echo('tax:countrytax:add:fail'));
    }
	
	
	?>