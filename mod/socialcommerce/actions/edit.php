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
	 * Elgg product - edit action
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
		$CONFIG->pluginlistcache = null;
		//----------- Get variables ---------------//
		$title = trim(get_input("storestitle"));
		$desc = trim(get_input("storesbody"));
		$file_name = trim($_FILES['upload']['name']);
		$product_type_id = get_input("product_type_id");
		$category = get_input("category_selected");
		$tags = trim(get_input("storestags"));
		$access_id = (int) get_input("access_id");
		$guid = (int) get_input('stores_guid');
		$tax_country = trim(get_input("tax_country"));
		//---------------- Check the entity --------------//
		if (!$stores = get_entity($guid)) {
			register_error(elgg_echo("stores:uploadfailed"));
			forward($CONFIG->wwwroot . "{$CONFIG->pluginname}/" . $_SESSION['user']->username);
			exit;
		}
		
		$product_fields = $CONFIG->product_fields[$product_type_id];
		
		//------------ Validation --------------------//
		if(empty($title)){
			$error_field = ", ".elgg_echo("title");
		}
		if(empty($desc)){
			$error_field .= ", ".elgg_echo("stores:text");
		}
		if(empty($product_type_id) || $product_type_id <= 0){
			$error_field .= ", ".elgg_echo("product:type");
		}
		if(empty($category)){
			$error_field .= ", ".elgg_echo("Category");
		}		
		if(substr_count($_FILES['product_image']['type'],'image/')==0 && $_FILES['product_image']['size']>0){
			$error_field .= ", ".elgg_echo("socialcommerce:product:image:error");
		}
		if (is_array($product_fields) && sizeof($product_fields) > 0){
			foreach ($product_fields as $shortname => $valtype){
				if($valtype['mandatory'] == 1){
					$value = trim(get_input($shortname));
					if($valtype['field'] == 'file')
						$value = trim($_FILES[$shortname]['name']);
					if($shortname == 'mupload'){
						continue;
					}	
					if(empty($value)){
						if($valtype['field'] == 'file' && $shortname == 'upload'){
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
							if(!is_numeric($value) || $value == 0)
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
				$ftp = new PS_FTPClass('', $destDir, $port, $host, $user, $pass, $url_path);
			}			
			if (!$ftp->conn) {
				$error_field .= ", ".elgg_echo("product:connection:fail");
			}
		}
		$validation = elgg_view("custom_field/validation",array('entity_type'=>$product_type_id));
		if($validation){
			$error_field .= ", ".$validation;
		}
		
		$result = false;
		
		$container_guid = $stores->container_guid;
		$container = get_entity($container_guid);
		
		if(!empty($error_field)){
			unset($_SESSION['product']);
			$_SESSION['product']['storestitle'] = $title;
			$_SESSION['product']['storesbody'] = $desc;
			$_SESSION['product']['product_type_id'] = $product_type_id;
			$_SESSION['product']['storescategory'] = $category;
			$_SESSION['product']['storestags'] = $tags;
			$_SESSION['product']['access_id'] = $access_id;
			
			if (is_array($product_fields) && sizeof($product_fields) > 0){
				foreach ($product_fields as $shortname => $valtype){
					if($valtype['field'] != 'file')
						$_SESSION['product'][$shortname] = get_input($shortname);
				}
			}
			
			$error_field = substr($error_field,2);
			
			register_error(sprintf(elgg_echo("product:validation:null"),$error_field));
			$container_user = get_entity($container_guid);
			$redirect = $CONFIG->wwwroot . "{$CONFIG->pluginname}/edit/".$guid;
		}else{
			if ($stores->canEdit()) {
				$old_product_type_id = $stores->product_type_id;
				$old_product_fields = $CONFIG->product_fields[$old_product_type_id];
				if($old_product_type_id != $product_type_id && is_array($old_product_fields) && sizeof($old_product_fields) > 0){
					foreach ($old_product_fields as $old_shortname => $old_valtype){
						if($old_valtype['field'] == 'file' && $old_shortname == 'upload'){
							$stores->filename = "";
							$stores->mimetype = "";
							$stores->originalfilename = "";
						}else{
							$stores->$old_shortname = "";
						}
					}
				}
				if (is_array($product_fields) && sizeof($product_fields) > 0){
					foreach ($product_fields as $shortname => $valtype){
						if($valtype['field'] == 'file' && $shortname == 'upload' && isset($_FILES[$shortname]) && $_FILES[$shortname]['name'] != ""){
							$old_filehandler = new ElggFile();
							$old_filehandler->owner_guid = $stores->owner_guid;
							$old_filehandler->setFilename($stores->filename);
							$old_file = $old_filehandler->getFilenameOnFilestore();
							if (substr_count($stores->mimetype,'image/')){
								$old_filehandler->setFilename($stores->thumbnail);
								$old_thumbnail_file = $old_filehandler->getFilenameOnFilestore();
								$old_filehandler->setFilename($stores->smallthumb);
								$old_smallthumb_file = $old_filehandler->getFilenameOnFilestore();
								$old_filehandler->setFilename($stores->largethumb);
								$old_largethumb_file = $old_filehandler->getFilenameOnFilestore();
							}
							
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
						}else{
							$value = trim(get_input($shortname));
							//if(!empty($value))
								$stores->$shortname = trim(get_input($shortname));
						}
					}
				}
				
				$stores->access_id = $access_id;
				$stores->title = $title;
				$stores->description = $desc;
				$stores->product_type_id = $product_type_id;
				$stores->category = $category;
				$stores->countrycode = $tax_country;
				
				// Save tags
				$tags = explode(",", $tags);
				$stores->tags = $tags;
				if(isset($_FILES['upload']) && $file_name != ""){
					$stores->simpletype = get_general_product_type($_FILES['upload']['type']);
				}
				$result = $stores->save();
				if($result){
					elgg_trigger_event('socialcommerce_product_edit',$stores->type,$stores);
				}
			}
			
			if ($result){
				if(in_array('product_update',$CONFIG->river_settings))
					add_to_river('river/object/stores/create','update',$_SESSION['user']->guid,$stores->guid);
				
				// Now see if we have a file product_image
				if ((isset($_FILES['product_image'])) && (substr_count($_FILES['product_image']['type'],'image/')))
				{
					$image_tmp = get_uploaded_file('product_image');							
					$image_prefix = "{$CONFIG->pluginname}/".$result;					
					$filehandler = new ElggFile();
					$filehandler->owner_guid = $stores->owner_guid;
					$filehandler->setFilename($image_prefix . ".jpg");
					$filehandler->open("write");
					$filehandler->write($image_tmp);					
					$filehandler->close();
					
					$thumbtiny = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true);
					$thumbsmall = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
					$thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),100,100, true);
					$thumblarge = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),350,800, false);
									
					if ($thumbtiny) {
						
						$thumb = new ElggFile();
						$thumb->owner_guid = $stores->owner_guid;
						$thumb->setMimeType('image/jpeg');
						
						$thumb->setFilename($image_prefix."tiny.jpg");
						$thumb->open("write");
						$thumb->write($thumbtiny);
						$tiny_path = $thumb->getFilenameOnFilestore();
						$thumb->close();
						
						$thumb->setFilename($image_prefix."small.jpg");
						$thumb->open("write");
						$thumb->write($thumbsmall);
						$samll_path = $thumb->getFilenameOnFilestore();
						$thumb->close();
						
						$thumb->setFilename($image_prefix."medium.jpg");
						$thumb->open("write");
						$thumb->write($thumbmedium);
						$medium_path = $thumb->getFilenameOnFilestore();
						$thumb->close();
						
						$thumb->setFilename($image_prefix."large.jpg");
						$thumb->open("write");
						$thumb->write($thumblarge);
						$large_path = $thumb->getFilenameOnFilestore();
						$thumb->close();
						
						$stores->icontime = time();
						$stores->save();
							
					}
					//$stores->ftp_upload_allow = false;
					/*if($CONFIG->ftp_upload_allow){
						$stores->ftp_upload_allow = true;
						$stores->ftp_http_path = $CONFIG->ftp_http_path;
						// create a new cURL resource
						$ch = curl_init();
						
						// set URL and other appropriate options
						curl_setopt($ch, CURLOPT_URL, $CONFIG->wwwroot."mod/socialcommerce/ftp_upload_images.php?store_guid=".$result);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						
						// grab URL and pass it to the browser
						curl_exec($ch);
						
						// close cURL resource, and free up system resources
						curl_close($ch);
						//echo $CONFIG->wwwroot."mod/socialcommerce/ftp_upload_images.php";
						//echo file_get_contents($CONFIG->wwwroot."mod/socialcommerce/ftp_upload_images.php?store_guid=".$result);
					}*/
					
					if($ftp->conn){
						$image_prefix_ftp = "{$CONFIG->pluginname}/{$_SESSION['user']->guid}";						
						$ftp->f_mkdir($image_prefix_ftp);					
											
						$from_path  = $filehandler->getFilenameOnFilestore();
						$ftp->f_upload_path($result.".jpg",$from_path);						
						$ftp->f_upload_path($result."tiny.jpg",$tiny_path);						
						$ftp->f_upload_path($result."small.jpg",$samll_path);
						$ftp->f_upload_path($result."medium.jpg",$medium_path);						
						$ftp->f_upload_path($result."large.jpg",$large_path);
						$stores->ftp_upload_allow = true;
						$stores->ftp_http_path = $ftp->httppath;		
						$ftp->f_close();
						$filehandler->delete();
						$thumb->delete();
						$thumb->setFilename($image_prefix."tiny.jpg");
						$thumb->delete();
						$thumb->setFilename($image_prefix."small.jpg");
						$thumb->delete();
						$thumb->setFilename($image_prefix."medium.jpg");
						$thumb->delete();
						
					}else{
						$stores->ftp_upload_allow = false;
					}
					//$stores->ftp_upload_allow = false;
				}
				$stores->save();
				
				// Generate thumbnail (if image)
				if(isset($_FILES['upload']) && $file_name != ""){
					if(file_exists($old_file)){
						unlink($old_file);
					}
					if(file_exists($old_thumbnail_file)){
						unlink($old_thumbnail_file);
					}
					if(file_exists($old_smallthumb_file)){
						unlink($old_smallthumb_file);
					}
					if(file_exists($old_largethumb_file)){
						unlink($old_largethumb_file);
					}
					
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
							if(file_exists("thumb".$old_file)){
								unlink($old_file);
							}
							
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