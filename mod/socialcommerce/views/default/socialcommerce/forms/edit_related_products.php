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
	 * Elgg form - product
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	// Check membership privileges
	$permission = membership_privileges_check('sell');
	if($permission == 1) {
		$product = $vars['product'];
		$related_product = $vars['related_product'];
		if(!$product || !elgg_is_logged_in())
			forward();
			
		if($product){
			$product_title = $product->title;
		}
	
		$detaile_template = <<<EOF
			<div %s>
				<input class="details_title_input" type="text" name="details_%s_title" value="%s" %s />
				<input class="details_price_input" type="text" name="details_%s_price" value="%s" %s />
				%s
				<div class="details_label_div"></div>
			</div>
EOF;
		$action_url = $vars['url']."action/{$CONFIG->pluginname}/manage/related_products";
		if($related_product->guid > 0){
			$title = $related_product->title;
			$product_type_id = $related_product->product_type_id;
			$description = $related_product->description;
			$listing = $related_product->listing;
			
			$manage_action = 'edit';
			if($related_product->guid > 0){
				$options = array('metadata_name_value_pairs'	=>	array('related_product' => $related_product->guid),
								 'types'		=>	"object",
								 'subtypes'		=>	"related_product_details",					
								 'limit'		=>	500,
								 'order_by' 	=>	'e.guid asc');
				$details = elgg_get_entities_from_metadata($options);
			}
			if($details){
				$number_of_details = 0;
				$del_confirm = elgg_echo('delete:related:product:detail:confirm');
				$script = <<<EOF
					function delete_related_product_detail(guid){
						var del_confirm = confirm("{$del_confirm}");
						var elgg_token = $('[name=__elgg_token]');
						var elgg_ts = $('[name=__elgg_ts]');
						if(del_confirm){
							$.post("{$action_url}", {
								guid: guid,
								u_id: {$_SESSION['user']->guid},
								manage_action: 'delete_detail',
								__elgg_token: elgg_token.val(),
								__elgg_ts: elgg_ts.val()
							},
							function(data){
								if(data > 0){
									$("#detail_"+guid).remove();
								}else{ alert("@@@@");
									alert(data);
								}
							});
						}
					}
EOF;
				$details_bit = '';
				if (count($details) > 0) {
				    $count = 0;
				    foreach($details as $detaile) {
				    	$edit_url = $CONFIG->wwwroot.'mod/'.$CONFIG->pluginname.'/images/';
				    	$delete_url = $CONFIG->wwwroot.'mod/'.$CONFIG->pluginname.'/images/';//onClick="load_edit_related_product_detaile({$detaile->guid})"
				    	$edit_delete = <<<EOF
							<div class="edit_delete_details">
								<a class="edit" rel="scbox" href="{$CONFIG->wwwroot}{$CONFIG->pluginname}/related/detail/{$detaile->guid}" ></a>
								<a class="delete" href="javascript:void(0);" onClick="delete_related_product_detail({$detaile->guid})"></a>
							</div>		    	
EOF;
				        $details_bit .= sprintf($detaile_template,'id="detail_'.$detaile->guid.'"',$detaile->guid,$detaile->title,'disabled="yes"',$detaile->guid,$detaile->price,'disabled="yes"',$edit_delete);
				        $count ++;
				    }
				}
	
			}else{
				$number_of_details = 1;
				$details_bit .= sprintf($detaile_template,'',0,'','',0,'','','');
			}
		}else{
			$number_of_details = 1;
			$manage_action = 'add';
			$listing = 'check';
			$details_bit .= sprintf($detaile_template,'',0,'','',0,'','','');
		}
		
		if(isset($_SESSION['related_product'])) {
			$title = $_SESSION['related_product']['title'];
			$product_type_id = $_SESSION['related_product']['product_type_id'];
			$description = $_SESSION['related_product']['description'];
			$listing = $_SESSION['related_product']['listing'];
		}
	}
?>
<script>
	var number_of_details = <?php echo $number_of_details; ?>;
	function add_details() {
	    var o,el;
	    o = document.getElementById('details_container');
	    el = document.createElement('input');
	    el.type = 'text';
	    el.className = "details_title_input";
	    el.name = "details_"+number_of_details+"_title";
	    el.value = "";
	    o.appendChild(el);
	    el = document.createElement('input');
	    el.type = 'text';
	    el.className = "details_price_input_js";
	    el.name = "details_"+number_of_details+"_price";
	    el.value = "";
	    o.appendChild(el);
	    el = document.createElement('div');
	    el.className = "details_label_div";
	    o.appendChild(el);
	    number_of_details++;
	    $("#number_of_details").val(number_of_details);
	    
	    x = document.getElementById('details_outer_container');
	    x.className = 'visible';
	}
	<?php echo $script; ?>
</script>
<?php if($permission == 1) {?>
	<form action="<?php echo $action_url?>" enctype="multipart/form-data" method="post">
		<p>
			<label><span style="color:red">*</span><?php echo elgg_echo('produt:title'); ?></label><br />
			<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $product->title, 'disabled'=>true));?>
		</p>
		<p>
			<label><span style="color:red">*</span><?php echo elgg_echo('related:produt:title'); ?></label><br />
			<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title));?>
		</p>
		<?php echo elgg_view('input/relatedproduct_type', array('name' => 'product_type_id', 'value' => $product_type_id));?>
		<p>
			<label><span style="color:red"></span><?php echo elgg_echo('related:produt:description'); ?></label><br />
			<?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $description));?>
		</p>
		<div style="padding:10px 0;">
			<label style="display:block;"><span style="color:red">*</span><?php echo elgg_echo('related:produt:listing'); ?></label><br />
			<?php 
				$options = array(elgg_echo('listing:radio')=>'radio',elgg_echo('listing:check')=>'check');
				echo elgg_view('input/radio', array('name' => 'listing', 'value' => $listing, 'options'=>$options));
			?>
		</div>
		<div style="padding:10px 0;"> 
			<label style="display:block;"><?php echo elgg_echo('related:produt:details'); ?></label><br />
			<div id="details_outer_container">
		        <div id="details_container">
		            <table width="100%">
		            	<tr>
		            		<td width="545">
		            			<span class="details_header"><?php echo elgg_echo('related:produt:details_title'); ?></span>
		            		</td>
		            		<td>
		            			<span class="details_header"><?php echo elgg_echo('related:produt:details_price'); ?></span>
		            		</td>
		            	</tr>
		            </table>
		            <?php echo $details_bit; ?>
		        </div>
		    </div>
		    <div style="float: right;margin-right:75px;margin-top:10px;">
			    <input class="submit_button" type="button" name="details_button" value="+ <?php echo elgg_echo('related:produt:details_add_button'); ?>"  onClick="add_details();" />
			    <input type="hidden" name="number_of_details" id="number_of_details" value="<?php echo $number_of_details; ?>" />
		    </div>
		</div>
		<div class="clear">
			<?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('related:produt:submit:button')));?>
			<input type="hidden" name="product_guid" id="product_guid" value="<?php echo $product->guid; ?>" />
			<input type="hidden" name="related_product_guid" id="related_product_guid" value="<?php echo $related_product->guid; ?>" />
			<input type="hidden" name="manage_action" id="manage_action" value="<?php echo $manage_action; ?>" />
			<?php echo elgg_view('input/securitytoken'); ?>
		</div>
	</form>
<?php } else {
	echo "<div class='contentWrapper'>".elgg_echo('update:sell')."</div>";
}?>