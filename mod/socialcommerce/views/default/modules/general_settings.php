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
	+-------------------------------------------------div----------------------------+
	\*****************************************************************************/
	
	/**$('#view_ship').hide()
	 * Elgg modules - general settings view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	
	$checkout_methods = get_checkout_methods();
	$checkoutmethods = "";
	$shipping_methods = get_shipping_methods();
	$fund_withdraw_methods = get_fund_withdraw_methods();
	load_checkout_actions();
	$settings = $vars['entity'];
		
	if($settings){
		$settings = $settings[0];
		$settings_guid = $settings->guid;
		$selected_checkoutmethods = $settings->checkout_methods;
		$selected_shippingmethods = $settings->shipping_methods;
		$selected_fund_withdraw_methods = $settings->fund_withdraw_methods;
		$http_proxy_server = $settings->http_proxy_server;
		$http_proxy_port = $settings->http_proxy_port;
		$http_varify_ssl = $settings->http_varify_ssl;
		$default_view = $settings->default_view;
		$river_settings = $settings->river_settings;
		$allow_shipping_method = $settings->allow_shipping_method;
		$allow_tax_method = $settings->allow_tax_method;
		
		/* For Flat Rate additinal to store percetage*/
		$allow_socialcommerce_store_percetage = $settings->allow_socialcommerce_store_percetage;
		$allow_socialcommerce_flat_amount = $settings->allow_socialcommerce_flat_amount;
		
		
		if($allow_socialcommerce_store_percetage > 0)	{
			$allow_socialcommerce_store_percetage_chk='checked';
		}else {
			$allow_socialcommerce_store_percetage_chk='';
		}
		if($allow_socialcommerce_flat_amount > 0)	{
			$allow_socialcommerce_flat_amount_chk='checked';
		}else {
			$allow_socialcommerce_flat_amount_chk='';
		}
		
		
		if(!is_array($river_settings))
			$river_settings = array($river_settings);
		if($http_varify_ssl){
			$http_varify_ssl_checked = "checked";
		}else{
			$http_varify_ssl_checked = "";
		}
		$allow_add_product = $settings->allow_add_product;
		if($allow_add_product){
			$allow_add_product_checked = "checked";
		}else{
			$allow_add_product_checked = "";
		}
		$allow_add_cart = $settings->allow_add_cart;
		if($allow_add_cart){
			$allow_add_cart_checked = "checked";
		}else{
			$allow_add_cart_checked = "";
		}
		$hide_system_message =  $settings->hide_system_message;
		if($hide_system_message){
			$hide_system_message_checked = "checked";
		}else{
			$hide_system_message_checked = "";
		}
		$send_mail_on_outofstock =  $settings->send_mail_on_outofstock;
		if($send_mail_on_outofstock){
			$send_mail_on_outofstock_checked = "checked";
		}else{
			$send_mail_on_outofstock_checked = "";
		}
		$withdraw_option = $settings->withdraw_option;
		$allow_add_coupon_code = $settings->allow_add_coupon_code;
		if($allow_add_coupon_code){
			$allow_add_coupon_code_checked = "checked";
		}else{
			$allow_add_coupon_code_checked = "";
		}
		$allow_add_related_product = $settings->allow_add_related_product;
		if($allow_add_related_product){
			$allow_add_related_product_checked = "checked";
		}else{
			$allow_add_related_product_checked = "";
		}
		if(!$withdraw_option)
			$withdraw_option = 'instant';
		$holding_days = $settings->holding_days;
		
		//For http://url
		$https_allow = $settings->https_allow;
		if($https_allow){
			$https_allow_checked = "checked";
		}
		else{
			$https_allow_checked = "";
		}
		$https_url_text= $settings->https_url_text;
		
		$allow_single_click_to_cart=$settings->allow_single_click_to_cart;
		if($allow_single_click_to_cart){
			$allow_single_click_to_cart_checked = "checked";
		}else{
			$allow_single_click_to_cart_checked = "";
		}
		if(!empty($settings->socialcommerce_key)){
			$socialcommerce_key = $settings->socialcommerce_key;
		}
		$allow_multiple_version_digit_product = $settings->allow_multiple_version_digit_product;
		if($allow_multiple_version_digit_product){
			$allow_multiple_version_digit_product_checked = "checked";
		}else{
			$allow_multiple_version_digit_product_checked = "";
		}
		
		$share_this = $settings->share_this;
		
		// For ftp image upload	
		$ftp_upload_allow = $settings->ftp_upload_allow;
		if($ftp_upload_allow){
			$ftp_upload_allow_checked = "checked";
		}
		else{
			$ftp_upload_allow_checked = "";
		}
		$ftp_host_url= $settings->ftp_host_url;
		$ftp_port= $settings->ftp_port;
		$ftp_user=html_entity_decode($settings->ftp_user);
		$ftp_password= html_entity_decode($settings->ftp_password);
		$ftp_upload_dir = $settings->ftp_upload_dir;
		$ftp_http_path = $settings->ftp_http_path;
		$ftp_base_path = $settings->ftp_base_path;
		if($ftp_base_path == ""){
			$ftp_base_path = elgg_echo('settings:upload:product:label:upload:base:path:value');
		}
		if($ftp_http_path == ""){
			$ftp_http_path = elgg_echo('settings:upload:product:txt:ftp_http_path:value');
		}
		
		$action_dir_path = $CONFIG->wwwroot."action/".$CONFIG->pluginname."/get_dir_list";
	}
	if($checkout_methods){
		foreach ($checkout_methods as $value=>$checkout_method){
			if(is_array($selected_checkoutmethods)){
				$selected_checkoutmethods = array_map('strtolower', $selected_checkoutmethods);
				if(!in_array(strtolower($value),$selected_checkoutmethods)){
					$selected = "";
				}else{
					$selected = "checked = \"checked\"";
				}
			}else{
				if (strtolower($value) != strtolower($selected_checkoutmethods)) {
		            $selected = "";
		        } else {
		            $selected = "checked = \"checked\"";
		        }
			}
			$checkoutmethods .= '<div class="checkout_selection_div"><input type="checkbox" name="checkout_method[]" value="'.$value.'" '.$selected.'>'.$checkout_method->label.'<span style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, \''. $checkout_method->label. '\', \''. elgg_echo('checkout:'.$value.':help').'\')" src="'. $CONFIG->wwwroot.'mod/'. $CONFIG->pluginname.'/images/help.gif" border="0"></span></div>';
		}
	}else {
		$checkoutmethods = "No methods available";
	}
	
	if($shipping_methods){
		foreach ($shipping_methods as $value=>$shipping_method){
			if(is_array($selected_shippingmethods)){
				$selected_shippingmethods = array_map('strtolower', $selected_shippingmethods);
				if(!in_array(strtolower($value),$selected_shippingmethods)){
					$selected = "";
				}else{
					$selected = "checked = \"checked\"";
				}
			}else{
				if (strtolower($value) != strtolower($selected_shippingmethods)) {
		            $selected = "";
		        } else {
		            $selected = "checked = \"checked\"";
		        }
			}
			$shippingmethods .= '<div class="checkout_selection_div"><input type="checkbox" name="shipping_method[]" value="'.$value.'" '.$selected.'>'.$shipping_method->label.'<span style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, \''. $shipping_method->label. '\', \''. elgg_echo('shipping:'.$value.':help').'\')" src="'. $CONFIG->wwwroot.'mod/'. $CONFIG->pluginname.'/images/help.gif" border="0"></span></div>';
		}
	}else {
		$shippingmethods = "No shipping methods available";
	}
	
	if($fund_withdraw_methods){
		foreach ($fund_withdraw_methods as $value=>$fund_withdraw_method){
			if(is_array($selected_fund_withdraw_methods)){
				$selected_fund_withdraw_methods = array_map('strtolower', $selected_fund_withdraw_methods);
				if(!in_array(strtolower($value),$selected_fund_withdraw_methods)){
					$selected = "";
				}else{
					$selected = "checked = \"checked\"";
				}
			}else{
				if (strtolower($value) != strtolower($selected_fund_withdraw_methods)) {
		            $selected = "";
		        } else {
		            $selected = "checked = \"checked\"";
		        }
			}
			$fundwithdraw_methods .= '<div class="checkout_selection_div"><input type="checkbox" name="fund_withdraw_method[]" value="'.$value.'" '.$selected.'>'.$fund_withdraw_method->label.'<span style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, \''. $fund_withdraw_method->label. '\', \''. elgg_echo('withdraw:'.$value.':help').'\')" src="'. $CONFIG->wwwroot.'mod/'. $CONFIG->pluginname.'/images/help.gif" border="0"></span></div>';
		}
	}else {
		$fundwithdraw_methods = "No withdraw methods available";
	}
	
	// For check the check-out url
		if($https_url_text=="")
		{
			$https_url_text = str_replace("http://", "https://", $CONFIG->wwwroot);

		}
	//// For check the check out url-----END

	// dowload version
	$download_newversion_allow = $settings->download_newversion_allow;
	if($download_newversion_allow){
		$download_newversion_checked = "checked";
	}else{
		$download_newversion_checked = "";
	}
	$download_newversion_days = $settings->download_newversion_days;
	
	$stores_percentage = $settings->socialcommerce_percentage;
	$store_flat_amount = $settings->socialcommerce_flat_amount;
	$min_withdraw_amount = $settings->min_withdraw_amount;
	$action = $CONFIG->wwwroot.'action/'.$CONFIG->pluginname.'/manage_socialcommerce';
