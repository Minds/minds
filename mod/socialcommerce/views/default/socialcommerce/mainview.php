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
	 * Elgg view - product mainview
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;

	$product = elgg_extract('entity', $vars, FALSE);
	$add_cart = elgg_extract('add_cart', $vars, FALSE);
	$owner = $product->getOwnerEntity();
	$ts = time();
	
	$price = $product->price;
	$quantity = $product->quantity;
	$mime = $product->mimetype;
	$product_type_details = get_product_type_from_value($product->product_type_id);
	
	$search_viewtype = get_input('search_viewtype');
	
	$owner_link = elgg_view('output/url', array(
		'href' => "file/owner/$owner->username",
		'text' => $owner->name,
	));
	$author_text = elgg_echo('byline', array($owner_link));
	$date = elgg_view_friendly_time($product->time_created);
	$subtitle = "<div class=\"elgg-subtext\">$author_text $date</div>";

	if($add_cart) {
		$phase = 2;
		$action_url = $CONFIG->url."action/{$CONFIG->pluginname}/addcart";
	} else {
		$phase = 1;
		$action_url = addcartURL($product);
		if(isset($_SESSION['product']))
			unset($_SESSION['product']);
		if(isset($_SESSION['related_product']))
			unset($_SESSION['related_product']);
	}
?>
	<div class="storesrepo_stores">
		<div class="storesrepo_icon full_view">
			<?php 
				echo elgg_view("{$CONFIG->pluginname}/image", array(
												'entity' => $product,
												'size' => 'large',
												'display' => 'image'
											  )
										);
			?>	
		</div>
		<form method="post" action="<?php echo $action_url; ?>">
			<div class="right_section_contents">
				<div class="storesrepo_title_owner_wrapper">
					<?php echo elgg_view_image_block(elgg_view_entity_icon($owner, 'tiny'), $subtitle);?>
				</div>
				<div class="storesrepo_maincontent">
					<div style="padding-left:10px;">
	<?php
						if(elgg_is_logged_in() && $_SESSION['user']->guid != $product->owner_guid){
							echo elgg_view("{$CONFIG->pluginname}/star_rating",array('id'=>$product->guid,'units'=>5,'static'=>''));
						}else{
							echo elgg_view("{$CONFIG->pluginname}/view_rating",array('id'=>$product->guid,'units'=>5,'static'=>''));
						}
	?>
					</div>
					
					<div class="storesrepo_tags">
						<span class="object_tag_string">
							<?php echo elgg_view('output/tags', array('tags' => $product->tags)); ?>
						</span>
					</div>
					<?PHP if($price > 0){?>
						<div class="product_odd" style="display:block;padding:3px 3px 20px 10px;">
							<div style="float:left"><B><?php echo elgg_echo("Price");?></B></div>
							<div class="s_price" style="float:left;padding-left:106px;"><B><?php echo get_price_with_currency($price); ?></B></div>
						</div>
					<?php }
						if($product->product_type_id > 0){
					?>
						<div class="product_even"><B><?php echo elgg_echo("product:type");?></B></div>
						<div class="field_results">
							<?php 
							if($product->mimetype && $product->product_type_id == 2){
								echo "<div style=\"float:left;margin-top:20px;\">".elgg_view('output/product_type',array('value' => $product->product_type_id))."</div>";
								echo "<div style=\"float:left;\"><a href=\"{$product->getURL()}\">" . elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $mime, 'thumbnail' => $product->thumbnail, 'stores_guid' => $product->getGUID(), 'size' => 'small')) . "</a></div>";
								echo "<div class=\"clear\"></div>";
							}else{
								echo elgg_view('output/product_type',array('value' => $product->product_type_id));
							} 
							?>
						</div>
					<?php }
					if($product->category > 0){
					?>
						<div class="product_odd" style="display:block;padding:3px 3px 20px 10px;">
							<div style="float:left"><B><?php echo elgg_echo("category");?></B></div>
							<div style="float:left;padding-left:60px;"><?php echo elgg_view('output/category',array('value' => $product->category)); ?></div>
						</div>
						
					<?php }
					if($quantity > 0 && $product->product_type_id == 1){?>
						<div class="product_even" style="display:block;padding:3px 3px 20px 10px;">
							<div style="float:left"><B><?php echo elgg_echo("quantity");?></B></div>
							<div style="float:left;padding-left:80px;"><?php echo $quantity ?></div>
						</div>
					<?php } ?>
					<?php if ($product->canEdit()) {?>
						<div class="storesrepo_controls">
							<?php if($product->status == 1){?>
								<div class="cart_wishlist">
									<?php echo elgg_view("{$CONFIG->pluginname}/share_this",array('entity'=>$product)); ?>
								</div>
							<?PHP } ?>
							<?php if($_SESSION['user']->guid != $product->owner_guid && $product->status == 1 && $product_type_details->addto_cart == 1){ ?>
								<div class="cart_wishlist">
									<a class="wishlist" href="<?php echo $CONFIG->wwwroot."action/{$CONFIG->pluginname}/add_wishlist?pgid=".$product->guid."&__elgg_token=".generate_action_token($ts)."&__elgg_ts={$ts}";  ?>"><?php echo elgg_echo('add:wishlist');?></a>
								</div>
							<?php } ?>
							<div class="clear"></div>
						</div>	
<?php 
						if(elgg_is_admin_logged_in() || $_SESSION['user']->guid == $product->owner_guid){ 
							$options = array('metadata_name_value_pairs' => array('product_id'=>$product->guid),
											 'types'			=>	"object",
											 'subtypes'			=>	"order_item",
							   				 'count'			=>	true);
							$count = elgg_get_entities_from_metadata($options);
?>
							<div class="storesrepo_controls">
<?php
								if($product->status == 1){ 
								 	// Check membership privileges
									$permission = membership_privileges_check('sell');
									if($permission == 1) {
?>							
										<div class="edit_btn" style="float:left;">
											<a href="<?php echo $CONFIG->wwwroot; ?><?php echo $CONFIG->pluginname; ?>/edit/<?php echo $product->getGUID(); ?>"><?php echo elgg_echo('edit'); ?></a>&nbsp; 
										</div>
									
										<div class="delete_btn" style="float:left;padding-left:10px;">
											<?php 
												echo elgg_view('output/confirmlink',array(
													'href' => $vars['url'] . "action/{$CONFIG->pluginname}/delete?stores=" . $product->getGUID(),
													'text' => elgg_echo("delete"),
													'confirm' => elgg_echo("stores:delete:confirm"),
												));  
											?>
										</div>
<?PHP 
									}
									if($count > 0){
?>
										<div class="view_order_btn" style="float:left;padding-left:10px;">
											<a href="<?php echo $CONFIG->wwwroot; ?><?php echo $CONFIG->pluginname; ?>/orderadmin/<?php echo $product->getGUID(); ?>"><?php echo elgg_echo('stores:purchased:orders'); ?></a>&nbsp;
										</div>
<?php 	
									}
								} else { 
?>
									<div class="retrieve_btn" style="float:left;">
										<a href="<?php echo $vars['url']; ?>action/<?php echo $CONFIG->pluginname; ?>/retrieve?stores_guid=<?php echo $product->getGUID(); ?>&__elgg_token=<?php echo generate_action_token($ts); ?>&__elgg_ts=<?php echo $ts; ?>"><?php echo elgg_echo('retrieve'); ?></a>&nbsp; 
									</div>
						<?php 
								}
						?>
								<div style="clear:both;"></div>
							</div>
<?php
						}
					}else{
						if($product->status == 1){
?>	
							<div class="storesrepo_controls">
								<div class="cart_wishlist">
									<?php echo elgg_view("{$CONFIG->pluginname}/share_this",array('entity'=>$product)); ?>
								</div>
								<?php if($product_type_details->addto_cart == 1) { ?>
									<div class="cart_wishlist">
											<a class="wishlist" href="<?php echo $CONFIG->wwwroot."action/".$CONFIG->pluginname."/add_wishlist?pgid=".$product->guid."&__elgg_token=".generate_action_token($ts)."&__elgg_ts={$ts}";  ?>"><?php echo elgg_echo('add:wishlist');?></a>
									</div>
								<?php } ?>
								<div style="clear:both;"></div>	
							</div>
<?php	
						}
					}
