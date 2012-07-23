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
	 * Elgg view - list shipping methods
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
	if($settings && $CONFIG->no_shipping !=2){
		$selected_shipping_methods = $settings[0]->shipping_methods;
		if(!is_array($selected_shipping_methods))
			$selected_shipping_methods = array($selected_shipping_methods);
		$shipping_methods = get_shipping_methods();
		$action = $CONFIG->checkout_base_url."{$CONFIG->pluginname}/checkout_process";
		
		$shipping_method_select = elgg_echo('shipping:method:select');
		$shipping_method_validation_text = elgg_echo('shipping:method:validation:text');
		$method_display = <<<EOF
			<script>
				function shipping_method_validation(){
					if ($("input[name='shipping_method']").is(":checked")){
						return true;
					}else{
						alert("{$shipping_method_validation_text}");
						return false;
					}
				}
			</script>
			<div style='padding:10px 5px;'>
				<B>{$shipping_method_select}</B>
			</div>
			<form onsubmit='return shipping_method_validation();' name='shipping_method_selection' method='post' action='{$action}'>
EOF;
		$submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('checkout:select:shipping:method')));
		$index_count = 0;
		foreach ($selected_shipping_methods as $selected_shipping_method){
			//Depricated function replace
			$options = array(	'metadata_name_value_pairs'	=>	array('shipping_method' => $selected_shipping_method),
							'types'				=>	"object",
							'subtypes'			=>	"s_shipping",						
							'limit'				=>	1,
							
						);
			$shipping_settings = elgg_get_entities_from_metadata($options);
			//$shipping_settings = get_entities_from_metadata('shipping_method',$selected_shipping_method,'object','s_shipping',0,1);
			if($shipping_settings)
				$shipping_settings = $shipping_settings[0];
			
			if(file_exists($CONFIG->shipping_path.'/'.$selected_shipping_method.'/action.php')) {
				include_once($CONFIG->shipping_path.'/'.$selected_shipping_method."/action.php");
			}else{
				throw new PluginException(sprintf(elgg_echo('misconfigured:shipping:method'), $selected_shipping_method));
			}
			
			$products = $_SESSION['CHECKOUT']['product'];
			
			$function = "price_calc_".$selected_shipping_method;
			if(function_exists($function)){
				$prince = $function($products);
			}else {
				throw new PluginException(sprintf(elgg_echo('misconfigured:shipping:function'), $function));
			}
			
			$shipping_price = 0;
			if(is_array($prince)){
				foreach ($prince as $s_price)	{
					$shipping_price += $s_price;
				}
			}
			
			$display_name = $shipping_settings->display_name;
			if(!$display_name)
				$display_name = $shipping_methods[$selected_shipping_method]->label;			
			if($selected_shipping_method == $_SESSION['CHECKOUT']['shipping_method']){
				 $checked = "checked='checked'";
				 $selected_shipping_price = $shipping_price;
			}
			else
			{
				if((empty($_SESSION['CHECKOUT']['shipping_method']))){
					 $checked = "checked='checked'";
					 $selected_shipping_price = $shipping_price;				
				}
				else{
					$checked = "";
				}
			} 
			$display_shipping_price = get_price_with_currency($shipping_price);
			$method_display .= <<<EOF
				<div style='padding:5px;'>
					<input type='radio' name='shipping_method' id='shipping_method{$index_count}' onclick='javascript:radioOnChange({$index_count})'  value='{$selected_shipping_method}' {$checked}> 
					<input type='hidden' name='ship_price' id='shipping_price{$index_count}' value='{$shipping_price}'> 
					<span style='margin-left:5px;'>{$display_name}</span> <span style="font-weight:bold;color:#4F0A0A;">{$display_shipping_price}</span>
				</div>
EOF;
		$index_count++;
		}
		$method_display .= <<<EOF
				<script language="javascript" type="text/javascript"> 
					function radioOnChange(Index) {
						if(document.getElementById('shipping_price'+Index))
						document.getElementById('shipping_price').value = document.getElementById('shipping_price'+Index).value;
					}
				</script>
				<div>
				<input type='hidden' name='shipping_price' id='shipping_price' value='{$selected_shipping_price}'>
					{$submit_input}
					<input type="hidden" name="checkout_order" value="2">
				</div>
			</form>
EOF;
	}
	else{
		if($CONFIG->no_shipping ==2){
			
			$method_display = elgg_echo('checkout:shipping:method:no');	
		}
		else
		{
			$method_display = elgg_echo('checkout:shipping:method:no:settings');	
		}
	}
	echo $method_display;
?>