?>
<script>
	function showData(){
			$('#view_ship').show();
	}
	
	function hideData(){
			$('#view_ship').hide();
	}
	
	function check_holding_date(method){
		if($(method).val() == 'escrow' || $(method).val() == 'moderation_escrow'){
			$("#holding_days").css({'display':'block'});
		}else{
			$("#holding_days").css({'display':'none'});
		}
	}
	
	function check_checkout_url(method){
		if(method.checked){
			$("#https_url_text").css({'display':'block'});
		}
		else{
			$("#https_url_text").css({'display':'none'});
		}
	}
	
	function check_ftp_upload(method){
		if(method.checked){
			$("#ftp_details").css({'display':'block'});
		}
		else{
			$("#ftp_details").css({'display':'none'});
		}
	}
	
	function check_download_versionnew(method){
		if(method.checked){
			$("#download_newversion_text").css({'display':'block'});
		}
		else{
			$("#download_newversion_text").css({'display':'none'});
		}
	}
	
	function settings_form_check(){
		if($('[name=allow_store_percentage]:checked').val()>0)
		{
			if($('[name=socialcommerce_percentage]').val()>0)
			{
				;
			}
			else
			{
				alert("Please enter the store percetage");
				$('[name=socialcommerce_percentage]').focus();
				return false;
			}
		}
		if($('[name=allow_store_flat_amount]:checked').val()>0)
		{
			if($('[name=socialcommerce_flat_amount]').val()>0)
			{
				;
			}
			else
			{
				alert("Please enter the flat amount");
				$('[name=socialcommerce_flat_amount]').focus();
				return false;
			}
		}
		if($('[name=ftp_upload_allow]:checked').val()>0)
		{
			if($('[name=ftp_host_url]').val()=="")
			{
				alert("Please enter the host name");
				$('[name=ftp_host_url]').focus();
				return false;
			}
			if($('[name=ftp_user]').val()=="")
			{
				alert("Please enter the user name");
				$('[name=ftp_user]').focus();
				return false;
			}
			if($('[name=ftp_password]').val()=="")
			{
				alert("Please enter the password");
				$('[name=ftp_password]').focus();
				return false;
			}

			if($('[name=ftp_upload_dir]').val()=="")
			{
				alert("Please enter the upload directoy");
				$('[name=ftp_upload_dir]').focus();
				return false;
			}
			if($('[name=ftp_http_path]').val()=="")
			{
				alert("Please enter the  image url");
				$('[name=ftp_http_path]').focus();
				return false;
			}
			if($('[name=ftp_base_path]').val()=="")
			{
				alert("Please enter the document root");
				$('[name=ftp_base_path]').focus();
				return false;
			}	
		}
		if($('[name=withdraw_option]:checked').val() == 'escrow' || $('[name=withdraw_option]:checked').val() == 'moderation_escrow'){
			if($('[name=holding_days]').val() > 0){
				//return true;
				;
			}else{
				alert("Please enter holding days");
				$('[name=holding_days]').focus();
				return false;
			}
		}
		if($('[name=https_allow]:checked').val()>0){
			if($('[name=https_url_text]').val() ==""){
				alert("Please enter the https url");
				return false;
			}
		}

		if($('[name=download_newversion_allow]:checked').val()>0){			
			var nofdays = $('[name=download_newversion_days]').val();
			if(isNaN(nofdays) || nofdays==""){
				alert('<?php echo elgg_echo('settings:download:newversion:validation:js:error'); ?>');
				$('[name=download_newversion_days]').focus();
				return false;
			}
		}	
		return true;
	}
	
	function isNumber(n) {
		  return !isNaN(parseFloat(n)) && isFinite(n);
	}
	
	function list_ftp_dir(){
		var elgg_token = $('[name=__elgg_token]');
		var elgg_ts = $('[name=__elgg_ts]');
		var ftp_host_url = $('[name=ftp_host_url]');
		var ftp_port = $('[name=ftp_port]');
		var ftp_user = $('[name=ftp_user]');
		var ftp_password = $('[name=ftp_password]');
		var selected =  $('[name=ftp_base_path]');
		var get_dir_path = "<?php echo $action_dir_path;?>";
		document.getElementById('root_directories').innerHTML = '<div style="margin-left:100px;"><img  src="<?php echo $CONFIG->wwwroot;?>mod/socialcommerce/images/working.gif" ></img><div class="clear"><div></div>';											
		$.post(get_dir_path, {	
			ftp_host_url:ftp_host_url.val(), 							
			ftp_port: ftp_port.val(),
			ftp_user:ftp_user.val(), 							
			ftp_password: ftp_password.val(),
			selected: selected.val(),
			__elgg_token: elgg_token.val(),
			__elgg_ts: elgg_ts.val()
				},
				function(data){
					if(data){
						//div.load(data);
						document.getElementById('root_directories').innerHTML = data;
					}										
				});	
						
	}
	
	// Function used in the radio buttion selection, directory listing in from the ajax call ftp 
	function get_dir(data){
		var base_path =  $('[name=ftp_base_path]');
			base_path.val(data.value+'/');
	}	
