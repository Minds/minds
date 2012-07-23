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
	 * Elgg view - related product view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	$related_product = $vars['entity'];
	if($related_product){
		$base_product = $related_product->product;
		$title = $related_product->title;
		$description = $related_product->description;
		$type = $related_product->product_type_id;
		$listing = $related_product->listing;
		
		$options = array('metadata_name_value_pairs'	=>	array('related_product' => $related_product->guid),
						 'types'		=>	"object",
						 'subtypes'		=>	"related_product_details",
						 'owner_guids'	=>	$_SESSION['user']->guid,						
						 'limit'		=>	99999);
		$details = elgg_get_entities_from_metadata($options);
?>
		<div class="search_listing related_product">
			<div class="title"><?php echo $title; ?></div>
			<div class="content">
				<div><?php echo $description; ?></div>
				<?php if($details) { ?>
					<div class="details">
						<?php foreach($details as $detail){ ?>
							<div> <?php echo elgg_view('input/related_product_details',array('related_produt'=>$related_product,'detail'=>$detail));?> </div>
							<div class="clear"></div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<?php if($related_product->canEdit()){?>
				<div class="edit_btn" style="float:left;margin-left:6px;margin-top:5px;">
					<a href="<?php echo $vars['url']; ?><?php echo $CONFIG->pluginname; ?>/related/edit/<?php echo $related_product->guid; ?>/<?php echo $base_product; ?>"><?php echo elgg_echo('edit'); ?></a>&nbsp; 
				</div>
				<div class="delete_btn" style="float:left;padding-left:10px;margin-left:6px;margin-top:5px;">
					<?php 
						echo elgg_view('output/confirmlink',array(
							'href' => $CONFIG->wwwroot . "action/{$CONFIG->pluginname}/manage/related_products?manage_action=delete&rpid=" . $related_product->getGUID(),
							'text' => elgg_echo("delete"),
							'confirm' => elgg_echo("related:product:delete:confirm"),
						));  
					?>
				</div>
			<?php } ?>
			<div class="clear"></div>
		</div>
<?php 
	}
?>