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
	 * Elgg address - Get Products in create_order form
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	global $CONFIG;
	$user =get_input('customer');
	$user = rtrim($user);
	$user_guid = get_user_by_username($user)->guid;
	if(!$user_guid) {
		$user_guid = get_user($user)->guid;// when the input is customer id
	}
	if($user_guid) {
		$wheres = array("(e.owner_guid != {$user_guid})");
		$options = array('types' => 'object',
			'subtypes' => 'stores',
			'limit'=>99999, 
			'wheres' => $wheres,
			'metadata_names' => 'status',
			'metadata_values' => 1);
		$product_lists = elgg_get_entities_from_metadata($options);
		$options_values = array() ;
		if($product_lists){
?>			
			<div style="height:200px;border:1px solid #9F9F9F;padding:10px;margin:10px 0;overflow-y:scroll;">
<?php 
			foreach ($product_lists as $product_list){
?>
				<div class="left" style="width:46%;border:1px solid #959B9F;padding:5px;background:#EFF8FF;margin:5px;">
					<div class="left" style="padding:2px; width: 20px;"><input type="checkbox" name="product[]" value="<?php echo $product_list->guid?>" /></div>
					<div class="left" style="padding:2px; float:left; width: 50px;"><img src="<?php echo $product_list->getIconURL('small');?>"/></div>
					<div class="left" style="padding:2px;width:200px; float: left;"><?php echo $product_list->title;?></div>
					<div class="left ajax_order_create" style="padding:2px;width:200px; float: left;"><?php 
					if($product_list->product_type_id == 2){
						echo elgg_view("output/multi_product_version",array('entity_guid'=>$product_list->guid,'internal_name'=>$product_list->guid."version_guid[]"));
					}
					?>
				</div>
				</div>
<?php				
			}
?>
			</div>
			<div><?php echo elgg_view("$CONFIG->pluginname}/extendCreateOrderView");?></div>
			<div class="fields clear">
				<div>
					<?php echo elgg_view('input/submit', array('name' => 'btn_order', 'value' => elgg_echo('stores:order:create')));?>
					<?php echo elgg_view('input/securitytoken'); ?>
				</div>
			</div>
			<div class="clear"></div>
<?php
		} else{
			echo "<div style='border:1px solid #8F4747;background:#FFEFEF;padding:5px;'>".elgg_echo("create:order:no:product")."</div>";
		}
	} else {
		echo "<div style='border:1px solid #8F4747;background:#FFEFEF;padding:5px;'>".elgg_echo("create:order:not:valid:username")."</div>";
	}
	