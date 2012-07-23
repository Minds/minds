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
	 * Elgg order - individual view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	$order = $vars['entity'];
	if($order){
			//Depricated function replace
			$options = array('relationship' 		=> 	'order_item',
							'relationship_guid' 	=>	$order->guid);
			$order_items = elgg_get_entities_from_relationship($options);
			//$order_items = get_entities_from_relationship('order_item',$order->guid);
			if($order_items){
				foreach ($order_items as $order_item){
					$icon = "";
					$poduct = get_entity($order_item->product_id);
					if($poduct) {
						$order_item_titles .= "<li>";
						$order_item_titles .= "<a href=\"" . $poduct->getURL() . "\">{$order_item->title}</a>";
							
						$version = get_entity($order_item->version_guid);
						if($version){
							$mimetype = $version->mimetype;
						}else{
							$mimetype = $poduct->mimetype;
							if($mimetype==""){						
								$version = get_latest_version($poduct->guid);
								if($version){							
									$mimetype = $version->mimetype;
								}
							}					
						}
						if($mimetype && $poduct->product_type_id == 2){
							$download_action_url = $CONFIG->wwwroot."action/".$CONFIG->pluginname."/download?product_guid=".$order_item->guid;						
							$download_action_url = elgg_add_action_tokens_to_url($download_action_url);
							$icon .= "<a style='margin-left:15px;' href=\"{$download_action_url}\"><b><i>Download</i></b></a>";
						} else{
							$icon .= "";
						}
						$order_item_titles .= $icon;
						$order_item_titles .= "</li>";
					}
				}
			}
			$order_datre = date("dS M Y", $order->time_created);
			$action = $CONFIG->wwwroot.''.$CONFIG->pluginname.'/order_products/'.$order->guid;
			
?>
			<div class="search_listing">
				<h3><a href="<?php echo $action;?>"><?php echo sprintf(elgg_echo('order:heading'),$order->guid); ?></a></h3>
				<div class="order_sub_con">
					<div><?php echo elgg_echo('order:date').": ".$order_datre; ?> </div>
					<?php
					if($order->s_first_name && $order->s_last_name){
						$order_recipient = elgg_echo('order:recipient').": ".$order->s_first_name." ".$order->s_last_name;
					}else{
						$order_recipient = elgg_echo('order:recipient').": ".$order->b_first_name." ".$order->b_last_name;
					}
					?>
					<div><?php echo $order_recipient; ?> </div>
					<div>
						<div><B><?php echo elgg_echo('order:item:head'); ?></B></div>
						<div>
							<ul>
								<?php echo $order_item_titles; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
<?php
	}
?>