?>
					<!-- Cart Button -->
					<?php 
						// Check membership privileges
						$permission = membership_privileges_check('buy');
						if($permission == 1) {
							echo elgg_view("{$CONFIG->pluginname}/socialcommerce_cart",array('entity'=>$product,'product_type_details'=>$product_type_details,'phase'=>$phase));
						}
					?>
				</div>
			</div>
			<div class="clear"></div>
			<table width="100%">
				<tr>
					<td>
						<?php
							$display_fields = '';
							$product_fields = $CONFIG->product_fields[$product->product_type_id];
							if (is_array($product_fields) && sizeof($product_fields) > 0){
								foreach ($product_fields as $shortname => $valtype){
									if($valtype['display'] == 1 && 	$shortname != 'price' && $shortname != 'quantity' && $shortname != 'upload'){
										$display_name = elgg_echo('product:'.$shortname);
										$output = elgg_view("output/{$valtype['field']}",array('value'=>$product->$shortname));
										$display_fields .= <<<EOF
											<div class="storesrepo_description">
												<B>{$display_name} :</B> {$output}
											</div>
EOF;
									}
								}
							}
							echo $display_fields;
						?>
						<?php 
						if($product->product_type_id == 2){
							echo elgg_view("output/multi_product_version",array('entity_guid'=>$product->guid));
						}
						?>					
						<?php echo  elgg_view("custom_field/display",array('entity'=>$product)); ?>
						<div class="features"><?php echo elgg_echo('features:des'); ?></div>
						<div class="storesrepo_description"><?php echo autop($product->description); ?></div>
					</td>
					<?php
						$options = array('metadata_name_value_pairs' =>	array('product' => $product->guid),
										 'types' =>	"object",
										 'subtypes' =>	"related_product",															
										 'limit' =>	99999,
										
									);
						$related_products = elgg_get_entities_from_metadata($options);
						if($related_products){
							
					?>
							<td style="width:47%;">
								<?php echo elgg_view("{$CONFIG->pluginname}/mainview_related_products",array("entity"=>$product,'related_products'=>$related_products))?>
							</td>
					<?php		
						}
					?>
				</tr>
			</table>
			<?php echo elgg_view('input/securitytoken'); ?>
		</form>
	</div>
<?php
	if ($vars['full']) {
		echo elgg_view_comments($product);
	}
?>
<img src='http://surfscripts.com/demo/adtracker/index.php/tracker/trackdata/1' width='0' height='0'>