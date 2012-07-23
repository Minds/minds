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
	 * Elgg input - product type
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */

	$internalname = $vars['name'];
	if($internalname == ""){
		$internalname = 'mupload';
	}
	$entity = $vars['entity'];

	$store = $vars['store'];
	if($entity){
		$version_release = $entity->version_release;
		$version_summary = $entity->version_summary;
		$file_image .= "<div style='float:left;'>".elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $vars['entity']->mimetype, 'thumbnail' => $vars['entity']->thumbnail, 'stores_guid' => $vars['entity']->guid))."</div>";
		$file_image .= "<div class='change_product_file'><a href='javascript:void(0);' onclick='load_edit_product_detaile();'><b>".elgg_echo('product:edit:file')."</a></a></div><div class='clear'></div>";
		$file_image .= "<div id='product_file_change'>".elgg_view("input/file",array(
										'name' => $internalname,
										'value' => $value,
										)).'</div>';
	}else{
		$version_release = $_SESSION['product'][$internalname.'_version'];
		$version_summary = $_SESSION['product'][$internalname.'_version_summary'];
	}
	?>

<script language="javascript" type="text/javascript">
	function load_edit_product_detaile(){
		 $("#product_file_change").toggle('fast');
	}
</script>
	<div class ="version">
		<div id="multiple_version">
			<div class="add_multiple_version" id="version_update0">
			<!--  <a href="javascript:remove_version('version_update0')" class="del-btn">X</a>-->		
				<p>
					<label><span style="color: red;">*</span><?php echo elgg_echo('socialcommerce:multi_prod_ver:uploadfile'); ?></label>
					<?php 
					if($entity){
						echo $file_image;
					}else{
					?>
					<input type="file" class="input-file" name="<?php echo $internalname;?>" size="30"/>
					<?php
					}
					?>
				</p>
				<div class="clear"></div>
				<p>
					<label><span style="color: red;">*</span><?php echo elgg_echo('socialcommerce:multi_prod_ver:version:release'); ?></label>
					<input type="text" class="elgg-input-text" name="<?php echo $internalname;?>_version" size="30" value="<?php echo $version_release;?>"/>
				</p>	
				<p>
					<label><span style="color: red;">*</span><?php echo elgg_echo('socialcommerce:multi_prod_ver:version:summary'); ?></label>
					<textarea name="<?php echo $internalname;?>_version_summary" class="elgg-input-textarea"><?php echo $version_summary;?></textarea>
				</p>
			</div>
		</div><!--
		<p>
			<input type="button" class="ver_add_button" value="Upload version" onclick="javascript:add_new_version();"/>
		</p>
		--><div class="clear"></div>
	</div>	
	<?php unset($_SESSION['product']);?>
		