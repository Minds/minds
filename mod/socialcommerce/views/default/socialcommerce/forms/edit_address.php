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
	 * Elgg address - edit form
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$ajax = $vars['ajax'];
	// Set title, form destination
		if (isset($vars['entity'])) {
			$action = "{$CONFIG->pluginname}/edit_address";
			$firstname = $vars['entity']->first_name;
			$lastname = $vars['entity']->last_name;
			$address_line_1 = $vars['entity']->address_line_1;
			$address_line_2 = $vars['entity']->address_line_2;
			$city = $vars['entity']->city;
			$state = $vars['entity']->state;
			$country = $vars['entity']->country;
			$pincode = $vars['entity']->pincode;
			$mobileno = $vars['entity']->mobileno;
			$phoneno = $vars['entity']->phoneno;
			$access_id = $vars['entity']->access_id;
		} else {
			$action = "{$CONFIG->pluginname}/add_address";
			$firstname = "";
			$lastname = "";
			$address_line_1 = "";
			$address_line_2 = "";
			$city = "";
			$state = "";
			$country = "";
			$pincode = "";
			$mobileno = "";
			$phoneno = "";
			$access_id = 0;
		}

	// Just in case we have some cached details
		if (isset($vars['address'])) {
			$firstname = $vars['address']['first_name'];
			$lastname = $vars['address']['last_name'];
			$address_line_1 = $vars['address']['address_line_1'];
			$address_line_2 = $vars['address']['address_line_2'];
			$city = $vars['address']['city'];
			$state = $vars['address']['state'];
			$country = $vars['address']['country'];
			$pincode = $vars['address']['pincode'];
			$mobileno = $vars['address']['mobileno'];
			$phoneno = $vars['address']['phoneno'];
			$access_id = $vars['address']['access_id'];
		}
?>

