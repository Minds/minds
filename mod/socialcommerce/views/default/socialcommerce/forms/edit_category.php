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
	 * Elgg form - category
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	// Set title, form destination
		if (isset($vars['entity'])) {
			$title = sprintf(elgg_echo("stores:editcate"),$object->title);
			$action = "{$CONFIG->pluginname}/edit_category";
			$title = $vars['entity']->title;
			$product_type_id = $vars['entity']->product_type_id;
			$body = $vars['entity']->description;
			$parent_guid = $vars['entity']->parent_category_id;
		} else {
			$title = elgg_echo("stores:addcate");
			$action = "{$CONFIG->pluginname}/add_category";
			$title = "";
			$body = "";
			$access_id = 2;
			$product_type_id = 1;
		}

	// Just in case we have some cached details
		if (isset($vars['category']['categorytitle'])) {
			$title = $vars['category']['categorytitle'];
			$body = $vars['category']['categorybody'];
			$product_type_id = $vars['category']['product_type_id'];
			$parent_guid = $vars['category']['category_selected'];
		}
		$category_extent .= elgg_view("{$CONFIG->pluginname}/extendCategoryAddView",array('entity'=>$vars['entity']));
?>

<?php
                $title_label = elgg_echo('title');
                $title_textbox = elgg_view('input/text', array('name' => 'categorytitle', 'value' => $title));
                $parent_label = elgg_echo('category:parent');
                $produt_type = elgg_view('input/product_type', array('name' => 'product_type_id', 'value' => $product_type_id,'js'=>'onChange="javascript:get_category();"'));
                
                $text_label = elgg_echo('category:text');
                $text_textarea = elgg_view('input/longtext', array('name' => 'categorybody', 'value' => $body));
                
                $submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save:category')));

                if (isset($vars['container_guid']))
					$entity_hidden = "<input type=\"hidden\" name=\"container_guid\" value=\"{$vars['container_guid']}\" />";
				if (isset($vars['entity']))
					$entity_hidden .= "<input type=\"hidden\" name=\"category_guid\" value=\"{$vars['entity']->getGUID()}\" />";
				
				$entity_hidden .= elgg_view('input/securitytoken');	
				$get_category_url = "{$vars['url']}action/{$CONFIG->pluginname}/get_category";
				$form_body = <<<EOT
				<script language="javascript" type="text/javascript">
					function get_category(){
						var elgg_token = $('[name=__elgg_token]');
						var elgg_ts = $('[name=__elgg_ts]');
						var product_type = $('[name=product_type_id]');
						var category_id = $('[name=category_guid]');
						var parent_guid	= "$parent_guid";													
						$.post("{$get_category_url}", {	
									category_guid:category_id.val(), 							
									product_type: product_type.val(),
									parent_guid: parent_guid,
									page:'category',
									__elgg_token: elgg_token.val(),
									__elgg_ts: elgg_ts.val()
								},
								function(data){
									if(data){
										//div.load(data);
										document.getElementById('cat_tree').innerHTML = data;
										$("#cat_tree").treeview({
											animated: "fast",
											collapsed: true,
											control: "#treecontrol"
										});
									}										
								});	
										
					}
					$(document).ready(function() {										
										get_category();
										});
				</script>
                	<form action="{$vars['url']}action/{$action}" enctype="multipart/form-data" method="post" id="category_form">
						<p>
							<label><span style="color:red">*</span> $title_label</label><br />
				                        $title_textbox
						</p>
						{$produt_type}
						<label>{$parent_label}</label><br />
						<div name = "category_listing" id='category_listing' class = "category_listing">
						<ul id="cat_tree" class="treeview-red">
						</ul>							
						</div>
						<p>
							<label>$text_label</label><br />
				                        $text_textarea
						</p>
						<p>
				        	{$category_extent}
						</p>
						<p>
							$entity_hidden
							$submit_input
						</p>
					</form>
EOT;
echo $form_body;
?>