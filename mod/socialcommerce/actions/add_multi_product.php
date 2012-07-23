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
	 * Elgg product - add multiple product
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */
	// Check membership privileges
	global $CONFIG;
	$permission = membership_privileges_check('sell');
	if($permission == 1) {		
		$title_column = trim(get_input("storestitle"));
		$category_column = get_input("storescategory");
		$desc_column = trim(get_input("storesbody"));
		$product_type_id_column = get_input("product_type_id");	
		$tags_column = trim(get_input("storestags"));
		$access_id_column = (int) get_input("access_id");
		$product_image_column = trim(get_input("product_image"));
		//$container_guid_column = (int) get_input('container_guid', 0);
		//$tax_country_column = trim(get_input("tax_country"));
		$mupload_column = trim(get_input("mupload"));
		$mupload_version_column = trim(get_input("mupload_version"));
		$mupload_version_summary_column = trim(get_input("mupload_version_summary"));
		//$price_column = trim(get_input("price"));
		//$base_stock_column = trim(get_input("base_stock"));
		
		$erro_message = "Please give valid data(s) on <br />";
		$error_messsage_select = "Please assign values on <br>";
		/**Get All physical category */
		//Depricated function replace
		/*$options = array(	'metadata_name_value_pairs'	=>	array('product_type_id' =>1),
							'types'				=>	"object",
							'subtypes'			=>	"category",
							'limit'				=>	99999,
						);
		$category_lists = elgg_get_entities_from_metadata($options);*/
		$category_lists = getall_categories(0,1);
        //$category_lists = get_entities_from_metadata("product_type_id",1,"object","category",0,99999);
        $cat_physical_values = array();
        if($category_lists){
        	foreach ($category_lists as $key => $value){
        		$cat_physical_values[strtoupper($value)] = $key;
        	}	
        }
        
        /**Get All digital category */
        //Depricated function replace
/*		$options = array(	'metadata_name_value_pairs'	=>	array('product_type_id' =>2),
							'types'				=>	"object",
							'subtypes'			=>	"category",
							'limit'				=>	99999,
						);
		$category_lists = elgg_get_entities_from_metadata($options);*/
		$category_lists = getall_categories(0,2);
        //$category_lists = get_entities_from_metadata("product_type_id",2,"object","category",0,99999);
        $cat_digital_values = array();
        if($category_lists){
        	foreach ($category_lists as $key => $value){
        		$cat_digital_values[strtoupper($value)] = $key;
        	}	
        }        
		/**ACCESS LIST*/
        $access_arrs = get_write_access_array();
        $access_arr = array();
        foreach($access_arrs as $key=>$value){
        	$access_arr[strtoupper($value)] = $key;
        }
        
		$dir_path =  $CONFIG->pluginspath.$CONFIG->pluginname."/upload_csv/".$_SESSION['user']->username.".csv";
		$row = 0;
		$display_row = 1;		
		$product_type_ids = array('PHYSICAL'=>1,'DIGITAL'=>2);
		$shortname_all = array(); 
		$error_field ="";
		if ($CONFIG->ftp_upload_allow){
			$host = $CONFIG->ftp_host_url;
			$port = $CONFIG->ftp_port;
			$user = $CONFIG->ftp_user;
			$pass = $CONFIG->ftp_password;	
			$destDir = $CONFIG->ftp_base_path.$CONFIG->ftp_upload_dir;				
			$ftp = new PS_FTPClass('', $destDir, $port, $host, $user, $pass);			
			if (!$ftp) {
				$error_field .= ", ".elgg_echo("product:connection:fail");
			}
		}	
		if (($handle = fopen($dir_path, "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		        $num = count($data);
		        if($num > $max_column){
		        	$max_column = $num;
		        }	        	
		        if($row == 0){		        	
			        for($c=0; $c < $num; $c++) {
			            $field_title[] = $data[$c];
			        }
		        }else{
		        	
		        	/*for($c=0; $c < $num; $c++) {           
			            $field_data[] = $data[$c];
			        }*/        	
			        
		        	$title = $data[$title_column];		        	
		        	$category_value = strtoupper($data[$category_column]);     	
		        	$desc = $data[$desc_column];
		        	$product_type_id = $product_type_ids[strtoupper($data[$product_type_id_column])];		        	
		        	$tags = $data[$tags_column];		        	
		        	$access_id = $access_arr[strtoupper($data[$access_id_column])];
		        	$product_fields = $CONFIG->product_fields[$product_type_id];
		        	$product_image = $data[$product_image_column]; 		        	
		        	$error_field_title = "";
		        	if($title == ""){
		        		
		        		if($title_column == ""){		        			
		        			$error_field_title = $error_field_title != "" ? $error_field_title.", title ": 'title';
		        		}else{
		        			$error_field .= " row no: $display_row * column no: ". ($title_column+1) ." <br>";
		        		}
		        	}
		        	
		       	 	if($product_type_id == ""){
		       	 		if($product_type_id_column == ""){
		        			$error_field_title = $error_field_title != "" ? $error_field_title.", type ": 'type';
		        		}else{
		        			$error_field .= " row no: $display_row * column no: ". ($product_type_id_column+1) ." <br>";
		        		}			        		
		        	}		        	
		        	/*Get the category value by its name**/
		        	if($product_type_id == 1){		        		
		        		$category = $cat_physical_values[$category_value];		        		
		        	}else if($product_type_id == 2){		        		
		        		$category = $cat_digital_values[$category_value];
		        	}
		        	if($category == ""){
		        		if($category_column == ""){
		        			$error_field_title = $error_field_title != "" ? $error_field_title.", ".elgg_echo('category'): elgg_echo('category');
		        		}else{
		        			$error_field .= " row no: $display_row * column no: ". ($category_column+1) ." <br>";
		        		}
		        				        		
		        	}
		        	if($desc == ""){
		        		if($desc_column == ""){
		        			$error_field_title = $error_field_title != "" ? $error_field_title.", ".elgg_echo('category'): elgg_echo('category');
		        		}else{
		        			$error_field .= " row no: $display_row * column no: ". ($desc_column+1) ." <br>";
		        		}	
		        	}
		        		        	
		        	if($product_type_id!=""){		        		
		        		/* Get product from the table*/
		        		//foreach($product_type_ids as $product_type_id){
			        		if(is_array($product_fields) && sizeof($product_fields) > 0){
									foreach ($product_fields as $shortname => $valtype){										
										//if(!in_array($shortname,$shortname_all)){										
											if($valtype['mandatory'] == 1){											
												$column_value = trim(get_input($shortname));						
												$value = $data[$column_value];												
												if(empty($value)){
													if($column_value == ""){
														$error_field_title = $error_field_title != "" ? $error_field_title.",".elgg_echo('product:'.$shortname): elgg_echo('product:'.$shortname);
													}elseif($value == ""){
														$error_field .= "row no: $display_row * column no: ". ($column_value+1) ."<br />";
													}													
												}elseif(($shortname == 'mupload' && $product_type_id == 2) || ($valtype['field'] == 'file' && $shortname == 'upload')){
													$mupload = $data[$column_value];													
													if($mupload!=""){
										        		$upload_data = file_get_contents($mupload);
										        	}
													if($column_value == ""){
														$error_field_title = $error_field_title != "" ? $error_field_title.",".elgg_echo('product:'.$shortname): elgg_echo('product:'.$shortname);
													}elseif($upload_data == ""){
										        		$error_field .= "row no: $display_row * column no: ". ($column_value+1) ."<br />";
										        	}
												}else{
													if($shortname == 'quantity'){
														if($column_value == ""){
															$error_field_title = $error_field_title != "" ? $error_field_title.",".elgg_echo('product:'.$shortname): elgg_echo('product:'.$shortname);
														}elseif(ereg("[^0-9]",$value)){													
															$error_field .= "row no: $display_row * column no: ". ($column_value+1) ."<br />";
														}
													}
													if($shortname == 'base_stock'){
														if($column_value == ""){
															$error_field_title = $error_field_title != "" ? $error_field_title.",".elgg_echo('product:'.$shortname): elgg_echo('product:'.$shortname);
														}elseif(ereg("[^0-9]",$value)){														
															$error_field .= "row no: $display_row * column no: ". ($column_value+1) ."<br />";
														}
													}
													if($shortname == 'price'){
														if($column_value == ""){
															$error_field_title = $error_field_title != "" ? $error_field_title.",".elgg_echo('product:'.$shortname): elgg_echo('product:'.$shortname);
														}elseif(!is_numeric($value) || $value == 0){													
															$error_field .= "row no: $display_row * column no: ". ($column_value+1) ."<br />";
														}
													}
												}
												if(($shortname == 'mupload')){															
														$column_value = trim(get_input($shortname.'_version'));						
														$value = $data[$column_value];
														if($column_value == ""){
															$error_field_title = $error_field_title != "" ? $error_field_title.",".elgg_echo('socialcommerce:multi_prod_ver:version:release'): elgg_echo('socialcommerce:multi_prod_ver:version:release');
														}elseif($value == ""){
															$error_field .= "row no: $display_row * column no: ". ($column_value+1) ."<br />";
														}
														$column_value = trim(get_input($shortname.'_version_summary'));						
														$value = $data[$column_value];
														if($column_value == ""){
															$error_field_title = $error_field_title != "" ? $error_field_title.",".elgg_echo('socialcommerce:multi_prod_ver:version:summary'): elgg_echo('socialcommerce:multi_prod_ver:version:summary');
														}elseif($value == ""){
															$error_field .= "row no: $display_row * column no: ". ($column_value+1) ."<br />";
														}
												}
											}
										//}
									$shortname_all[] = $shortname;	
									}
			        		}	
						//}	        		
		        	}
		        	if($product_image!=""){
		        		
		        		$data = file_get_contents($product_image);
		        	}
	        		if($access_id == ""){
		        		$error_field .= "row no: $display_row * column no: ".($access_id_column+1) ."<br />";
		        	}		        	
		        }
		        $row++;
		        $display_row++;		       
		    }
		    fclose($handle);
		}
		if($error_field != "" && $error_field_title!=""){			
			register_error($error_messsage_select.$error_field_title."<br />".$erro_message.$error_field);
			//echo $error_messsage_select.$error_field_title."<br />".$erro_message.$error_field;			
		}elseif($error_field != ""){
			register_error($erro_message.$error_field);
			//echo $erro_message.$error_field;
		}elseif($error_field_title !=""){
			register_error($error_messsage_select.$error_field_title);
			//echo $error_messsage_select.$error_field_title;
		}else{// Save the product Entity
			//exit;
			$row = 0;
			$display_row = 1;			
			$shortname_all = array();
			$success_count = 0; 	
			if (($handle = fopen($dir_path, "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
			        $num = count($data);
			        if($num > $max_column){
			        	$max_column = $num;
			        }	        	
			        if($row == 0){		        	
				        for($c=0; $c < $num; $c++) {
				            $field_title[] = $data[$c];
				        }
			        }else{			        	
			        	// Extract stores from, save to default stores (for now)
			        	$access_id = $access_arr[strtoupper($data[$access_id_column])];
			        	
						$stores = new ElggObject();
						$stores->subtype="stores";
						$stores->access_id = $access_id;

						
			        	$title = $data[$title_column];
			        	
		        				        	
			        	
			        	$desc = $data[$desc_column];
			        	//$product_type_id = $data[$product_type_id_column];
			        	$product_type_id = $product_type_ids[strtoupper($data[$product_type_id_column])];
			        	
			        	$tags = $data[$tags_column];
			        	
			        	//$container_guid =  $data[$container_guid_column];
			        	
			        	$quantity = $data[$quantity_column];
						$price = $data[$price_column];
						$base_stock = $data[$base_stock_column];		        	
			        	$product_fields = $CONFIG->product_fields[$product_type_id];
			        	
			        	$category_value = strtoupper($data[$category_column]);	
				        /*Get the category value by its name**/
			        	if($product_type_id == 1){		        		
			        		$category = $cat_physical_values[$category_value];		        		
			        	}else if($product_type_id == 2){		        		
			        		$category = $cat_digital_values[$category_value];
			        	}	        	
		        		
			        	
			        	//**SELECT THE PRODUCT FIELDS**///			       
		        		//foreach($product_type_ids as $product_type_id){
			        		if(is_array($product_fields) && sizeof($product_fields) > 0){
									foreach ($product_fields as $shortname => $valtype){										
										//if(!in_array($shortname,$shortname_all)){																				
											$column_value = trim(get_input($shortname));						
											$value = trim($data[$column_value]);
											if(!empty($value)){
												if($shortname == 'mupload'){													
													//**File version details***///
										        	$mupload = $data[$mupload_column];
										        	if($mupload!=""){
										        		$upload_data = file_get_contents($mupload);
										        	}
										        	$path_parts = pathinfo($mupload);
										        	$upload_file_name = $path_parts['basename'];		        	
										        	$upload_file_type  = get_mime_type($mupload);
										        	
										        	
													$version = new ElggObject();
													$version->subtype="digital_product_versions";
													$version->owner_guid = $_SESSION['user']->guid;
													$version->access_id  = $access_id;
													$version_guid = $version->save();
													$prefix = "{$CONFIG->pluginname}/";
													
													$upload_file = new ElggFile();
													$filestorename = strtolower(time().$upload_file_name);
													$upload_file->setFilename($prefix.$filestorename);
													$upload_file->setMimeType($upload_file_type);
													$upload_file->originalfilename = $upload_file_name;
													$upload_file->open("write");
													$upload_file->write($upload_data);
													$upload_file->close();
													
													$version->filename = $upload_file->filename;
													$version->mimetype = $upload_file->mimetype;
													$version->originalfilename = $upload_file->originalfilename;
													$version->simpletype = get_general_product_type($upload_file_type);
													$version->version_summary = $data[trim(get_input( $shortname.'_version_summary'))];
													$version->version_release = $data[trim(get_input( $shortname.'_version'))];
													$version->status = 1;
													$version_guid = $version->save();
													
													// Generate thumbnail (if image)
													if($upload_file_name != ""){
														if (substr_count($upload_file->getMimeType(),'image/')){
															$thumbnail = get_resized_image_from_existing_file($upload_file->getFilenameOnFilestore(),60,60, true);
															$thumbsmall = get_resized_image_from_existing_file($upload_file->getFilenameOnFilestore(),153,153, true);
															$thumblarge = get_resized_image_from_existing_file($upload_file->getFilenameOnFilestore(),600,600, false);
															if ($thumbnail) {
																$thumb = new ElggFile();
																$thumb->setMimeType($upload_file_type);
																
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
												}else{
													$stores->$shortname = $value;
												}
											}	
										//}
										$shortname_all[] = $shortname;	
									}
							}
						//}			        	
						$stores->title = $title;
						$stores->status = 1;
						$stores->description = $desc;
						$stores->product_type_id = $product_type_id;
						$stores->category = $category;
						$stores->access_id = $access_id;
						//$stores->countrycode = $tax_country;
						
						// Save tags
						$tags = explode(",", $tags);
						$stores->tags = $tags;
						
						if ($container_guid){
							$stores->container_guid = $container_guid;
						}
				
						$result = $stores->save();
			        	
			        	/*$mupload_version = $data[$mupload_version_column];
			        	$mupload_version_summary = $data[$mupload_version_summary_column];	  */      					
			        	
			        	if($result>0){			        			        	
				        	//**SAVE PRODUCT IMAGE**/
				        	$product_image = $data[$product_image_column]; 
				        	if($product_image!=""){
				        		$upload_data = file_get_contents($product_image);
				        	}
				        	$path_parts = pathinfo($product_image);		        	
				        	$product_type  = get_mime_type($product_image);		        	
				        	
		        			if($version_guid>0){
								if(!check_entity_relationship($result,'version_release',$version_guid)){
									add_entity_relationship($result,'version_release',$version_guid);
								}
							}
				        	
							//**** Saving the product image****// 
							if(substr_count($product_type,'image/')){ 						
						        $image_prefix = $CONFIG->pluginname."/".$result;						
								$product_imagehandler = new ElggFile();
								$product_imagehandler->owner_guid = $stores->owner_guid;
								$product_imagehandler->setFilename($image_prefix . ".jpg");
								$product_imagehandler->open("write");
								$product_imagehandler->write($upload_data);
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
									$product_thumb->close();
									
									$product_thumb->setFilename($image_prefix."small.jpg");
									$product_thumb->open("write");
									$product_thumb->write($product_thumbsmall);
									$product_thumb->close();
									
									$product_thumb->setFilename($image_prefix."medium.jpg");
									$product_thumb->open("write");
									$product_thumb->write($product_thumbmedium);
									$product_thumb->close();
									
									$product_thumb->setFilename($image_prefix."large.jpg");
									$product_thumb->open("write");
									$product_thumb->write($product_thumblarge);
									$product_thumb->close();
									
									$stores->icontime = time();
									$stores->save();
										
								}
								if($CONFIG->ftp_upload_allow){
									$image_prefix_ftp = "{$CONFIG->pluginname}/{$_SESSION['user']->guid}";					
									$ftp->f_mkdir($image_prefix_ftp);						
									$from_path  = $product_imagehandler->getFilenameOnFilestore();
									$ftp->f_upload_path($result.".jpg",$from_path);												
									$ftp->f_upload_path($result."tiny.jpg",$tiny_path);						
									$ftp->f_upload_path($result."small.jpg",$samll_path);
									$ftp->f_upload_path($result."medium.jpg",$medium_path);						
									$ftp->f_upload_path($result."large.jpg",$large_path);
									$stores->ftp_upload_allow = true;
									$stores->ftp_http_path = $CONFIG->ftp_http_path;
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
							$success_count++;
							
			        	}	
			        				        	       	
			        }
			        $row++;
			        $display_row++;
			    }
			    fclose($handle);
			}
		}
	}
	
	$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/add_multiple_product';
	if($success_count>0){
		system_message(sprintf(elgg_echo('stores:add:multiple:success'),$success_count));
		unlink($dir_path);
		$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/upload_multiple';
	}
	
	forward($redirect);
	exit;
?>