<?php
        /*$title_label = elgg_echo('title');
        $title_textbox = elgg_view('input/text', array('name' => 'title', 'value' => $title));*/
        
        $fnaem_label = elgg_echo('first:name');
        $fnaem_textbox = elgg_view('input/text', array('name' => 'first_name', 'value' => $firstname));
        
        $lname_label = elgg_echo('last:name');
        $lname_textbox = elgg_view('input/text', array('name' => 'last_name', 'value' => $lastname));
        
        $address_line_1_label = elgg_echo('address:line:1');
        $address_line_1_textbox = elgg_view('input/text', array('name' => 'address_line_1', 'value' => $address_line_1));
        
        $address_line_2_label = elgg_echo('address:line:2');
        $address_line_2_textbox = elgg_view('input/text', array('name' => 'address_line_2', 'value' => $address_line_2));
        
        $city_label = elgg_echo('city');
        $city_textbox = elgg_view('input/text', array('name' => 'city', 'value' => $city));
        
        $state_label = elgg_echo('state');
        $state_textbox = elgg_view('input/text', array('name' => 'state', 'value' => $state));
                        
        $country_label = elgg_echo('country');
        $country_textbox = elgg_view('input/text', array('name' => 'country', 'value' => $country));
        
        $pincode_label = elgg_echo('pincode');
        $pincode_textbox = elgg_view('input/text', array('name' => 'pincode', 'value' => $pincode));
        
        $mobno_label = elgg_echo('mob:no');
        $mobno_textbox = elgg_view('input/text', array('name' => 'mobileno', 'value' => $mobileno));
        
        $phoneno_label = elgg_echo('phone:no');
        $phoneno_textbox = elgg_view('input/text', array('name' => 'phoneno', 'value' => $phoneno));
        
        $access_label = elgg_echo('access');
        $access_input = elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id));
        
        $submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save')));

        if (isset($vars['container_guid']))
			$entity_hidden = "<input type=\"hidden\" name=\"container_guid\" value=\"{$vars['container_guid']}\" />";
		if (isset($vars['entity']))
			$entity_hidden .= "<input type=\"hidden\" name=\"address_guid\" value=\"{$vars['entity']->getGUID()}\" />";
		
		$entity_hidden .= elgg_view('input/securitytoken');
		if($ajax == 1){
			$javascript = "onsubmit='return save_address()'";
			$fnaem_label_none = elgg_echo('first:name:none');
			$lname_label_none = elgg_echo('last:name:none');
			$address_line_1_label_none = elgg_echo('address:line:1:none');
			$address_line_2_label_none = elgg_echo('address:line:2:none');
			$city_label_none = elgg_echo('city:none');
			$state_label_none = elgg_echo('state:none');
			$country_label_none = elgg_echo('country:none');
			$pincode_label_none = elgg_echo('pincode:none');
			$mobno_label_none = elgg_echo('mob:no:none');
			$address_post_url = "{$vars['url']}action/{$action}";
			$address_reload_url = "{$vars['url']}{$CONFIG->pluginname}/view_address/{$_SESSION['user']->username}";
			$script = <<<EOF
				<script>
					function save_address(){
						var first_name = $('[name=first_name]').val();
						var last_name = $('[name=last_name]').val();
						var address_line_1 = $('[name=address_line_1]').val();
						var address_line_2 = $('[name=address_line_2]').val();
						var city = $('[name=city]').val();
						var state = $('[name=state]').val();
						var country = $('[name=country]').val();
						var pincode = $('[name=pincode]').val();
						var mobileno = $('[name=mobileno]').val();
						var phoneno = $('[name=phoneno]').val();
						var address_guid = $('[name=address_guid]').val();
						var elgg_token = $('[name=__elgg_token]');
						var elgg_ts = $('[name=__elgg_ts]');
						
						if($.trim(first_name) == ""){
							alert("{$fnaem_label_none}");
							$('[name=first_name]').focus();
							return false;
						}
						if($.trim(last_name) == ""){
							alert("{$lname_label_none}");
							$('[name=last_name]').focus();
							return false;
						}
						if($.trim(address_line_1) == ""){
							alert("{$address_line_1_label_none}");
							$('[name=address_line_1]').focus();
							return false;
						}
						if($.trim(address_line_2) == ""){
							alert("{$address_line_2_label_none}");
							$('[name=address_line_2]').focus();
							return false;
						}
						if($.trim(city) == ""){
							alert("{$city_label_none}");
							$('[name=city]').focus();
							return false;
						}
						if($.trim(state) == ""){
							alert("{$state_label_none}");
							$('[name=state]').focus();
							return false;
						}
						if($.trim(country) == ""){
							alert("{$country_label_none}");
							$('[name=country]').focus();
							return false;
						}
						if($.trim(pincode) == ""){
							alert("{$pincode_label_none}");
							$('[name=pincode]').focus();
							return false;
						}
						if($.trim(mobileno) == ""){
							alert("{$mobno_label_none}");
							$('[name=mobileno]').focus();
							return false;
						}
							
						$.post("{$address_post_url}", {
							first_name: first_name,
							last_name: last_name,
							address_line_1: address_line_1,
							address_line_2: address_line_2,
							city: city,
							state: state,
							country: country,
							pincode: pincode,
							mobileno: mobileno,
							phoneno: phoneno,
							address_guid: address_guid,
							ajax:1,
							__elgg_token: elgg_token.val(),
							__elgg_ts: elgg_ts.val()
						},
						function(data){
							if(data > 0){
								$("#checkout_address").load("{$address_reload_url}", {guid: data,todo:'reload_address'});
							}else{
								alert(data);
							}
						});
						return false;
					}
				</script>
EOF;
		}else{
			$javascript = "";
		}
		$form_body = <<<EOT
			{$script}
			<div class="address_form">
	        	<form action="{$vars['url']}action/{$action}" method="post" {$javascript}>
					<p>
						<label><span style="color:red">*</span>$fnaem_label</label><br />
			                        $fnaem_textbox
					</p>
					<p>
						<label><span style="color:red">*</span>$lname_label</label><br />
									$lname_textbox
					</p>
					<p>
						<label><span style="color:red">*</span>$address_line_1_label</label><br />
									$address_line_1_textbox
					</p>
					<p>
						<label><span style="color:red">*</span>$address_line_2_label</label><br />
									$address_line_2_textbox
					</p>
					<p>
						<label><span style="color:red">*</span>$city_label</label><br />
									$city_textbox
					</p>
					<p>
						<label><span style="color:red">*</span>$state_label</label><br />
									$state_textbox
					</p>
					<p>
						<label><span style="color:red">*</span>$country_label</label><br />
									$country_textbox
					</p>
					<p>
						<label><span style="color:red">*</span>$pincode_label</label><br />
									$pincode_textbox
					</p>
					<p>
						<label><span style="color:red">*</span>$mobno_label</label><br />
									$mobno_textbox
					</p>
					<p>
						<label>$phoneno_label</label><br />
									$phoneno_textbox
					</p>
					<p>
						$entity_hidden
						$submit_input
					</p>
				</form>
			</div>
EOT;
echo $form_body;
?>