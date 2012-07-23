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
	 * Elgg social commerce - related product page
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	
	$detail = get_input('guid');
	if($detail > 0){
		$detail = get_entity($detail);
		$action_url = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage/related_products";
		$edit_fail = elgg_echo('related:product:details:edit:failed');
		?>
		<script type="text/javascript">
			function edit_related_product_details(guid){
				var elgg_token = $('[name=__elgg_token]');
				var elgg_ts = $('[name=__elgg_ts]');
				$.post("<?php  echo $action_url; ?>", {
					guid: guid,
					u_id: <?php  echo $_SESSION['user']->guid; ?>,
					manage_action: 'edit_details',
					details_title: $("#details_title").val(),
					details_price: $("#details_price").val(),
					__elgg_token: elgg_token.val(),
					__elgg_ts: elgg_ts.val()
				},
				function(data){
					if(data > 0){
						$.post("<?php  echo $action_url; ?>", {
							guid: guid,
							manage_action: 'reload_detail',
							__elgg_token: elgg_token.val(),
							__elgg_ts: elgg_ts.val()
						},
						function(data1){
							if(data1 == 'Fail'){
								alert("<?php echo $edit_fail; ?>");
							}else{
								$("#detail_"+guid).html(data1);
								$("#scbox").find(".content").html('');
								$("#scbox").hide();
								$("#scbox_overlay").remove();
							}
						});
					}else{
						$("#related_messages").show();
						$("#related_messages").html(data);
					}
				});
			}
		</script>
		<div class="load_details_maindiv">
			<div class="load_details_titlebar">
				<?php echo elgg_echo('edit:related:product:details'); ?>
			</div>
			<div id="related_messages"></div>
			<div class="load_details_content">
				<p>
					<label><span style="color:red">*</span><?php echo elgg_echo('related:produt:details_title'); ?></label><br />
					<input type="text" class="elgg-input-text" value="<?php  echo $detail->title; ?>" name="details_title" id="details_title"></input>
				</p>
				<p>
					<label><span style="color:red"></span><?php echo elgg_echo('related:produt:details_price'); ?></label><br />
					<input type="text" class="elgg-input-text" value="<?php  echo $detail->price; ?>" name="details_price" id="details_price"></input>
				</p>
				<div class="clear">
					<?php echo elgg_view('input/button', array('name'=>'sub_btn', 'value'=>elgg_echo("related:produt:details:submit:button"), 'onclick'=>"edit_related_product_details({$detail->guid})"));?>
					<input type="hidden" name="detail_guid" id="detail_guid" value="<?php echo $detail->guid; ?>" />
					<?php echo elgg_view('input/securitytoken'); ?>
				</div>
			</div>
		</div>
		<?php 
	}else{
		echo elgg_echo ('related:product:details:edit:failed');
	}
	exit;