</script>
<div class="basic">
	<?php if($CONFIG->UpgradeSocialcommerce === true){ ?>
		<form method="post" action="<?php echo $CONFIG->wwwroot.'action/'.$CONFIG->pluginname.'/manage_socialcommerce'; ?>">
			<div class="checkout_title"><B><?php echo elgg_echo('socialcommerce:settings:version:upgrade'); ?></B></div>
			<div class="clear general">
				<?php echo elgg_view($CONFIG->pluginname.'/upgrade_version');?>
				<input type="hidden" name="manage_action" value="versionUpdate">
				<?php echo elgg_view('input/securitytoken'); ?>
			</div>
		</form>
	<?php }?>
	<form onsubmit="return settings_form_check()" method="post" action="<?php echo $action; ?>">
	<!-- 
		<div class="checkout_title"><B><?php  // echo elgg_echo('socialcommerce:key'); ?></B></div>
		<div class="">
			<div class="settings_field_left" style="width:120px;"><?php //  echo elgg_echo('socialcommerce:key'); ?></div>
			<div class="left" style="padding:3px 5px;">:</div>
			<div class="settings_field_right" style="width:300px;"><?php // echo elgg_view('input/text',array('name'=>'socialcommerce_key','value'=>$socialcommerce_key)); ?></div>
			<div class="left" style="padding:0 5px 0 10px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php // echo elgg_echo('socialcommerce:key'); ?>', '<?php // echo elgg_echo('socialcommerce:key:help'); ?>')" src="<?php // echo $CONFIG->wwwroot; ?>mod/<?php // echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
			<div class="clear"></div>
		</div>
		 -->		 
	 	<input type="hidden" name="socialcommerce_key" value="<?php echo $socialcommerce_key;?>">
		<div class="checkout_title"><B><?php echo elgg_echo('common:settings'); ?></B></div>
		<div class="checkout_body">
			<div>
				<div class="clear general">
					<div class="settings_field_left" style="width:120px;"><?php echo elgg_echo('stores:percentage'); ?></div>
					<div class="left" style="padding:3px 5px;">:</div>
					<div class="settings_field_right""><?php echo elgg_view('input/text',array('name'=>'socialcommerce_percentage','value'=>$stores_percentage)); ?></div>
					<div class="left" style="padding:0 5px 0 10px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('stores:percentage'); ?>', '<?php echo elgg_echo('stores:percentage:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
					<div class="left" style="padding:3px 0"><input type="checkbox" name="allow_store_percentage" <?php echo $allow_store_percetage_checked; ?> value="1" <?php echo $allow_socialcommerce_store_percetage_chk;?>></div>
					<div class="left" style="padding:3px 5px;"></div>					
					<div class="left"><?php echo elgg_echo('settings:add:store:percentage:allow'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:add:store:percentage:allow:help:title'); ?>', '<?php echo elgg_echo('settings:add:store:percentage:allow:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
				</div>
				<div class="clear general">
					<div class="settings_field_left" style="width:120px;"><?php echo elgg_echo('settings:add:store:flat:amount'); ?></div>
					<div class="left" style="padding:3px 5px;">:</div>
					<div class="settings_field_right""><?php echo elgg_view('input/text',array('name'=>'socialcommerce_flat_amount','value'=>$store_flat_amount)); ?></div>
					<div class="left" style="padding:0 5px 0 10px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:add:store:flat:amount:help:title'); ?>', '<?php echo elgg_echo('settings:add:store:flat:amount:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
					<div class="left" style="padding:3px 0"><input type="checkbox" name="allow_store_flat_amount" <?php echo $allow_store_flat_rate_checked; ?> value="1" <?php echo $allow_socialcommerce_flat_amount_chk?>></div>
					<div class="left" style="padding:3px 5px;"></div>					
					<div class="left"><?php echo elgg_echo('settings:add:store:flat:amount:allow'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:add:store:flat:amount:allow:help:title'); ?>', '<?php echo elgg_echo('settings:add:store:flat:amount:allow:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
				</div>
				
				<div class="clear general">
					<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:add:product:allow'); ?></B></div>
					<div class="left" style="padding:3px 0"><input type="checkbox" name="allow_add_product" <?php echo $allow_add_product_checked; ?> value="1"></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('settings:add:product:allow:desc'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:add:product:allow'); ?>', '<?php echo elgg_echo('settings:add:product:allow:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
				</div>
				<div class="clear general">
					<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:add:cart:allow'); ?></B></div>
					<div class="left" style="padding:3px 0"><input type="checkbox" name="allow_add_cart" <?php echo $allow_add_cart_checked; ?> value="1"></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('settings:add:cart:allow:desc'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:add:cart:allow'); ?>', '<?php echo elgg_echo('settings:add:cart:allow:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
				</div>
				<div class="clear general">
					<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:add:coupon:code:allow'); ?></B></div>
					<div class="left" style="padding:3px 0"><input type="checkbox" name="allow_add_coupon_code" <?php echo $allow_add_coupon_code_checked; ?> value="1"></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('settings:add:coupon:code:allow:desc'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:add:coupon:code:allow'); ?>', '<?php echo elgg_echo('settings:add:coupon:code:allow:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
				</div>
				<div class="clear general">
					<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:add:related:product:allow'); ?></B></div>
					<div class="left" style="padding:3px 0"><input type="checkbox" name="allow_add_related_product" <?php echo $allow_add_related_product_checked; ?> value="1"></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('settings:add:related:product:allow:desc'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:add:related:product:allow'); ?>', '<?php echo elgg_echo('settings:add:related:product:allow:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
				</div>
				<div class="clear general">
					<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:default:view'); ?></B></div>
					<div class="left" style="padding:3px 0"><input type="radio" name="default_view" <?php if($default_view == 'list') echo 'checked="checked"'; ?> value="list"></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('list'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:default:view'); ?>', '<?php echo elgg_echo('settings:default:view:list:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="left" style="width:33px;">&nbsp;</div>
					<div class="left" style="padding:3px 0"><input type="radio" name="default_view" <?php if($default_view == 'gallery') echo 'checked="checked"'; ?> value="gallery"></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('gallery'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:default:view'); ?>', '<?php echo elgg_echo('settings:default:view:gallery:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
				</div>
				<div class="clear general">
					<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:hide:system:message'); ?></B></div>
					<div class="left" style="padding:3px 0"><input type="checkbox" name="hide_system_message" <?php echo $hide_system_message_checked; ?> value="1"></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('settings:hide:system:message:desc'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:hide:system:message'); ?>', '<?php echo elgg_echo('settings:hide:system:message:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
				</div>
				<div class="clear general">
					<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:send:mail:on:outofstock'); ?></B></div>
					<div class="left" style="padding:3px 0"><input type="checkbox" name="send_mail_on_outofstock" <?php echo $send_mail_on_outofstock_checked; ?> value="1"></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('settings:send:mail:on:outofstock:desc'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:send:mail:on:outofstock'); ?>', '<?php echo elgg_echo('settings:send:mail:on:outofstock:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
				</div>
				<div class="clear general">
					<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:https:for:checkout'); ?></B></div>				
					<div class="left" style="padding:3px 0"><input type="checkbox" onclick="check_checkout_url(this);" name="https_allow" <?php echo $https_allow_checked; ?> value="1"></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('settings:https:yes'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:https:for:checkout'); ?>', '<?php echo elgg_echo('settings:https:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear" style="margin-bottom: 5px;"></div>
						<div id="https_url_text" class="<?php if($https_allow) echo "display_block"; else echo "display_none";?>">
							<div class="settings_field_left" style="width:55px;"><span style="color:red;">* </span><?php echo elgg_echo('settings:https:Url'); ?></div>
							<div class="left" style="padding:3px 5px;">:</div>
							<div style="width:350px" class="settings_field_right"><?php echo elgg_view('input/text',array('name'=>'https_url_text','value'=>$https_url_text)); ?></div>
							<div class="clear"></div>
						</div>												
				</div>
				<div class="clear general">
					<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:add:product:singleclick'); ?></B></div>
					<div class="left" style="padding:3px 0"><input type="checkbox" name="allow_single_click_to_cart" <?php echo $allow_single_click_to_cart_checked; ?> value="1"></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('settings:add:product:singleclick:yes'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:add:product:singleclick'); ?>', '<?php echo elgg_echo('settings:add:product:singleclick:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
				</div>
				<div class="clear general">
					<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:add:multiple:product:version:label'); ?></B></div>
					<div class="left" style="padding:3px 0"><input type="checkbox" name="allow_mult_ver_digital_product" <?php echo $allow_multiple_version_digit_product_checked; ?> value="1"></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('settings:add:multiple:product:version:yes'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:add:multiple:product:version:help:index'); ?>', '<?php echo elgg_echo('settings:add:multiple:product:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
				</div>
				<div class="clear general">
					<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:upload:product:image:ftp:label'); ?></B></div>
					<div class="left" style="padding:3px 0"><input type="checkbox" name="ftp_upload_allow" onclick="javascript:check_ftp_upload(this);" <?php echo $ftp_upload_allow_checked; ?> value="1"></input></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('settings:upload:product:image:ftp:yes'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:upload:product:image:ftp:index'); ?>', '<?php echo elgg_echo('settings:upload:product:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear" style="margin-bottom: 5px;"></div>
						<div id="ftp_details" style="padding-left:10px;" class="<?php if($ftp_upload_allow) echo "display_block"; else echo "display_none";?>">
							<div>
								<div class="settings_field_left" style="width:100px;">
									<div class="left">
										<?php echo elgg_echo('settings:upload:product:label:port'); ?>:
									</div>
								</div>		
								<div class="left" style="width: 50px;">								
									<?php echo elgg_view('input/text',array('name'=>'ftp_port','value'=>$ftp_port)); ?>
								</div>
							</div>
							<div class="padding-top">										
								<div class="settings_field_left" style="width:100px;">
									<div class="left"><span style="color:red;">* </span>
										<?php echo elgg_echo('settings:upload:product:label:host'); ?>:
									</div>
								</div>		
								<div class="left" style="width: 500px;">								
									<?php echo elgg_view('input/text',array('name'=>'ftp_host_url','value'=>$ftp_host_url)); ?>
								</div>	
							</div>
							<div class="padding-top">										
								<div class="settings_field_left" style="width:100px;">
									<div class="left"><span style="color:red;">* </span>
										<?php echo elgg_echo('settings:upload:product:label:user'); ?>:
									</div>
								</div>		
								<div class="left" style="width: 200px;">								
									<?php echo elgg_view('input/text',array('name'=>'ftp_user','value'=>$ftp_user)); ?>
								</div>	
							</div>
							<div class="padding-top">										
								<div class="settings_field_left" style="width:100px;">
									<div class="left"><span style="color:red;">* </span>
										<?php echo elgg_echo('settings:upload:product:label:password'); ?>:
									</div>
								</div>		
								<div class="left" style="width: 200px;">								
									<?php echo elgg_view('input/password',array('name'=>'ftp_password','value'=>$ftp_password)); ?>
								</div>	
							</div>
							<div class="padding-top">										
								<div class="settings_field_left" style="width:100px;">
									<div class="left"><span style="color:red;">* </span>
										<?php echo elgg_echo('settings:upload:product:label:upload:dir'); ?>:
									</div>
								</div>		
								<div class="left" style="width: 500px;">								
									<?php echo elgg_view('input/text',array('name'=>'ftp_upload_dir','value'=>$ftp_upload_dir)); ?>
								</div>	
							</div>
							<div class="padding-top">										
								<div class="settings_field_left" style="width:100px;">
									<div class="left"><span style="color:red;">* </span>
										<?php echo elgg_echo('settings:upload:product:label:http:path'); ?>:
									</div>
								</div>		
								<div class="left" style="width: 500px;">								
									<?php echo elgg_view('input/text',array('name'=>'ftp_http_path','value'=>$ftp_http_path)); ?>
								</div>	
							</div>
							<div class="padding-top">										
								<div class="settings_field_left" style="width:100px;">
									<div class="left"><span style="color:red;">* </span>
										<?php echo elgg_echo('Get document root'); ?>:
									</div>
								</div>		
								<div class="left" style="width: 500px;">								
									<input type="button" value="<?php echo elgg_echo('settings:upload:product:label:get:dir');?>" onclick="list_ftp_dir();"></input>
								</div>	
							</div>
							<!-- Ajax call to get the values form root dir listing -->
							<div class="clear"></div>
							<div id="root_directories">							
							</div>							
							<div class="padding-top">										
								<div class="settings_field_left" style="width:100px;">
									<div class="left"><span style="color:red;">* </span>
										<?php echo elgg_echo('settings:upload:product:label:upload:base:path'); ?>:
									</div>
								</div>		
								<div class="left" style="width: 500px;">								
									<?php echo elgg_view('input/text',array('name'=>'ftp_base_path','value'=>$ftp_base_path)); ?>
								</div>	
							</div>																					
							<div class="clear"></div>
						</div>
				</div>
				<div class="clear general">
					<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:download:newversion'); ?></B></div>				
					<div class="left" style="padding:3px 0"><input type="checkbox" onclick="check_download_versionnew(this);" name="download_newversion_allow" <?php echo $download_newversion_checked; ?> value="1"></div>
					<div class="left" style="padding:3px 5px;"></div>
					<div class="left"><?php echo elgg_echo('settings:download:newversion:yes'); ?></div>
					<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:download:newversion:title'); ?>', '<?php echo elgg_echo('settings:download:newversion:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear" style="margin-bottom: 5px;"></div>
						<div id="download_newversion_text" class="<?php if($download_newversion_allow) echo "display_block"; else echo "display_none";?>">
							<div class="settings_field_left" style="width:55px;"><span style="color:red;">* </span><?php echo elgg_echo('settings:download:newversion:days'); ?></div>
							<div class="left" style="padding:3px 5px;">:</div>
							<div class="settings_field_right"><?php echo elgg_view('input/text',array('name'=>'download_newversion_days','value'=>$download_newversion_days)); ?></div>
							<div class="clear"></div>
						</div>												
				</div>
				<div class="clear general">
				<?php echo elgg_view('socialcommerce/extend_general_settings',array('settings'=>$settings));?>
				</div>
			<div class="clear"></div>
		</div>
		<div class="checkout_title"><B><?php echo elgg_echo('checkout:methods'); ?></B></div>
		<div class="checkout_body">
			<?php echo $checkoutmethods; ?>
		</div>
		<div class="checkout_title"><B><?php echo elgg_echo('shipping:methods'); ?></B></div>
		<div class="checkout_body">
			<input type="radio" <?php if($allow_shipping_method == 1) echo "checked='checked'"; ?> name="allow_shipping_method" id="sel_ship1"  onClick ="showData();" value="1"><?php echo elgg_echo('ship:display:view');?></input>
			<input type="radio" <?php if($allow_shipping_method == 2 || $allow_shipping_method == '') echo "checked='checked'"; ?> name="allow_shipping_method" id="sel_ship2"  onClick ="hideData();" value="2" ><?php echo elgg_echo('ship:no:display:view'); ?></input>
			<?php 
				if($allow_shipping_method == 1)
					$style = "display:block;";
				else
					$style = "display:none;";
			?>
			<div id="view_ship" style="<?php echo $style; ?>">
				<?php echo $shippingmethods; ?>
			</div>
		</div>
		
		<div class="checkout_title"><B><?php echo elgg_echo('settings:system:shipping'); ?></B></div>
		<div class="checkout_body">
			<p><input type="radio"  id="ship_notax" name="allow_tax_method" value="1" <?php if($allow_tax_method == 1|| $allow_tax_method == '') echo "checked='checked'"; ?> ><?php echo elgg_echo('ship:tax:no:forall');?></input></p>
			<p><input type="radio"  id="ship_tax" name="allow_tax_method" value="2" <?php if($allow_tax_method == 2) echo "checked='checked'"; ?>><?php echo elgg_echo('ship:tax:countrywise'); ?></input></p>
			<p><input type="radio"  id="ship_storetax"  name="allow_tax_method" value="3" <?php if($allow_tax_method == 3) echo "checked='checked'"; ?>><?php echo elgg_echo('ship:tax:stockwise'); ?></input></p>
		</div>
		
		<div class="checkout_title"><B><?php echo elgg_echo('fund:withdraw:settings'); ?></B></div>
		<div class="checkout_body">
			<div class="clear general">
				<div class="settings_field_left"><?php echo elgg_echo('minimum:withdraw:amount'); ?></div>
				<div class="left" style="padding:3px 5px;">:</div>
				<div class="settings_field_right"><?php echo elgg_view('input/text',array('name'=>'min_withdraw_amount','value'=>$min_withdraw_amount)); ?></div>
				<div class="left" style="padding:0 5px 0 10px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('minimum:withdraw:amount'); ?>', '<?php echo sprintf(elgg_echo('minimum:withdraw:amount:help'),get_price_with_currency(100)); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
				<div class="clear"></div>
			</div>
			<div class="clear general">
				<div style="padding:5px 0;"><B><?php echo elgg_echo('settings:withdraw:options'); ?></B></div>
				<div class="left" style="padding:3px 0"><input onclick="check_holding_date(this);" type="radio" name="withdraw_option" <?php if($withdraw_option == 'instant') echo 'checked="checked"'; ?> value="instant"></div>
				<div class="left" style="padding:3px 5px;"></div>
				<div class="left"><?php echo elgg_echo('instant'); ?></div>
				<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:withdraw:options').":".elgg_echo('instant'); ?>', '<?php echo elgg_echo('settings:withdraw:instant:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
				<div class="left" style="width:33px;">&nbsp;</div>
				<div class="left" style="padding:3px 0"><input onclick="check_holding_date(this);" type="radio" name="withdraw_option" <?php if($withdraw_option == 'moderation') echo 'checked="checked"'; ?> value="moderation"></div>
				<div class="left" style="padding:3px 5px;"></div>
				<div class="left"><?php echo elgg_echo('moderation'); ?></div>
				<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:withdraw:options').":".elgg_echo('moderation'); ?>', '<?php echo elgg_echo('settings:withdraw:moderation:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
				<div class="clear" style="margin-bottom: 5px;"></div>
				<div class="left" style="padding:3px 0"><input onclick="check_holding_date(this);" type="radio" name="withdraw_option" <?php if($withdraw_option == 'escrow') echo 'checked="checked"'; ?> value="escrow"></div>
				<div class="left" style="padding:3px 5px;"></div>
				<div class="left"><?php echo elgg_echo('escrow'); ?></div>
				<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:withdraw:options').":".elgg_echo('escrow'); ?>', '<?php echo elgg_echo('settings:withdraw:escrow:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
				<div class="left" style="width:30px;">&nbsp;</div>
				<div class="left" style="padding:3px 0"><input onclick="check_holding_date(this);" type="radio" name="withdraw_option" <?php if($withdraw_option == 'moderation_escrow') echo 'checked="checked"'; ?> value="moderation_escrow"></div>
				<div class="left" style="padding:3px 5px;"></div>
				<div class="left"><?php echo elgg_echo('moderation_escrow'); ?></div>
				<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:withdraw:options').":".elgg_echo('moderation_escrow'); ?>', '<?php echo elgg_echo('settings:withdraw:moderation:escrow:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
				<div class="clear" style="margin-bottom: 5px;"></div>
				<div id="holding_days" class="<?php if($withdraw_option == 'escrow' || $withdraw_option == 'moderation_escrow') echo "display_block"; else echo "display_none";?>">
					<div class="settings_field_left" style="width:90px;"><span style="color:red;">* </span><?php echo elgg_echo('holding:days'); ?></div>
					<div class="left" style="padding:3px 5px;">:</div>
					<div class="settings_field_right"><?php echo elgg_view('input/text',array('name'=>'holding_days','value'=>$holding_days)); ?></div>
					<div class="left" style="padding: 0pt 5px 0pt 10px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('holding:days'); ?>', '<?php echo sprintf(elgg_echo('holding:days:help')); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
					<div class="clear"></div>
				</div>
			</div>
			<div style="padding:5px 0;"><B><?php echo elgg_echo('fund:withdraw:methods'); ?></B></div>
			<?php echo $fundwithdraw_methods; ?>
		</div>
		<div class="checkout_title"><B><?php echo elgg_echo('river:management'); ?></B></div>
		<div class="checkout_body">
			<div><?php echo elgg_echo("river:management:description")?></div>
			<div>
				<div style="padding:5px 0;"></div>
				<div class="left" style="padding:3px 0"><input type="checkbox" name="river_settings[]" <?php if(in_array('product_add',$river_settings)) echo 'checked="checked"'; ?> value="product_add"></div>
				<div class="left" style="padding:3px 5px;"></div>
				<div class="left"><?php echo elgg_echo('add:product'); ?></div>
				<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('river:management'); ?>', '<?php echo elgg_echo('river:product:add:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
				<div class="left" style="width:33px;">&nbsp;</div>
				<div class="left" style="padding:3px 0"><input type="checkbox" name="river_settings[]" <?php if(in_array('product_update',$river_settings)) echo 'checked="checked"'; ?> value="product_update"></div>
				<div class="left" style="padding:3px 5px;"></div>
				<div class="left"><?php echo elgg_echo('update:product'); ?></div>
				<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('river:management'); ?>', '<?php echo elgg_echo('river:product:update:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
				<div class="clear"></div>
				
				<div class="left" style="padding:3px 0"><input type="checkbox" name="river_settings[]" <?php if(in_array('cart_add',$river_settings)) echo 'checked="checked"'; ?> value="cart_add"></div>
				<div class="left" style="padding:3px 5px;"></div>
				<div class="left"><?php echo elgg_echo('add:cart'); ?></div>
				<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('river:management'); ?>', '<?php echo elgg_echo('river:cart:add:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
				<div class="left" style="width:33px;">&nbsp;</div>
				<div class="left" style="padding:3px 0"><input type="checkbox" name="river_settings[]" <?php if(in_array('product_checkout',$river_settings)) echo 'checked="checked"'; ?> value="product_checkout"></div>
				<div class="left" style="padding:3px 5px;"></div>
				<div class="left"><?php echo elgg_echo('checkout'); ?></div>
				<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('river:management'); ?>', '<?php echo elgg_echo('river:product:checkout:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
				<div class="clear"></div>
				
				<div class="left" style="padding:3px 0"><input type="checkbox" name="river_settings[]" <?php if(in_array('wishlist_add',$river_settings)) echo 'checked="checked"'; ?> value="wishlist_add"></div>
				<div class="left" style="padding:3px 5px;"></div>
				<div class="left"><?php echo elgg_echo('add:wishlist'); ?></div>
				<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('river:management'); ?>', '<?php echo elgg_echo('river:wishlist:add:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="checkout_title"><B><?php echo elgg_echo('settings:share:this'); ?></B></div>
		<div class="checkout_body" style="font-size:12px;">
			<div style="margin:0 0 10px 0"><?php echo elgg_echo('settings:share:this:desc'); ?></div>
			<div class="clear general">
				<div class="settings_field_left" style="width:100px;"><?php echo elgg_echo('settings:share:this:publisher'); ?></div>
				<div class="left" style="padding:3px 5px;">:</div>
				<div class="left""><?php echo elgg_view('input/text',array('name'=>'share_this','value'=>$share_this, 'class'=>'publisher_input')); ?></div>
				<div class="left" style="padding:0 5px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('settings:share:this'); ?>', '<?php echo elgg_echo('settings:share:this:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="checkout_title"><B><?php echo elgg_echo('miscellaneous:ettings'); ?></B></div>
		<div class="checkout_body" style="font-size:12px;">
			<div><B><?php echo elgg_echo('http:proxy:settings'); ?></B></div>
			<div>
				<table class="stores_settings content" width="100%">
					<tr>
						<td style="text-align:right;width:150px;"><?php echo elgg_echo('http:proxy:server'); ?></td>
						<td style="width:5px;">:</td>
						<td style="width:160px;" style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'http_proxy_server','value'=>$http_proxy_server)); ?></td>
						<td style="width:40%"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('http:proxy:server'); ?>', '<?php echo elgg_echo('http:proxy:server:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></td>
					</tr>
					<tr>
						<td style="text-align:right;"><?php echo elgg_echo('http:proxy:port'); ?></td>
						<td style="width:5px;">:</td>
						<td style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'http_proxy_port','value'=>$http_proxy_port)); ?></td>
						<td style="width:40%"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('http:proxy:port'); ?>', '<?php echo elgg_echo('http:proxy:port:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></td>
					</tr>
					<tr>
						<td style="text-align:right;"><?php echo elgg_echo('http:varify:ssl'); ?></td>
						<td style="width:5px;">:</td>
						<td style="text-align:left;"><input type="checkbox" name="http_varify_ssl" <?php echo $http_varify_ssl_checked; ?> value="1"> Yes <span><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('http:varify:ssl'); ?>', '<?php echo elgg_echo('http:varify:ssl:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></span></td>
						<td style="width:40%"></td>
					</tr>
				</table>
			</div>
		</div>
		<div style="margin-left:20px;">
			<?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save')));?>
			<input type="hidden" name="manage_action" value="settings">
			<input type="hidden" name="guid" value="<?php echo $settings_guid; ?>">
			<?php echo elgg_view('input/securitytoken'); ?>
		</div>
		</div>
	</form>
</div>