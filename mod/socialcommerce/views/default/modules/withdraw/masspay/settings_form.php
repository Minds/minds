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
	 * Elgg withdraw - masspay - settings forms page
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$method = get_input('method');
	if(isset($_SESSION['WITHDRAW']) && !empty($method) && $method == 'masspay'){
		$withdraw_amount = $_SESSION['WITHDRAW']['amount'];
		$paypal_email = $_SESSION['WITHDRAW']['paypal_email'];
	}else{
		$amount = "";
		$paypal_email = "";
		unset($_SESSION['WITHDRAW']);
	}
?>
<div>
	<script language="javascript" type="text/plain">
		function validate_masspay(){
			var withdraw_amount = $("#withdraw_amount").val();
			var total_amount = $("#total_amount").val();
			var paypa_email = $("#paypal_email").val();
			if($.trim(withdraw_amount) == ""){
				alert("Please Enter the Amount");
				return false;
			}else{
				var regex = /^((\d+(\.\d*)?)|((\d*\.)?\d+))$/;
				if(!regex.test(withdraw_amount)){
					alert("Please Enter a valid Amount");
					return false;
				}else{
					if(parseInt(withdraw_amount) > parseInt(total_amount)){
						alert("Your account have only $" + total_amount);
						return false;
					}
				}
			}
			if($.trim(paypa_email) == ""){
				alert("Please Enter your PayPal Email ID");
				return false;
			}else{
				var regex = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
				if(!regex.test(paypa_email)){
					alert("Please Enter a valid PayPal Email ID");
					return false;
				}
			}
			return true;
		}
	</script>
	<div>
		<B><?php echo elgg_echo('mass:pay'); ?></B>
	</div>
	<div>
		<div>
			<table class="stores_settings" width="70%">
				<tr>
					<td style="text-align:right;"><B><span style="color:red;">*</span> <?php echo elgg_echo('amount'); ?></B></td>
					<td>:</td>
					<td style="text-align:left;">
						<input type="text" name="withdraw_amount" id="withdraw_amount" class="elgg-input-text" style="width:180px;" value="<?php echo $withdraw_amount; ?>">
					</td>
				</tr>
				<tr>
					<td style="text-align:right;"><B><span style="color:red;">*</span> <?php echo elgg_echo('paypal:email:id'); ?></B></td>
					<td>:</td>
					<td style="text-align:left;">
						<input type="text" name="paypal_email" id="paypal_email"  class="elgg-input-text" style="width:180px;" value="<?php echo $paypal_email; ?>">
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>