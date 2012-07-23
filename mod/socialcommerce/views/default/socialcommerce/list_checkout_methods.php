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
	 * Elgg view - list checkout methods
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 

	global $CONFIG;
	//Depricated function replace
	$options = array('types'			=>	"object",
					'subtypes'			=>	"splugin_settings",
					'limit'				=>	99999,
				);
	$settings = elgg_get_entities($options);
	//$settings = get_entities('object','splugin_settings',0,'',9999);
	if($settings){
		$selected_checkout_methods = $settings[0]->checkout_methods;
		if(!is_array($selected_checkout_methods))
			$selected_checkout_methods = array($selected_checkout_methods);
		$checkout_methods = get_checkout_methods();
		$action = $CONFIG->checkout_base_url."{$CONFIG->pluginname}/checkout_process";
		$checkout_method_validation_text = elgg_echo('checkout:method:validation:text');
		$checkout_method_title_text = elgg_echo('checkout:method:title:text');
		$method_display = <<<EOF
			<script type="text/javascript">
				function checkout_method_validation(){
					if ($("input[name='checkout_method']").is(':checked')){
						return true;
					}else{
						alert("{$checkout_method_validation_text}");
						return false;
					}
				}
			</script>
			<div style='padding:10px 5px;'>
				<B>{$checkout_method_title_text}</B>
			</div>
			<form onsubmit="javascript:return checkout_method_validation();" name='checkout_method_selection' method='post' action='{$action}'>
EOF;
		$submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('checkout:select:checkout:method')));
		$i = 1;
		foreach ($selected_checkout_methods as $selected_checkout_method){
			//Depricated function replace
			$options = array(	'metadata_name_value_pairs'	=>	array('checkout_method' => $selected_checkout_method),
							'types'				=>	"object",
							'subtypes'			=>	"s_checkout",						
							'limit'				=>	1,
							
						);
			$CheckOut_settings = elgg_get_entities_from_metadata($options);
			//$CheckOut_settings = get_entities_from_metadata('checkout_method',$selected_checkout_method,'object','s_checkout',0,1);
			if($CheckOut_settings){
				$CheckOut_settings = $CheckOut_settings[0];	
			}
			
			$display_name = $CheckOut_settings->display_name;
			if(!$display_name)
				$display_name = $checkout_methods[$selected_checkout_method]->label;
			
			
			if($i == 1 && empty($_SESSION['CHECKOUT']['checkout_method'])){
				$checked = "checked='checked'";
			}else{
				$checked = "";
			}
			if($selected_checkout_method == $_SESSION['CHECKOUT']['checkout_method']){
				 $checked = "checked='checked'";
			}
			
			$i++;
			$method_display .= <<<EOF
				<div style='padding:5px;'>
					<input type='radio' name='checkout_method' value='{$selected_checkout_method}' {$checked}> 
					<span style='margin-left:5px;'>{$display_name}</span>
				</div>
EOF;
		}
		$method_display .= <<<EOF
				<div>
					{$submit_input}
					<input type="hidden" name="checkout_order" value="3">
				</div>
			</form>
EOF;
	}else{
		$method_display = elgg_echo('checkout:checkout:method:no:settings');	
	}
	echo $method_display;
?>