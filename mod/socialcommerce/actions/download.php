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
	 * Elgg product - download action
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	
	global $CONFIG;
	// Get the guid
	$order_guid = get_input("product_guid");
	
	$order = get_entity($order_guid);
	$subtype = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes where id='{$order->subtype}'");
	
	if(((elgg_is_logged_in() && $order->owner_guid == $_SESSION['user']->guid) || !elgg_is_logged_in()) &&  $order && $subtype->subtype == "order_item"){
		// Get the file
		$product = get_entity($order->product_id);
		$version = get_entity(get_input("version_guid"));
		if(!$version){
			$version = get_entity($order->version_guid);
			
		}
		if ($product){	
			
			$prod = new ElggFile();	
			$prod->guid = $product->guid;
			$prod->owner_guid = $product->owner_guid;
			$prod->container_guid = $product->container_guid;
			
			// Added for consider the product versions
			if($version){
				$prod->mimetype = $version->mimetype;
				$prod->originalfilename = $version->originalfilename;
				$prod->filename = $version->filename;
			}else{
				$prod->mimetype = $product->mimetype;
				if($prod->mimetype==""){
					$version = get_latest_version($product->guid);
					if($version){
						$prod->mimetype = $version->mimetype;
						$prod->originalfilename = $version->originalfilename;
						$prod->filename = $version->filename;							
					}
				}else{
					$prod->originalfilename = $product->originalfilename;
					$prod->filename = $product->filename;
				}
			}
					
			$prod->simpletype = $product->simpletype;
			$prod->site_guid = $product->site_guid;
			$prod->access_id = $product->access_id;
			$prod->time_created = $product->time_created;
			$prod->time_updated = $product->time_updated;
			$prod->title = $product->title;
			$prod->description = $product->description;
			$prod->tables_split = $product->tables_split;
			$prod->tables_loaded = $product->tables_loaded;
			
			$mime = $prod->getMimeType();
			if (!$mime) $mime = "application/octet-stream";
			if($version){
				$filename = $version->originalfilename;
			}else{
				$filename = $product->originalfilename;
			}
			header("Content-type: $mime");
			if (strpos($mime, "image/")!==false)
				header("Content-Disposition: inline; filename=\"$filename\"");
			else
				header("Content-Disposition: attachment; filename=\"$filename\"");
			$contents = $prod->grabFile();
			$splitString = str_split($contents, 8192);
			foreach($splitString as $chunk)
				echo $chunk;
			exit;
		}
	}
	else
		register_error(elgg_echo("file:downloadfailed"));
?>