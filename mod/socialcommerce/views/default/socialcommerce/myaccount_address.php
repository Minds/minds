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
	 * Elgg view - my account address
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$options = array('types'			=>	"object",
					 'subtypes'			=>	"address",
					 'owner_guids'		=>	elgg_get_page_owner_guid(),
				);
	$address = elgg_get_entities($options);	
	if($address){
		$list_address = elgg_view("{$CONFIG->pluginname}/list_address",array('entity'=>$address,'display'=>'list_with_action','selected'=>$selected_address,'type'=>'myaccount'));
		$body = <<<EOF
			<div>
				<div style="float:right;margin-bottom:10px;">
					<div class="buttonwrapper" style="float:left;">
						<a onclick="add_myaddress();" class="squarebutton"><span> Add New Address </span></a>
					</div>
				</div>
				<div class="clear" style="margin-bottom:10px;">
					{$list_address}
				</div>
			</div>
EOF;
	}else{
		$body = elgg_view("{$CONFIG->pluginname}/forms/checkout_edit_address",array('ajax'=>1,'type'=>'myaccount','first'=>1));
	}
	$delete_confirm_msg = elgg_echo('delete:confirm:address');
	$load_action = $CONFIG->checkout_base_url."".$CONFIG->pluginname."/view_address"; 
	$delete_action = $CONFIG->checkout_base_url."action/".$CONFIG->pluginname."/delete_address";
	$loggedin_user_id = elgg_get_logged_in_user_guid();
	$area2 = <<<EOF
		<script>
			function add_myaddress(){
				$("#myaccount_address").load("{$load_action}", { 
					u_guid: {$loggedin_user_id},
					todo:'add_myaddress'
				});
			}
			function edit_myaddress(guid){
				$("#myaccount_address").load("{$load_action}", { 
					u_guid: {$loggedin_user_id},
					a_id: guid,
					todo:'edit_myaddress'
				});
			}
			function myaccount_cancel_address(){
				$("#myaccount_address").load("{$load_action}", { 
					todo:'reload_myaccount_address',
					u_guid: {$loggedin_user_id}
				});
			}
			function delete_myaddress(guid){
				var elgg_token = $('[name=__elgg_token]');
				var elgg_ts = $('[name=__elgg_ts]');
				if(confirm('{$delete_confirm_msg}')){
					$.post("{$delete_action}", {
						u_guid: {$loggedin_user_id},
						a_id: guid,
						ajax: 1,
						__elgg_token: elgg_token.val(),
						__elgg_ts: elgg_ts.val()
					},
					function(data){
						if(data > 0){
							$("#myaccount_address").load("{$load_action}", { 
								u_guid: {$loggedin_user_id},
								todo:'reload_myaccount_address'
							});
						}else{
							alert(data);
						}
					});
				}
			}
		</script>
		<div class="basic checkout_process">
			<div class="content">
				<div id="myaccount_address">
					{$body}
				</div>
			</div>
		</div>			
EOF;
	
	echo $area2;
?>