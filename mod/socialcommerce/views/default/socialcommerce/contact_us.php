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
 * Elgg view - contact us
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */ 
	 
global $CONFIG;
if($_SESSION['user']->guid > 0){
	$user_email = $_SESSION['user']->email;
	$user_name = $_SESSION['user']->name;
}
?>
<SCRIPT>
function IsEmail(PossibleEmail){
	var PEmail = new String(PossibleEmail);
	var regex = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
	return !regex.test(PEmail);
}
function contact_form_validation(){
	var c_email = $("#contact_email").val();
	var c_name = $("#contact_name").val();
	var subject = $("#subject").val();
	
	if($.trim(c_email) == ""){
		alert("<?php echo elgg_echo("contact:email:null"); ?>");
		$("#contact_email").focus();
		return false;
	}else{
		if(IsEmail(c_email)){
			alert("<?php echo elgg_echo("contact:email:not:valid"); ?>");
			$("#contact_email").focus();
			return false;
		}
	}
	if($.trim(c_name) == ""){
		alert("<?php echo elgg_echo("contact:name:null"); ?>");
		$("#contact_name").focus();
		return false;
	}
	if($.trim(subject) == ""){
		alert("<?php echo elgg_echo("subject:null"); ?>");
		$("#subject").focus();
		return false;
	}
}
</SCRIPT>
<div class="contact_us_box">
	<div class="index_box">
		<h2><?php echo elgg_echo("contact:us");?></h2>
		<div class="contentWrapper">
			<form onsubmit="return contact_form_validation();" method="POST" action="<?php echo $CONFIG->wwwroot; ?>action/<?php echo $CONFIG->pluginname; ?>/contact_us">
				<table width="100%">
					<tr>
						<td style="text-align:right"><span style="color:red;">*</span><B><?php echo elgg_echo("contact:email");?></B></td>
						<td>:</td>
						<td><input type="text" id="contact_email" name="contact_email" value="<?php echo $user_email;?>"></td>
					</tr>
					<tr>
						<td style="text-align:right"><span style="color:red;">*</span><B><?php echo elgg_echo("contact:name");?></B></td>
						<td>:</td>
						<td><input type="text" id="contact_name" name="contact_name" value="<?php echo $user_name;?>"></td>
					</tr>
					<tr>
						<td style="text-align:right"><span style="color:red;">*</span><B><?php echo elgg_echo("subject");?></B></td>
						<td>:</td>
						<td><input type="text" id="subject" name="subject" value=""></td>
					</tr>
					<tr>
						<td colspan="3" style="text-align:center"><B><?php echo elgg_echo("message");?></B><textarea style="width:350px;height:100px;" id="description" name="description"></textarea></td>
					</tr>
					<tr>
						<td colspan="3" style="text-align:center">
							<?php echo elgg_view('input/submit', array('name' => 'btn_send', 'value' => elgg_echo('send'), 'class'=>'submit_button'));?>
						</td>
					</tr>
				</table>
				<?php echo elgg_view('input/securitytoken'); ?>
			</FORM>
			<div style="clear:both;"></div>
		</div>
	</div>
</div>
