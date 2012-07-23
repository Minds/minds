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
	 * Elgg product - add action
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	global $CONFIG;
	$version_guid = get_input('version_guid',0);
	$store_guid = get_input('store_guid',0);
	$store = get_entity($store_guid);
	$shortname = 'mupload';
	$comma = "";
	$error_field ="";
	if($version_guid==""){
		if(isset($_FILES[$shortname]) && $_FILES[$shortname]['name'] == "" ){
			$error_field .= $comma.elgg_echo("product:upload_version");
			$comma =", ";
		}
	}
	if(get_input($shortname.'_version')==""){
		$error_field .= $comma.elgg_echo("product:mupload_version");
		$comma =", ";
	}
	if(get_input($shortname.'_version_summary')==""){
		$error_field .= $comma.elgg_echo("product:mupload_version_summary");
		$comma =", ";
	}
	if(empty($error_field)){
			unset($_SESSION['product']);
	}else{			
		$_SESSION['product'][$shortname.'_version'] = get_input($shortname.'_version');
		$_SESSION['product'][$shortname.'_version_summary'] = get_input($shortname.'_version_summary');
	}
	if(!empty($error_field)){
		register_error(sprintf(elgg_echo("product:validation:null"),$error_field));		
		$redirect = $CONFIG->wwwroot . "/".$CONFIG->pluginname."/version_edit/".$store_guid."/".$version_guid;	
		forward($redirect);
	}
			
	$version = new ElggObject($version_guid);
	$version->subtype="digital_product_versions";
	$version->owner_guid = $store->owner_guid;
	$version->access_id  = $store->access_id;
	
	
	$version->version_summary = trim(get_input( $shortname.'_version_summary'));
	$version->version_release = trim(get_input( $shortname.'_version'));
	$version->status = 1;
	$version_guid = $version->save();	
	if(isset($_FILES[$shortname]) && $_FILES[$shortname]['name'] != ""){
		
		if(!empty($version->filename)){
			$old_file = new ElggFile();
			$old_file->setFilename($version->filename);
			$old_file->owner_guid = $store->owner_guid;
			$old_file->container_guid = $store->container_guid;
			$old_file->site_guid = $store->site_guid;
			$old_file->delete();
		}
		//$version_guid = $version->save();
		$prefix = "{$CONFIG->pluginname}/";
		
		$upload_file = new ElggFile();
		$filestorename = strtolower(time().$_FILES[$shortname]['name']);
		$upload_file->setFilename($prefix.$filestorename);
		$upload_file->owner_guid = $store->owner_guid;
		$upload_file->container_guid = $store->container_guid;
		$upload_file->site_guid = $store->site_guid;
		$upload_file->setMimeType($_FILES[$shortname]['type']);
		$upload_file->originalfilename = $_FILES[$shortname]['name'];//print_r($upload_file);
		
		$upload_file->open("write");
		$upload_file->write(get_uploaded_file($shortname));
		$upload_file->close();
		
		$version->filename = $upload_file->filename;
		$version->mimetype = $upload_file->mimetype;
		$version->originalfilename = $upload_file->originalfilename;
		$version->simpletype = get_general_product_type($_FILES[$shortname]['type']);
		$version_guid = $version->save();
		
		// Generate thumbnail (if image)
		if (substr_count($upload_file->getMimeType(),'image/')){			
				$thumbnail = get_resized_image_from_existing_file($upload_file->getFilenameOnFilestore(),60,60, true);
				$thumbsmall = get_resized_image_from_existing_file($upload_file->getFilenameOnFilestore(),153,153, true);
				$thumblarge = get_resized_image_from_existing_file($upload_file->getFilenameOnFilestore(),600,600, false);
				if ($thumbnail) {					
					$thumb = new ElggFile();
					$thumb->owner_guid = $store->owner_guid;
					$thumb->container_guid = $store->container_guid;
					$thumb->site_guid = $store->site_guid;
					$thumb->setMimeType($_FILES[$shortname]['type']);
					
					$thumb->setFilename($prefix."thumb".$filestorename);
					$thumb->open("write");
					$thumb->write($thumbnail);
					$thumb->close();
					$version->thumbnail = $prefix."thumb".$filestorename;
					
					$thumb->setFilename($prefix."smallthumb".$filestorename);
					$thumb->open("write");
					$thumb->write($thumbsmall);
					$thumb->close();
					$version->smallthumb = $prefix."smallthumb".$filestorename;
					
					$thumb->setFilename($prefix."largethumb".$filestorename);
					$thumb->open("write");
					$thumb->write($thumblarge);
					$thumb->close();
					$version->largethumb = $prefix."largethumb".$filestorename;
						
				}
			}
			$version->save();
	}

	$value = trim(get_input($shortname));
	if(!empty($value)){
		$stores->$shortname = trim(get_input($shortname));
	}
	if($version_guid>0 && $store_guid>0){
		if(!check_entity_relationship($store_guid,'version_release',$version_guid)){
			add_entity_relationship($store_guid,'version_release',$version_guid);
		}
	}
	$container_user = get_entity($container_guid);	
	if($version_guid>0 && $store_guid>0){
		system_message(elgg_echo('version successfully saved'));
	}else{
		register_error(elgg_echo('Version failer to save'));
	}
	$redirect = $CONFIG->wwwroot . $CONFIG->pluginname."/edit/".$store_guid;	
	forward($redirect);