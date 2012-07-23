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
	 * Elgg cart - view
	 * 
	 * @package Elgg SocialCommerce check before delete category
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */
		global $CONFIG;
		$category = get_entity(get_input('category'));
		$product_type_id = get_input('product_type');
		$category_list = get_categories(0,$category->guid,$product_type_id,0);
		if($category){
			$options = array('metadata_name_value_pairs'	=>	array('category' => $category->guid,'status'=>1),
							'types'							=>	'object',
							'subtypes'						=>	'stores',
							'count'							=>	TRUE
							);
			
			$count = elgg_get_entities_from_metadata($options);
			if($count > 0){
				$lan_title  = elgg_echo('category:title:deletion');
				$lan_count = sprintf(elgg_echo('category:title:numberOfProduct'),$count);
				$lan_ressign = elgg_echo('category:title:reassign:cat');
				$root = $CONFIG->wwwroot;
			$result = <<<EOF
			<div id="product_inner">
				<div class="product_inner_head">
					{$lan_title}
					<div class="close_button">
						<a href="javascript:cancel();"<img src="{$root}mod/socialcommerce/images/close.gif" /></a>
					</div>
				</div>
				<div class="product_inner_middle">				
					{$lan_count}.<br/>
					{$lan_ressign}:
					<div class="product_inner_middle_scrolling">
						{$category_list}
					</div>
					<div class="product_inner_middle_button_wrap">
						<input style="float:left;" type="button" value="Cancel" onclick="javascript:cancel();" />
						<input style="float:right;" onclick="javascript:reassign();"  type="button" value="Continue >>" />
						<div class="clear"></div>
					</div> 
				</div>
			</div>
			
EOF;
			}else{
				$result = "";
			}
			echo $result;
			exit;	
		}	
		