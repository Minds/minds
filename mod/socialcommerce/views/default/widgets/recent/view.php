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
 * Elgg widget - recent - view
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */ 
?>
<script type="text/javascript">
$(document).ready(function () {

$('a.show_product_recent_desc').click(function () {
	$(this.parentNode).children("[class=stores_listview_desc3]").slideToggle("fast");
	return false;
});

}); /* end document ready function */
</script>


<?php
	global $CONFIG;
    //the page owner
	$owner = $vars['entity']->owner_guid;
	$widget_guid = $vars['entity']->guid;
	//the number of files to display
	$number = (int) $vars['entity']->num_display;
	if (!$number)
		$number = 1;
		
	//How to display the files
	$display = $vars['entity']->product_display;
	if (!$display)
		$display = 1;
	
	//get the layout view which is set by the user in the edit panel
	$get_view = (int) $vars['entity']->gallery_list;
	if (!$get_view || $get_view == 1) {
	    $view = "list";
    }else{
        $view = "gallery";
    }

	//get the user's files
	if($display == 1){
		//Depricated function replace
		$options = array(	'metadata_name_value_pairs'	=>	array('status' => 1),
						'types'				=>	"object",
						'subtypes'			=>	"stores",
						'owner_guids'		=>	elgg_get_page_owner_guid(),						
						'limit'				=>	$number,
						
					);
		$product = elgg_get_entities_from_metadata($options);
		//$product = get_entities_from_metadata('status',1,"object", "stores", elgg_get_page_owner_guid(), $number);
	}else if($display == 2){
		if ($friends = get_user_friends($user_guid, '', 999999, 0)) {
				$friendguids = '';
			foreach($friends as $friend) {
					$friendguids .=$friend->getGUID(). ",";
			}
			$friendguids = substr_replace($friendguids,'',-1);
			//Depricated function replace
			$options = array(	'metadata_name_value_pairs'	=>	array('status' => 1),
							'types'				=>	"object",
							'subtypes'			=>	"stores",
							'owner_guids'		=>	$friendguids,						
							'limit'				=>	$number,
							
						);
			$product = elgg_get_entities_from_metadata($options);
			//$product = get_entities_from_metadata('status',1,'object','stores',$friendguids,$number);
		}
	}else if($display == 3){
		//Depricated function replace
		$options = array(	'metadata_name_value_pairs'	=>	array('status' => 1),
						'types'				=>	"object",
						'subtypes'			=>	"stores",						
						'limit'				=>	$number,
						
					);
		$product = elgg_get_entities_from_metadata($options);
		//$product = get_entities_from_metadata('status',1,"object", "stores", 0, $number);
	}
	
	//if there are some files, go get them
	if ($product) {
    	
    	echo "<div id=\"stores_widget_layout\">";
        
        if($view == "gallery"){
        
        //echo "<div class=\"stores_widget_galleryview\">";
        	$i = 0;
            //display in gallery mode
            foreach($product as $f){
            	if($i%5 == 0){
					$products_list .= "</tr><tr>";
				}
                $mime = $f->mimetype;
                $product_img = elgg_view("{$CONFIG->pluginname}/image", array(
										'entity' => $f,
										'size' => 'medium',
										'display'=>'image'
									  )
								);
                $product_img = "<a onmouseover=\"recent_products_list_mouseover_action($f->guid,$widget_guid)\" onmouseout=\"recent_products_list_mouseout_action($f->guid,$widget_guid)\" href=\"{$f->getURL()}\">" . $product_img . "</a>";
            	$products_list .= <<<EOF
					<td>
						<div id="recent_products_list_{$f->guid}{$widget_guid}" class="popular_products_list">
							$product_img
						</div>
					</td>
EOF;
				$i++;
            }
            
            //echo "</div>";
            echo $cart_body = <<<EOF
				<script>
					function recent_products_list_mouseover_action(product_guid,widget_guid){
						$("#recent_products_list_"+product_guid+widget_guid).fadeTo("fast", 1); 
						$("#recent_products_list_"+product_guid+widget_guid+" img").css("width","42px");
						$("#recent_products_list_"+product_guid+widget_guid+" img").css("border","1px solid #e89005");
						$("#recent_products_list_"+product_guid+widget_guid+" img").css("padding","1px");
					}
					function recent_products_list_mouseout_action(product_guid,widget_guid){
						$("#recent_products_list_"+product_guid+widget_guid).fadeTo("fast", 0.8); 
						$("#recent_products_list_"+product_guid+widget_guid+" img").css("width","45px");
						$("#recent_products_list_"+product_guid+widget_guid+" img").css("border","none");
						$("#recent_products_list_"+product_guid+widget_guid+" img").css("padding","0");
					}
				</script>
				<div class="contentWrapper">
					<table class="popular_products_list_table">
						<tr>
							{$products_list}
						</tr>
					</table>
					<div style="clear:both;"></div>
				</div>
EOF;
            
        }else{
        	    
            //display in list mode
            foreach($product as $f){
            	
                $mime = $f->mimetype;
               	$description = $f->description;
		        if (!empty($description)){ 
		        	$more = "<a href=\"javascript:void(0);\" class=\"show_product_recent_desc\">". elgg_echo('more') ."</a><br /><div class=\"stores_listview_desc3\">" . $description . "</div>";
		        }
		        $product_icon = elgg_view("{$CONFIG->pluginname}/image", array(
										'entity' => $f,
										'size' => 'medium',
										'display'=>'image'
									  )
								);
		        $time_creatd =  elgg_view_friendly_time($f->time_created);
		        $price_text = elgg_echo('price');
		        $cart_url = addcartURL($f);
				$cart_text = elgg_echo('add:to:cart');
				$wishlist_text = elgg_echo('add:wishlist');
				$hidden = elgg_view('input/securitytoken');
				$cart_wishlist = '';
				if($f->owner_guid != $_SESSION['user']->guid){
						$cart_wishlist = <<<EOF
						<div style='display:block;'>
							<div class="cart_wishlist" style='float:left'>
								<form name="frm_wishlist_{$f->guid}" method="POST" action="{$CONFIG->wwwroot}action/{$CONFIG->pluginname}/add_wishlist">
									<a title="{$wishlist_text}" class="wishlist" onclick=" document.frm_wishlist_{$f->guid}.submit();" href="javascript:void(0);">&nbsp;</a>
									<INPUT type="hidden" name="pgid" value="{$f->guid}">
									{$hidden} 
								</form>
							</div>
							<div class="cart_wishlist" style='float:left'>
								<a title="{$cart_text}" class="cart" href="{$cart_url}">&nbsp;</a>
							</div>
						</div>
EOF;
				}
				$display_price = get_price_with_currency($f->price);
		        $inner_content .= <<<EOF
		        	<div style="clear:both;" class="search_listing">
		        		<div class="stores_listview_icon"><a href="{$f->getURL()}">{$product_icon}</a></div>
		        		<div class="stores_widget_content">
		        			<div class="stores_listview_title"><p class="stores_title">{$f->title}</p></div>
		        			<div class="stores_listview_date"><p class="stores_timestamp"><small>{$time_creatd}</small></p></div>
		        			<div class="product_actions" style="padding-bottom:5px;">
								<div style="clear:both;"></div>
								<div class="price_list">
									{$price_text}: {$display_price}
								</div>
								{$cart_wishlist}
								<div style="clear:both;"></div>	
							</div>
		        			
		        			{$more}
		        		</div>
		        		<div style="clear:both;"></div>
		        	</div>
EOF;
            				
        	}
        	echo $content = <<<EOF
        		<div  class="stores_widget_singleitem stores">
        			{$inner_content}
        			<div style="clear:both;"></div>
        		</div>
EOF;
        	    
        }
        	
        	
        //get a link to the users files
        $users_file_url = $vars['url'] . "{$CONFIG->pluginname}/" . get_user($f->owner_guid)->username;
        	
        //echo "<a href=\"{$users_file_url}\"><B>" . elgg_echo('stores:more') . "</B></a>";
        echo "</div>";
        	
				
	} else {
		
		echo "<div class=\"contentWrapper\">".elgg_echo("stores:none")."</div>";
		
	}

?>