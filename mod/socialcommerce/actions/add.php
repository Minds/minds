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
	// Check membership privileges
	$permission = membership_privileges_check('sell');
	if($permission == 1) {
		// Get variables
		$title = trim(get_input("storestitle"));
		$file_name = trim($_FILES['upload']['name']);
		$desc = trim(get_input("storesbody"));
		$category = get_input("category_selected");
		$product_type_id = get_input("product_type_id");
		$tags = trim(get_input("storestags"));
		$access_id = (int) get_input("access_id");
		$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());
		$tax_country = trim(get_input("tax_country"));
		
		$product_fields = $CONFIG->product_fields[$product_type_id];
		
		//Validation
		if(empty($title)){
			$error_field = ", ".elgg_echo("title");
		}
		if(empty($desc)){
			$error_field .= ", ".elgg_echo("stores:text");
		}
		if(empty($category)){
			$error_field .= ", ".elgg_echo("category");
		}
	
		if(empty($product_type_id) || $product_type_id <= 0){
			$error_field .= ", ".elgg_echo("product:type");
		}
		if(substr_count($_FILES['product_image']['type'],'image/')==0){
			$error_field .= ", ".elgg_echo("socialcommerce:product:image:error");
		}
		
		if (is_array($product_fields) && sizeof($product_fields) > 0){
			foreach ($product_fields as $shortname => $valtype){
				if($valtype['mandatory'] == 1){
					$value = trim(get_input($shortname));
					if($valtype['field'] == 'file')
						$value = trim($_FILES[$shortname]['name']);
					if(empty($value)){
						if($shortname == 'mupload'){
							if($_FILES[$shortname]['name'] == ""){
								$error_field .= ", ".elgg_echo("product:upload_version");
							}
							if(trim(get_input( $shortname.'_version')) == ""){
								$error_field .= ", ".elgg_echo("product:mupload_version");
							}
							if(trim(get_input( $shortname.'_version_summary')) == ""){
								$error_field .= ", ".elgg_echo("product:mupload_version_summary");
							}

						}elseif($valtype['field'] == 'file' && $shortname == 'upload'){
							if($stores->mimetype == "")
								$error_field .= ", ".elgg_echo("product:".$shortname);
						}else{
							$error_field .= ", ".elgg_echo("product:".$shortname);
						}
					}else{
						if($shortname == 'quantity'){
							if(ereg("[^0-9]",$value))
								$error_field .= ", ".elgg_echo("product:".$shortname);
						}
						if($shortname == 'base_stock'){
							if(ereg("[^0-9]",$value))
								$error_field .= ", ".elgg_echo("product:".$shortname);
						}
						if($shortname == 'price'){
							if((!is_numeric($value)) || $value == 0 )
								$error_field .= ", ".elgg_echo("product:".$shortname);
						}
					}
				}
			}
		}
		if ($CONFIG->ftp_upload_allow){
			$host = $CONFIG->ftp_host_url;
			$port = $CONFIG->ftp_port;
			$user = $CONFIG->ftp_user;
			$pass = $CONFIG->ftp_password;	
			$destDir = $CONFIG->ftp_base_path.$CONFIG->ftp_upload_dir;
			$url_path = $CONFIG->ftp_http_path;
			$category_entity = get_entity($category);
			$ftp =  trigger_plugin_hook('product_save_ftp','object',array(
																			'entity' => $category_entity),
																		FALSE);
			if(!$ftp->conn){		
				$ftp = new PS_FTPClass('', $destDir, $port, $host, $user, $pass,$url_path);
			}				
			if (!$ftp->conn){
				$error_field .= ", ".elgg_echo("product:connection:fail");
			}
		}
		if(!empty($error_field)){
			unset($_SESSION['product']);
			$_SESSION['product']['storestitle'] = $title;
			$_SESSION['product']['product_type_id'] = $product_type_id;
			$_SESSION['product']['storescategory'] = $category;
			$_SESSION['product']['storesbody'] = $desc;
			$_SESSION['product']['storestags'] = $tags;
			$_SESSION['product']['access_id'] = $access_id;
			
			if (is_array($product_fields) && sizeof($product_fields) > 0){
				foreach ($product_fields as $shortname => $valtype){
					if($shortname == 'mupload'){						
						$_SESSION['product'][$shortname.'_version'] = get_input($shortname.'_version');
						$_SESSION['product'][$shortname.'_version_summary'] = get_input($shortname.'_version_summary');
					}else if($valtype['field'] != 'file'){
						$_SESSION['product'][$shortname] = get_input($shortname);
					}	
				}
			}
			
			$error_field = substr($error_field,2);
			register_error(sprintf(elgg_echo("product:validation:null"),$error_field));
			$container_user = get_entity($container_guid);
			$redirect = $CONFIG->wwwroot . $CONFIG->pluginname."/add/".$container_guid;
		}else{
			// Extract stores from, save to default stores (for now)
			$stores = new ElggObject();
			$stores->subtype="stores";
			$stores->access_id = $access_id;			
			if (is_array($product_fields) && sizeof($product_fields) > 0){
				foreach ($product_fields as $shortname => $valtype){
					if($valtype['field'] == 'file' && $shortname == 'upload' && isset($_FILES[$shortname]) && $_FILES[$shortname]['name'] != ""){
						$prefix = "{$CONFIG->pluginname}/";
						$upload_file = new ElggFile();
						$filestorename = strtolower(time().$_FILES[$shortname]['name']);
						$upload_file->setFilename($prefix.$filestorename);
						$upload_file->setMimeType($_FILES[$shortname]['type']);
						$upload_file->originalfilename = $_FILES[$shortname]['name'];
						$upload_file->open("write");
						$upload_file->write(get_uploaded_file('upload'));
						$upload_file->close();
						
						$stores->filename = $upload_file->filename;
						$stores->mimetype = $upload_file->mimetype;
						$stores->originalfilename = $upload_file->originalfilename;
						$stores->simpletype = get_general_product_type($_FILES[$shortname]['type']);
					}else if($shortname == 'mupload'){
						$version = new ElggObject($version_guid);
						$version->subtype="digital_product_versions";
						$version->owner_guid = $_SESSION['user']->guid;
						$version->access_id  = $access_id;
						//$version_guid = $version->save();
						$prefix = "{$CONFIG->pluginname}/";
						
						$upload_file = new ElggFile();
						$filestorename = strtolower(time().$_FILES[$shortname]['name']);
						$upload_file->setFilename($prefix.$filestorename);
						$upload_file->setMimeType($_FILES[$shortname]['type']);
						$upload_file->originalfilename = $_FILES[$shortname]['name'];
						$upload_file->open("write");
						$upload_file->write(get_uploaded_file($shortname));
						$upload_file->close();
						
						$version->filename = $upload_file->filename;
						$version->mimetype = $upload_file->mimetype;
						$version->originalfilename = $upload_file->originalfilename;
						$version->simpletype = get_general_product_type($_FILES[$shortname]['type']);
						$version->version_summary = trim(get_input( $shortname.'_version_summary'));
						$version->version_release = trim(get_input( $shortname.'_version'));
						$version->status = 1;
						$version_guid = $version->save();
						
						// Generate thumbnail (if image)
						if(isset($_FILES[$shortname]) && $_FILES[$shortname]['name'] != ""){
							if (substr_count($upload_file->getMimeType(),'image/')){
								$thumbnail = get_resized_image_from_existing_file($upload_file->getFilenameOnFilestore(),60,60, true);
								$thumbsmall = get_resized_image_from_existing_file($upload_file->getFilenameOnFilestore(),153,153, true);
								$thumblarge = get_resized_image_from_existing_file($upload_file->getFilenameOnFilestore(),600,600, false);
								if ($thumbnail) {
									$thumb = new ElggFile();
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

					}

					$value = trim(get_input($shortname));
					if(!empty($value))
						$stores->$shortname = trim(get_input($shortname));
				}
			}
			
			$stores->title = $title;
			$stores->status = 1;
			$stores->description = $desc;
			$stores->product_type_id = $product_type_id;
			$stores->category = $category;
			$stores->countrycode = $tax_country;
			
			// Save tags
			$tags = explode(",", $tags);
			$stores->tags = $tags;
			
			if ($container_guid){
				$stores->container_guid = $container_guid;
			}
	
			$result = $stores->save();
			if($version_guid>0){
				if(!check_entity_relationship($result,'version_release',$version_guid)){
					add_entity_relationship($result,'version_release',$version_guid);
				}
			}	
			if($result){
				trigger_elgg_event('socialcommerce_product_add',$stores->type,$stores);
			}
			if ($result){
				if(in_array('product_add',$CONFIG->river_settings))
					add_to_river('river/object/stores/create','create',$_SESSION['user']->guid,$stores->guid);
				
				// Now see if we have a file product_image
				if ((isset($_FILES['product_image'])) && (substr_count($_FILES['product_image']['type'],'image/')))
				{
					$image_prefix = $CONFIG->pluginname."/".$result;
					
					$product_imagehandler = new ElggFile();
					$product_imagehandler->owner_guid = $stores->owner_guid;
					$product_imagehandler->setFilename($image_prefix . ".jpg");
					$product_imagehandler->open("write");
					$product_imagehandler->write(get_uploaded_file('product_image'));
					$product_imagehandler->close();
					
					$product_thumbtiny = get_resized_image_from_existing_file($product_imagehandler->getFilenameOnFilestore(),25,25, true);
					$product_thumbsmall = get_resized_image_from_existing_file($product_imagehandler->getFilenameOnFilestore(),40,40, true);
					$product_thumbmedium = get_resized_image_from_existing_file($product_imagehandler->getFilenameOnFilestore(),100,100, true);
					$product_thumblarge = get_resized_image_from_existing_file($product_imagehandler->getFilenameOnFilestore(),350,800, false);
					if ($product_thumbtiny) {
						
						$product_thumb = new ElggFile();
						$product_thumb->owner_guid = $stores->owner_guid;
						$product_thumb->setMimeType('image/jpeg');
						
						$product_thumb->setFilename($image_prefix."tiny.jpg");
						$product_thumb->open("write");
						$product_thumb->write($product_thumbtiny);
						$tiny_path = $product_thumb->getFilenameOnFilestore();
						$product_thumb->close();
						
						$product_thumb->setFilename($image_prefix."small.jpg");
						$product_thumb->open("write");
						$product_thumb->write($product_thumbsmall);
						$samll_path = $product_thumb->getFilenameOnFilestore();
						$product_thumb->close();
						
						$product_thumb->setFilename($image_prefix."medium.jpg");
						$product_thumb->open("write");
						$product_thumb->write($product_thumbmedium);
						$medium_path = $product_thumb->getFilenameOnFilestore();
						$product_thumb->close();
						
						$product_thumb->setFilename($image_prefix."large.jpg");
						$product_thumb->open("write");
						$product_thumb->write($product_thumblarge);
						$large_path = $product_thumb->getFilenameOnFilestore();
						$product_thumb->close();
						
						$stores->icontime = time();
						$stores->save();
							
					}
					if($ftp->conn){
						$image_prefix_ftp = "{$CONFIG->pluginname}/{$_SESSION['user']->guid}";					
						$ftp->f_mkdir($image_prefix_ftp);						
						$from_path  = $product_imagehandler->getFilenameOnFilestore();
						$ftp->open_f_upload_path($result.".jpg",$from_path);												
						$ftp->open_f_upload_path($result."tiny.jpg",$tiny_path);						
						$ftp->open_f_upload_path($result."small.jpg",$samll_path);
						$ftp->open_f_upload_path($result."medium.jpg",$medium_path);						
						$ftp->open_f_upload_path($result."large.jpg",$large_path);
						$stores->ftp_upload_allow = true;
						$stores->ftp_http_path = $ftp->httppath;
						$ftp->f_close();
						$product_imagehandler->delete();
						$product_thumb->delete();
						$product_thumb->setFilename($image_prefix."tiny.jpg");
						$product_thumb->delete();
						$product_thumb->setFilename($image_prefix."small.jpg");
						$product_thumb->delete();
						$product_thumb->setFilename($image_prefix."medium.jpg");
						$product_thumb->delete();
					}else{
						$stores->ftp_upload_allow = false;
					}
				}
				$stores->save();
				// Generate thumbnail (if image)
				if(isset($_FILES['upload']) && $file_name != ""){
					if (substr_count($upload_file->getMimeType(),'image/')){
						$thumbnail = get_resized_image_from_existing_file($upload_file->getFilenameOnFilestore(),60,60, true);
						$thumbsmall = get_resized_image_from_existing_file($upload_file->getFilenameOnFilestore(),153,153, true);
						$thumblarge = get_resized_image_from_existing_file($upload_file->getFilenameOnFilestore(),600,600, false);
						if ($thumbnail) {
							$thumb = new ElggFile();
							$thumb->setMimeType($_FILES['upload']['type']);
							
							$thumb->setFilename($prefix."thumb".$filestorename);
							$thumb->open("write");
							$thumb->write($thumbnail);
							$thumb->close();
							$stores->thumbnail = $prefix."thumb".$filestorename;
							
							$thumb->setFilename($prefix."smallthumb".$filestorename);
							$thumb->open("write");
							$thumb->write($thumbsmall);
							$thumb->close();
							$stores->smallthumb = $prefix."smallthumb".$filestorename;
							
							$thumb->setFilename($prefix."largethumb".$filestorename);
							$thumb->open("write");
							$thumb->write($thumblarge);
							$thumb->close();
							$stores->largethumb = $prefix."largethumb".$filestorename;
								
						}
					}
				}
			}
				
			if ($result){
				system_message(elgg_echo("stores:saved"));
				unset($_SESSION['product']);
			}else{
				register_error(elgg_echo("stores:uploadfailed"));
			}
			$container_user = get_entity($container_guid);
			$redirect = $CONFIG->wwwroot . "{$CONFIG->pluginname}/owner/" . $container_user->username;
		}
		
		
		forward($redirect);
	} else {
		system_message(elgg_echo("update:sell"));
	}

?>