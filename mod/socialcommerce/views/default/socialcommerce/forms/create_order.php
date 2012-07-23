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
	$redirect = "{$vars['url']}{$CONFIG->pluginname}/get_products";
?>
<script>
	$(document).ready(function(){
	 	$("#customer").autocomplete("<?php echo $vars['url'];?><?php echo $CONFIG->pluginname; ?>/autoUserList", {
				cacheLength: 20,
				multiple: false
		});

		$("#btn_psearch").click(function(){
			$.ajax({
				type: "POST",
				url: "<?php echo $redirect;?>",
				data: "customer=" + $.trim($("#customer").val()),
				success: function(data) { 
					$("#list_products").html(data);
				}		
			}); 
		});
	 });
	
	function validate_order_create(){
		if($.trim($("#customer").val()) == ''){
			alert("Enter Customer id or username");
			$("#customer").focus();
			return false;
		}
		
		var product = $('input[name=product[]]:checked');
		if($(product).size() == 0){
			alert("Enter product guid");
			return false;
		}
		return true;
	}
</script>
<form action="<?php echo $vars['url']; ?><?php echo $CONFIG->pluginname;?>/create_order_confirm" method="post" onsubmit="return validate_order_create()">
	<div class="fields clear" style="width:50%;">
		<div><b><span style="color:red">*</span><?php echo elgg_echo('order:customers'); ?></b></div>
        <div>
        	<?php echo elgg_view('input/text',array('name'=>'customer','id'=>'customer','onclick'=>"javascript: if (this.value=='".elgg_echo('order:customers')."') { this.value='' }", 'onblur'=>"javascript: if(this.value==''){ this.value='".elgg_echo('order:customers')."' }", 'value'=>elgg_echo('order:customers')))?>
        </div>
        <div style='margin:10px;'>
			<?php echo elgg_view('input/button', array('name' => 'submit', 'id'=>'btn_psearch', 'value' => elgg_echo('Find Products')));?>
        </div>
	</div>
	<div id="list_products"></div>
</form>