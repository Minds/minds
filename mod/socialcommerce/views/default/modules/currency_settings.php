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
	 * Elgg modules - currency methods
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	//$socialcommerce_settings = $vars['entity'];
	$ajax = $vars['ajax'];
	//Depricated function replace
	$options = array('types'			=>	"object",
					'subtypes'			=>	"s_currency",
					'limit'				=>	99999,
				);
	$currency_settings = elgg_get_entities($options);
	//$currency_settings = get_entities('object','s_currency',0,'',9999);
	if($currency_settings){
		$body = elgg_view('modules/currency/list_settings',array('entity'=>$currency_settings));
	}else{
		$body = '<div style="text-align:center;padding:5px 0 10px 0;"><B>'.elgg_echo('add:default:currency').'</B></div>';
		$body .= elgg_view('modules/currency/settings_form',array('status'=>'default'));
	}
	$action = $CONFIG->wwwroot."action/".$CONFIG->pluginname."/manage_socialcommerce";
	$load_action = $CONFIG->wwwroot."".$CONFIG->pluginname."/currency_settings";
	
	if($ajax){
		echo $body;
	}else{
?>
		<script>
			function save_currency_settings(){
				var guid = $('[name=guid]');
				var m_action = $('#manage_action');
				var c_name = $('[name=currency_name]');
				var c_country = $('[name=currency_country]');
				var c_code = $('[name=currency_code]');
				var e_rate = $('[name=exchange_rate]');
				var c_token = $('[name=currency_token]');
				var t_location = $('[name=token_location]');
				var d_token = $('[name=decimal_token]');
				var set_def = $('[name=set_default]');
				var elgg_token = $('[name=__elgg_token]');
				var elgg_ts = $('[name=__elgg_ts]');
				if(guid.val() > 0){
					guid = guid.val();
				}else{
					guid = 0;
				}
				if($.trim(c_name.val()) == ""){
					alert('Please enter a currency name.');
					c_name.focus();
					return false;
				}
				
				if($.trim(c_country.val()) == ""){
					alert('Please enter a currency Country.');
					c_country.focus();
					return false;
				}
				
				if($.trim(c_code.val()) == ""){
					alert('Please enter a currency code.');
					c_code.focus();
					return false;
				}
				
				if($.trim(e_rate.val()) == ""){
					alert('Please enter an exchange rate for this currency.');
					e_rate.focus();
					return false;
				}else{
					var regex = /^((\d+(\.\d*)?)|((\d*\.)?\d+))$/;
					if(!regex.test(e_rate.val())){
						alert("Please enter a valid exchange rate. The exchange rate must be a decimal number.");
						e_rate.focus();
						return false;
					}	
				}
				
				if($.trim(c_token.val()) == ""){
					alert('Please enter a currency Sign.');
					c_token.focus();
					return false;
				}
				
				if($.trim(t_location.val()) == ""){
					alert('Please enter the Sign Location.');
					t_location.focus();
					return false;
				}
				
				if($.trim(d_token.val()) == ""){
					alert('Please enter the Decimal Places.');
					d_token.focus();
					return false;
				}else{
					var regex = /^\d+$/;
					if(!regex.test(d_token.val())){
						alert('Please enter a vlid Decimal Places. The Decimal Places must be a number.');
						d_token.focus();
						return false;
					}
				}
				startPreloader();
				$.post("<?php echo $action; ?>", {
					guid: guid,
					u_id: <?php echo $_SESSION['user']->guid; ?>,
					c_name: c_name.val(),
					manage_action: m_action.val(),
					c_country: c_country.val(),
					c_code: c_code.val(),
					e_rate: e_rate.val(),
					c_token: c_token.val(),
					t_location: t_location.val(),
					d_token: d_token.val(),
					set_def: set_def.val(),
					__elgg_token: elgg_token.val(),
					__elgg_ts: elgg_ts.val()
				},
				function(data){
					if(data > 0){
						$("#currency_settings").load("<?php echo $load_action; ?>", { 
							u_id: <?php echo $_SESSION['user']->guid; ?>,
							todo:'currency_settings'},
							function(){
								closePreloader();
						});
					}else{
						closePreloader();
						alert(data);
					}
				});
			}
			function add_currency(){
				startPreloader();
				$("#currency_settings").load("<?php echo $load_action; ?>", { 
					u_id: <?php echo $_SESSION['user']->guid; ?>,
					todo:'settings_form'},
					function(){
						closePreloader();
				});
			}
			function cancel_currency_settings(){
				startPreloader();
				$("#currency_settings").load("<?php echo $load_action; ?>", { 
					u_id: <?php echo $_SESSION['user']->guid; ?>,
					todo:'currency_settings'},
					function(){
						closePreloader();
				});
			}
			function edit_currency(c_guid){
				startPreloader();
				$("#currency_settings").load("<?php echo $load_action; ?>", { 
					u_id: <?php echo $_SESSION['user']->guid; ?>,
					c_id: c_guid,
					todo:'edit_settings'},
					function(){
						closePreloader();
				});
			}
			function delete_currency(c_guid){
				startPreloader();
				var elgg_token = $('[name=__elgg_token]');
				var elgg_ts = $('[name=__elgg_ts]');
				$.post("<?php echo $action; ?>", {
					u_id: <?php echo $_SESSION['user']->guid; ?>,
					c_id: c_guid,
					__elgg_token: elgg_token.val(),
					__elgg_ts: elgg_ts.val(),
					manage_action:'delete_currency'
				},
				function(data){
					if(data > 0){
						$("#currency_settings").load("<?php echo $load_action; ?>", { 
							u_id: <?php echo $_SESSION['user']->guid; ?>,
							todo:'currency_settings'},
							function(){
								closePreloader();
						});
					}else{
						closePreloader();
						alert(data);
					}
				});
			}
			function set_default_currency(c_guid){
				startPreloader();
				var elgg_token = $('[name=__elgg_token]');
				var elgg_ts = $('[name=__elgg_ts]');
				$.post("<?php echo $action; ?>", {
					u_id: <?php echo $_SESSION['user']->guid; ?>,
					c_id: c_guid,
					__elgg_token: elgg_token.val(),
					__elgg_ts: elgg_ts.val(),
					manage_action:'set_default_currency'
				},
				function(data){
					if(data > 0){
						$("#currency_settings").load("<?php echo $load_action; ?>", { 
							u_id: <?php echo $_SESSION['user']->guid; ?>,
							todo:'currency_settings'},
							function(){
								closePreloader();
						});
					}else{
						closePreloader();
						alert(data);
					}
				});
			}
			function get_exchange_rate(){
				var c_code = $('[name=currency_code]');
				var e_rate = $('[name=exchange_rate]');
				var elgg_token = $('[name=__elgg_token]');
				var elgg_ts = $('[name=__elgg_ts]');
				if($.trim(c_code.val()) == ""){
					alert('Please enter a currency code.');
					c_code.focus();
					return false;
				}
				$("#run_exchange_rate").css("display","block");
				$.post("<?php echo $action; ?>", {
					u_id: <?php echo $_SESSION['user']->guid; ?>,
					c_code: c_code.val(),
					__elgg_token: elgg_token.val(),
					__elgg_ts: elgg_ts.val(),
					manage_action:'get_exchange_rate'
				},
				function(data){
					$("#run_exchange_rate").css("display","none");
					if(data >= 0){
						e_rate.val(data);
					}else{
						alert(data);
					}
				});
			}
		</script>
		<div id="currency_settings" class="currency_settings basic">
			<?php echo $body; ?>
		</div>
<?php
	}
?>