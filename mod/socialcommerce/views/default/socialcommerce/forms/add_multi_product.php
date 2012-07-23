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
	 * Elgg upload the multiple products
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	global $CONFIG;
	$field_title  = array();
	$field_data = array();
	$max_column = 1;	
	$dir_path =  $CONFIG->pluginspath.$CONFIG->pluginname."/upload_csv/".$_SESSION['user']->username.".csv";
	$row = 1;
	
	if (($handle = fopen($dir_path, "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	        $num = count($data);
	        if($num > $max_column){
	        	$max_column = $num;
	        }	        	
	        if($row == 1){
		        for($c=0; $c < $num; $c++) {		          
		            $field_title[$c] = $data[$c];
		        }
	        }else{
	        	for($c=0; $c < $num; $c++) {		           
		            $field_data[] = $data[$c];
		        }
	        }    
	        $row++;
	    }
	    fclose($handle);
	}else{
		;	
	}	
	for($i=0;$i<$max_column;$i++){	
		$value = $field_title[$i]!="" ? $field_title[$i] : "column$i";
		$option_title .= "<option value={$i}>{$value}</option><br>"; 
	}	
	$url = 	$vars['url']."action/".$CONFIG->pluginname."/add_multi_product";
	
	
	 $fields = '';
	 $product_type_ids =array('Physical'=>1,'Digital'=>2);
	 $product_type_id = 1;
	 $shortname_all = array();
	 foreach($product_type_ids as $product_type_id){
		$product_fields = $CONFIG->product_fields[$product_type_id];
		if(is_array($product_fields) && sizeof($product_fields) > 0){
			foreach ($product_fields as $shortname => $valtype){
				if($product_type_id == 2)
					$option_start_title = "<option value=''>Select for digital product</option>";
				elseif($product_type_id == 1)
					$option_start_title = "<option value=''>Select for physical product</option>";
				
				if($shortname == 'price'){
					$option_start_title = "<option value=''>Select</option>";
				}				
				if($valtype['mandatory'] == 1){
					$mandetory = '<span style="color:red">*</span>';	
				}else{
					$mandetory = '';	
				}				
				if(!in_array($shortname,$shortname_all)){
					$dis_name = 'product:'.$shortname;
					if($shortname == 'mupload'){
						$dis_name = 'socialcommerce:multi_prod_ver:uploadfile';
					}
					$fields .='<div class="fields clearboth">
								<br>		
									<div class="multi_pro_label">
										<b><span style="color:red">'.$mandetory.'</span>'.elgg_echo($dis_name).'</b>
									</div>	
									<div class="multi_pro_value">
										<select name="'.$shortname.'">'.$option_start_title.$option_title.'
										</select>
									</div>
								</div>';
					if($shortname == 'mupload'){
						$fields .='<div class="fields clearboth">
								<br>		
									<div class="multi_pro_label">
										<b><span style="color:red">'.$mandetory.'</span>'.elgg_echo('socialcommerce:multi_prod_ver:version:release').'</b>
									</div>	
									<div class="multi_pro_value">
										<select name="'.$shortname.'_version">'.$option_start_title.$option_title.'
										</select>
									</div>
								</div>';
						$fields .='<div class="fields clearboth">
								<br>		
									<div class="multi_pro_label">
										<b><span style="color:red">'.$mandetory.'</span>'.elgg_echo('socialcommerce:multi_prod_ver:version:summary').'</b>
									</div>	
									<div class="multi_pro_value">
										<select name="'.$shortname.'_version_summary">'.$option_start_title.$option_title.'
										</select>
									</div>
								</div>';
					}	
				}
				$shortname_all[] = $shortname;
			}
		}
	 }
	$option_start_title = "<option value=''>Select</options>";
	$option_title = $option_start_title.$option_title;
?>
<form method="post" action="<?php echo $url;?>">
	<div class="fields clearboth">
	<br>		
		<div class="multi_pro_label">
				<b><span style="color:red">*</span><?php echo elgg_echo('Title');?></b>
		</div>	
		<div class="multi_pro_value">
				<select name="storestitle">
					<?php echo $option_title;?>
				</select>
		</div>
	</div>
	<div class="fields clearboth">
	<br>		
			<div class="multi_pro_label">
				<b><span style="color:red">*</span><?php echo elgg_echo('Category');?></b>
			</div>	
			<div class="multi_pro_value">
				<select name="storescategory">
					<?php echo $option_title;?>
				</select>
			</div>
	</div>
	<div class="fields clearboth">
	<br>	
			<div class="multi_pro_label">
				<b><?php echo elgg_echo('Product type');?></b>
			</div>	
			<div class="multi_pro_value">
				<select name="product_type_id">
					<?php echo $option_title;?>
				</select>
			</div>
	</div>	
	<div class="fields clearboth">
	<br>	
			<div class="multi_pro_label">
				<b><span style="color:red">*</span><?php echo elgg_echo('Description');?></b>
			</div>	
			<div class="multi_pro_value">
				<select name="storesbody">
					<?php echo $option_title;?>
				</select>
			</div>
	</div>
	<div class="fields clearboth">
	<br>	
			<div class="multi_pro_label">
				<b><?php echo elgg_echo('Image');?></b>
			</div>	
			<div class="multi_pro_value">
				<select name="product_image">
					<?php echo $option_title;?>
				</select>
			</div>
	</div>		
	<?php echo $fields;?>	
	<div class="fields clearboth">
	<br>	
		<div class="multi_pro_label">
			<b><?php echo elgg_echo('tags');?></b>
		</div>	
		<div class="multi_pro_value">
			<select name="storestags">
				<?php echo $option_title;?>
			</select>
		</div>
	</div>
		<div class="fields clearboth">
	<br>	
		<div class="multi_pro_label">
			<b><?php echo elgg_echo('Access');?></b>
		</div>	
		<div class="multi_pro_value">
			<select name="access_id">
				<?php echo $option_title;?>
			</select>
		</div>
	</div>	
	<div class="fields clearboth">
		<?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('Add')));?>
	    <?php echo elgg_view('input/securitytoken');?>
    </div>
	<div class="clearboth"></div>
</form>