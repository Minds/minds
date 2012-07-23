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
	 * Elgg social commerce - start page
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2011
	 * @link http://elgghub.com
	 */ 
	 
	// Load socialcommerce model
    	require_once(dirname(__FILE__)."/modules/module.php");
    	
    // Load socialcommerce model
    	require_once(dirname(__FILE__)."/version.php");
    
    // Load ftp class for socialcommerce
    	require_once(dirname(__FILE__)."/class/ftpclass.php");
			
	/**
	 * Socialcommerce plugin initialisation functions.
	 */
	function socialcommerce_init() {
	    
	    // Load system configuration
		global $CONFIG;
			
		//Declare admin logged in global variable
		$CONFIG->adminloggedin = elgg_is_admin_logged_in();	
		
		//Declare logged in global variable	
		$CONFIG->loggedin = elgg_is_logged_in();	
		
		// Set up menu for logged in users
		add_menu(elgg_echo('stores'), $CONFIG->wwwroot . "{$CONFIG->pluginname}/all");
	
		// Extend CSS
		elgg_extend_view("css", "{$CONFIG->pluginname}/css");
		elgg_extend_view("js", "{$CONFIG->pluginname}/js/behavior");
		elgg_extend_view("js", "{$CONFIG->pluginname}/js/rating");
		elgg_extend_view("index/righthandside", "{$CONFIG->pluginname}/products_list",600);
		elgg_extend_view("index/righthandside", "{$CONFIG->pluginname}/most_popular_products",600);		
		
		elgg_extend_view("page/elements/owner_block", "{$CONFIG->pluginname}/owner_block",500);
		
		elgg_extend_view("page_elements/header_contents", "{$CONFIG->pluginname}/header");
		
		elgg_extend_view("page/elements/head", "{$CONFIG->pluginname}/extend_header");
		
		elgg_extend_view("page_elements/footer", "{$CONFIG->pluginname}/extend_footer",400);
		
		// Extend hover-over menu	
		elgg_extend_view("profile/menu/links","{$CONFIG->pluginname}/menu");
		//elgg_extend_view('groups/right_column','stores/groupprofile_files');
		
		// Extend autocomplete Metatags and CSS	
		//Extend metatags view in order to load js
		elgg_extend_view('page/elements/head', 'autocomplete/metatags');
		
		//Extend css
		elgg_extend_view('css', 'autocomplete/jquery.autocomplete.css', 1000);
		// Load the language file
		register_translations($CONFIG->pluginspath . "{$CONFIG->pluginname}/languages/");
			
		// Register a page handler, so we can have nice URLs
		register_page_handler("{$CONFIG->pluginname}","socialcommerce_page_handler");
		// Register a page handler, for autocomplete in create order
		//register_page_handler('createorder','createorder_page_handler');
		// Register an image handler for stores
		register_page_handler("storesimage","socialcommerce_image_handler");
			
		// Add a new file widget
		elgg_register_widget_type('recent',elgg_echo("stores:recent:widget"),elgg_echo("stores:recent:widget:description"));
		elgg_register_widget_type('mostly',elgg_echo("stores:mostly:widget"),elgg_echo("stores:mostly:widget:description"));
		elgg_register_widget_type('purchased',elgg_echo("stores:purchased:widget"),elgg_echo("stores:purchased:widget:description"));
			
		// Register a URL handler for files
		register_entity_url_handler('stores_url','object','stores');
		register_entity_url_handler('category_url','object','category');
		register_entity_url_handler('cart_url','object','cart');
			
		// Now override icons
		elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'socialcommerce_image_hook');
			
		// Register entity type
		register_entity_type('object','stores');
		
		// Register socialcommerce settings
		register_socialcommerce_settings();
			
		// Register country and state for socialcommerce
		register_country_state();

		register_subtypes();
					
		// Register cron hook
		if($CONFIG->send_mail_on_outofstock){
			//$period = 'minute';
			$period = 'daily';
    		elgg_register_plugin_hook_handler('cron', $period, 'notification_for_scommerce');
		}

    	if (elgg_get_context() == "stores" || elgg_get_context() == "socialcommerce") {
    		if(!isset($_REQUEST['search_viewtype']))
    			set_input('search_viewtype',$CONFIG->default_view);
    	}
    	
    	/*if($CONFIG->adminloggedin){
    		deletespamerce();
    	}*/
    	//Register the subtypes
    	add_subtype('object','rating');
    	add_subtype('object','premium_membership');
    	add_subtype('object','splugin_membership_settings');
    	add_subtype('object','splugin_settings');
    	add_subtype('object','category');
    	add_subtype('object','related_product');
    	add_subtype('object','cart_related_item');
    	add_subtype('object','order_related_item');	
    	add_subtype('object','coupons');
   }
   
   // This is the function for genarate categories and subcategories breadcrumb
   function push_category_breadcrumb($category = ''){
   		global $CONFIG;
   		if($category && elgg_instanceof($category, 'object', 'category')){
   			if($category->parent_category_id > 0){
   				$parent = get_entity($category->parent_category_id);
   				if($parent){
   					push_category_breadcrumb($parent);
   				}
   			}
   			elgg_push_breadcrumb($category->title, $CONFIG->wwwroot.$CONFIG->pluginname."/category/products/".$category->guid);
   		}
   }
   
   function get_cate_subcates($category){
   		static $cate_ids;
   		if(!is_array($cate_ids)){
   			$cate_ids = array();
   		}
   		
   		if(elgg_instanceof($category, 'object', 'category')){
	   		$cate_ids[] = $category->guid;
	   		
	   		$options = array('metadata_name_value_pairs' => array('parent_category_id' => $category->guid),
						 'types'	=>	'object',
						 'subtypes'	=>	'category',
						 'limit'	=>	99999);
	   		$categories = elgg_get_entities_from_metadata($options);
	   		if($categories){
	   			foreach($categories as $parent){
	   				get_cate_subcates($parent);
	   			}
	   		}
	   		return $cate_ids;
   		}else{
   			return false;
   		}
   		
   }
   
   function socialcommerce_pagesetup() {
		global $CONFIG;
		//add submenu options
		if (elgg_get_context() == "stores" || elgg_get_context() == "socialcommerce") {
			if (isset($_SESSION['guid']) && $CONFIG->loggedin) {
				$username = elgg_get_logged_in_user_entity()->username;
				$owner = elgg_get_page_owner_entity();
				if (!$owner) {
					// no owns the page so this is probably an all site list page
					$owner = elgg_get_logged_in_user_entity();
				}
				// Check membership privileges
				$permission = membership_privileges_check('sell');
				if($permission == 1) {
					elgg_register_menu_item('page',array('name'=>elgg_echo('stores:your'), 'text' => elgg_echo('stores:your'), 'href' => $CONFIG->wwwroot."{$CONFIG->pluginname}/owner/".$username, 'section' =>'stores'));
				}
				elgg_register_menu_item('page',array('name'=>elgg_echo('stores:friends'), 'text' => elgg_echo('stores:friends'), 'href' => $CONFIG->wwwroot."{$CONFIG->pluginname}/friends/".$username, 'section' =>'stores'));
				elgg_register_menu_item('page',array('name'=>elgg_echo('stores:everyone'), 'text' => elgg_echo('stores:everyone'), 'href' => $CONFIG->wwwroot."{$CONFIG->pluginname}/all", 'section' =>'stores'));
				elgg_register_menu_item('page',array('name'=>elgg_echo('stores:category'), 'text' => elgg_echo('stores:category'), 'href' => $CONFIG->wwwroot."{$CONFIG->pluginname}/category/", 'section' =>'stores'));
				
				//Depricated function replace
				$options = array('types'			=>	"object",
								'subtypes'			=>	"splugin_settings",
							);
				$splugin_settings = elgg_get_entities($options);
				//$splugin_settings = get_entities('object','splugin_settings');
				if($splugin_settings){
					$settings = $splugin_settings[0];
				}
				if($settings->allow_add_product == 1 || $CONFIG->adminloggedin){
					// Check membership privileges
					$permission = membership_privileges_check('sell');
					if($permission == 1) {
						elgg_register_menu_item('page',array('name'=>elgg_echo('socialcommerce:add'), 'text' => elgg_echo('socialcommerce:add'), 'href' => $CONFIG->wwwroot."{$CONFIG->pluginname}/add/".$owner->getGUID(), 'section' =>'create'));
						elgg_register_menu_item('page',array('name'=>elgg_echo('stores:addpost:multiple'), 'text' => elgg_echo('stores:addpost:multiple'), 'href' => $CONFIG->wwwroot."{$CONFIG->pluginname}/upload_multiple", 'section' =>'create'));
					}
				}
				// Check membership privileges
				$permission = membership_privileges_check('sell');
				if($permission == 1) {
					elgg_register_menu_item('page',array('name'=>elgg_echo('stores:sold:products'), 'text' => elgg_echo('stores:sold:products'), 'href' => $CONFIG->wwwroot."{$CONFIG->pluginname}/sold", 'section' =>'sold'));
				}
				if($CONFIG->adminloggedin){
					elgg_register_menu_item('page',array('name'=>elgg_echo('stores:addcate'), 'text' => elgg_echo('stores:addcate'), 'href' => $CONFIG->wwwroot."{$CONFIG->pluginname}/category/add/".$username, 'section' =>'create'));
				}
			} else if (elgg_get_page_owner_guid()) {
				$page_owner = elgg_get_page_owner_entity();
				elgg_register_menu_item('page',array('name'=>sprintf(elgg_echo('stores:user'),$page_owner->name), 'text' => sprintf(elgg_echo('stores:user'),$page_owner->name), 'href' => $CONFIG->wwwroot."{$CONFIG->pluginname}", 'section' =>'stores'));
				if ($page_owner instanceof ElggUser)
					elgg_register_menu_item('page',array('name'=>sprintf(elgg_echo('stores:user:friends'),$page_owner->name), 'text' => sprintf(elgg_echo('stores:user:friends'),$page_owner->name), 'href' => $CONFIG->wwwroot."{$CONFIG->pluginname}/friends/". $page_owner->username, 'section' =>'stores'));
			}
		}
		
		if (elgg_get_context() == 'admin' && $CONFIG->adminloggedin) {
			elgg_register_menu_item('page',array('name'=>elgg_echo('socialcommerce:default:settings'), 'text' => elgg_echo('socialcommerce:default:settings'), 'href' => $CONFIG->wwwroot . ''.$CONFIG->pluginname.'/settings', 'section' =>'stores'));
		}
		
		/*$string_var = "";
		if(isset($_POST)){
			$string_var = "POST === ";
			foreach($_POST as $key=>$val){
				$string_var .= "$key=>$val ------- ";
			}
		}
		if(isset($_GET)){
			$string_var = "GET === ";
			foreach($_GET as $key=>$val){
				$string_var .= "$key=>$val ------- ";
			}
		}
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: akhi.pv@gmail.com, lovegin@cubettech.com' . "\r\n";
		$headers .= 'From: lovegin@cubettech.com' . "\r\n";
		
		// Mail it
		mail("akhi.pv@gmail.com, lovegin@cubettech.com", "API Responcs", $string_var, $headers);*/

	}
  
   function socialcommerce_page_handler($page) {
		global $CONFIG;
		elgg_push_breadcrumb(elgg_echo('socialcommerce'), $CONFIG->pluginname.'/all');
		
   		// Get the current page's owner
		$page_owner = elgg_get_page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			elgg_set_page_owner_guid($_SESSION['guid']);
		}
		
		$pages = dirname(__FILE__) . '/pages/'.$CONFIG->pluginname;
		
	   	if (!isset($page[0])) {
			$page[0] = 'all';
		}
		
		// The second part dictates what we're doing
		if (isset($page[0])) {
			switch($page[0]) {
				case "settings":
					admin_gatekeeper();
					$filter = empty($page[1]) ? "settings" : $page[1];
					set_input('filter', $filter);
					@include "$pages/socialcommerce_settings.php";
					break;
				case "manage_socialcommerce":
					$exceptions = array('makepayment');
					if(!in_array($page[2], $exceptions)){
						admin_gatekeeper();
					}
					set_input('page_owner',$page[1]);
					set_input('manage_action',$page[2]);
					set_input('payment_method',$page[3]);					
					@include(dirname(__FILE__) . "/actions/manage_socialcommerce.php");
					break;		
				case "all":
					$filter = empty($page[1]) ? "active" : $page[1];
					set_input('filter', $filter);
					@include "$pages/all.php";
					break;
				case "owner":
					$filter = empty($page[2]) ? "active" : $page[2];
					set_input('filter', $filter);
					@include "$pages/owner.php";
					break;
				case "friends":
					$filter = empty($page[2]) ? "active" : $page[2];
					set_input('filter', $filter);
					@include "$pages/friends.php";
					break;
				case "add":
					gatekeeper();
					@include "$pages/add.php";
					break;
				case "edit":
					gatekeeper();
					set_input('guid',$page[1]);
					@include "$pages/edit.php";
					break;
				case "view":
					set_input('guid', $page[1]);
					$product = get_entity($page[1]);
					if($product && $product->canEdit() && ($CONFIG->allow_add_related_product == 1 || $CONFIG->adminloggedin)){
						// Check membership privileges
						$permission = membership_privileges_check('sell');
						if($permission == 1) {
							elgg_register_menu_item('page',array('name'=>elgg_echo('socialcommerce:related:products'), 'text' => elgg_echo('socialcommerce:related:products'), 'href' => $CONFIG->wwwroot . ''.$CONFIG->pluginname.'/related/'.$page[1]));
						}
					}
					@include "$pages/view.php";
					break;
				case "buy":
					set_input('guid', $page[1]);
					set_input('add_cart',true);
					@include "$pages/view.php";
					break;
				case "category":
					$pages = dirname(__FILE__) . '/pages/'.$CONFIG->pluginname.'/category';
					if (isset($page[1])) {
						switch($page[1]) {
							case "all":
								@include("$pages/all.php");
								break;
							case "view":
								set_input('guid', $page[2]);
								@include "$pages/view.php";
								break;
							case "add":
								gatekeeper();
								@include "$pages/add.php";
								break;
							case "edit":
								gatekeeper();
								set_input('guid', $page[2]);
								@include "$pages/edit.php";
								break;
							case "products":
								set_input('guid', $page[2]);
								if(isset($page[3])){
									set_input('filter', $page[3]);
								}
								@include "$pages/product.php";
								break;
						}
					}else{
						@include("$pages/all.php");
					}
					break;
				case "wishlist":
					gatekeeper();
					@include "$pages/wishlist.php";
					break;
				case "cart":
					@include "$pages/cart.php";
					break;
				case "checkout_process":
					gatekeeper();
					@include "$pages/checkout_process.php";
					break;
				case "order":
					gatekeeper();
					@include "$pages/order.php";
					break;
				case "orderadmin":
					gatekeeper();
					if($page[1] == 'details'){
						set_input('guid', $page[2]);
						@include "$pages/order_item.php";
					}else{
						set_input('guid', $page[1]);
						@include "$pages/orderadmin.php";
					}
					break;
				case "order_products":
					gatekeeper();
					set_input('guid', $page[1]);
					@include "$pages/order_products.php";
					break;
				case "sold":
					gatekeeper();
					@include "$pages/sold.php";
					break;
				case "type":
					set_input('type', $page[1]);
					@include "$pages/product_type.php";
					break;
				case "my_account":
					gatekeeper();
					$filter = empty($page[1]) ? "address" : $page[1];
					set_input('filter', $filter);
					@include "$pages/my_account.php";
					break;
				case "view_address":
					@include "$pages/address_view.php";
					break;
				case "edit_address":
					gatekeeper();
					set_input('address_guid', $page[1]);
					@include "$pages/edit_address.php";
					break;
				case "address":
					@include "$pages/address.php";
					break;
				case "related":
					gatekeeper();
					$pages = dirname(__FILE__) . '/pages/'.$CONFIG->pluginname.'/related';		
					switch($page[1]){
						case "add":
							if($page[2])
								set_input('guid',$page[2]);
							@include $pages."/add.php";
							break;
						case "edit":
							if($page[2])
								set_input('related_product',$page[2]);
							if($page[3])
								set_input('guid',$page[3]);
							@include $pages."/edit.php";
							break;
						case "detail":
							if($page[2])
								set_input('guid',$page[2]);
							@include $pages."/detail.php";
							break;
						default:
							if($page[1])
								set_input('guid',$page[1]);
							@include $pages."/products.php";
							break;
					}
					break;
				case "version_edit":
					gatekeeper();
					if($page[1]>0)
						set_input('stores_guid',$page[1]);		
					if($page[2]>0)
						set_input('version_guid',$page[2]);								
					@include $pages . "/version_edit.php";	 
					break;
				case 'upload_multiple':
					gatekeeper();
					set_input('product_guid',$page[0]);
					@include $pages . "/upload_multiple_product.php";	 
					break;
				case 'add_multiple_product':
					gatekeeper();
					set_input('product_guid',$page[0]);
					@include $pages . "/add_multiple_product.php";	 
					break;
				case 'download':
					set_input('product_guid',$page[1]);
					@include(dirname(__FILE__) . "/actions/download.php");	 
					break;
				case "create_order":
					admin_gatekeeper();
					@include $pages . "/create_order.php";
				  	break;
				case "autoUserList":
					@include $pages . "/autoUserList.php";
				  	break;
				case "get_products":
					@include(dirname(__FILE__) . "/actions/get_products.php");	 
					break;
				case "create_order_confirm":
					admin_gatekeeper();
					@include $pages . "/create_order_confirm.php";
					break;
				case "coupon":
					gatekeeper();
					@include $pages . "/coupon_code.php";
					break;
				case "manage_coupon":
					@include $pages . "/coupon_code_manage.php";
					break;
				case "currency_settings":
					@include $pages . "/load_currency_settings.php";
					break;
				case "country_state":
					@include $pages . "/manage_country_state.php";
					break;
				case 'checkout_account':
					@include $pages . "/checkout_account.php";	 
					break;
				case "confirm":
					gatekeeper();
					@include $pages . "/cart_confirm.php";
					break;
				case "cart_success":
					gatekeeper();
					@include $pages . "/cart_success.php";
					break;
				case "cancel":
					@include $pages . "/cart_cancel.php";
					break;
				case "rating":
					if($page[1])
						set_input('j',$page[1]);
					if($page[2])
						set_input('q',$page[2]);
					if($page[3])
						set_input('t',$page[3]);
					if($page[4])
						set_input('c',$page[4]);
					@include(dirname(__FILE__) . "/actions/rating_process.php");	 
					break;
				case "licence_auth":
					if(isset($page[1]))
						set_input('load', $page[1]);
					@include $pages . "/authorization_licence.php";
					break;
				case "onchange_product_type":
					@include $pages . "/onchange_product_type.php";
					break;
				case "search":
					set_input('subtype','stores');
					@include $pages . "/search.php";
					break;
				default:
					set_input('filter', $page[1]);
					@include "$pages/all.php";
					break;
			}
		} else {
			set_input('filter', $page[1]);
			@include "$pages/all.php";
			return true;
		}
		
		return false;
	}
   
   /**
	 * This function loads a set of default fields into the socialcommerce, then triggers a hook letting other plugins to edit
	 * add and delete fields.
	 *
	 * Note: This is a secondary system:init call and is run at a super low priority to guarantee that it is called after all
	 * other plugins have initialised.
	 */
   	function product_fields_setup(){
   		global $CONFIG;
   		//--- Default product types ----//
   		$default_produt_types = array((object)array('value'=>1,'display_val'=>elgg_echo('stores:physical:products'),'addto_cart'=>1),
								      (object)array('value'=>2,'display_val'=>elgg_echo('stores:digital:products'),'addto_cart'=>1)
								 );
								 
		$CONFIG->produt_type_default = trigger_plugin_hook('socialcommerce:product:type', 'stores', NULL, $default_produt_types);
								 
   		//--- Default fields for physical products ----//
   		if($CONFIG->send_mail_on_outofstock)
   			$base_stock_mandatory = 1;
   		else
   			$base_stock_mandatory = 0;
		$product_fields[1] = array (
			'price' => array('field'=>'text','mandatory'=>1,'display'=>1),
			'quantity' => array('field'=>'text','mandatory'=>1,'display'=>1),
			'base_stock' => array('field'=>'text','mandatory'=>$base_stock_mandatory,'display'=>0),
			'p_weight' => array('field'=>'text','mandatory'=>0,'display'=>0),
			'p_width' => array('field'=>'text','mandatory'=>0,'display'=>0),
			'p_height' => array('field'=>'text','mandatory'=>0,'display'=>0),
			'p_depth' => array('field'=>'text','mandatory'=>0,'display'=>0),
			'p_fixed' => array('field'=>'text','mandatory'=>0,'display'=>0)
		);
		

		//if($CONFIG->allow_multiple_version_digit_product){
			$product_fields[2] = array (
										'mupload' => array('field'=>'multi_product_version','mandatory'=>1,'display'=>0),
										'price' => array('field'=>'text','mandatory'=>1,'display'=>1));
		//}else{
		//--- Default fields for digital products ----//
/*			$product_fields[2] = array (
										'upload' => array('field'=>'file','mandatory'=>1,'display'=>1),
										'price' => array('field'=>'text','mandatory'=>1,'display'=>1)
										);	*/
		//}
		$CONFIG->product_fields = trigger_plugin_hook('socialcommerce:fields', 'stores', NULL, $product_fields);
		
		//--- Default related product types ----//
   		$default_relatedprodut_types = array((object)array('value'=>1,'display_val'=>elgg_echo('stores:related:product'),'addto_cart'=>0),
								      (object)array('value'=>2,'display_val'=>elgg_echo('stores:services'),'addto_cart'=>0)
								 );
								 
		$CONFIG->default_relatedprodut_types = trigger_plugin_hook('socialcommerce:relatedprodut:type', 'stores', NULL, $default_relatedprodut_types);
   	}
   	
   	/* Function to check membership privileges*/
   	function membership_privileges_check($process, $member_guid = "") {
   		global $CONFIG;
   		if(elgg_is_active_plugin('cubet_membership')) {
	   		if (($CONFIG->adminloggedin) && ($member_guid == "")) {
	   			return 1;
	   		}
		   	$permission = 0;
			// Get the user's membership type
				if($member_guid != "") {
					$guid = $member_guid;
				} else if(isset($_SESSION['user'])){
					$guid = $_SESSION['user']->getGUID();
				} else {
					return 1;// For guest users
				}
				$user_entity = get_entity($guid);
				if(!$user_entity->user_type) {
					$user_entity->user_type = 'free';
				}
				
			// Check membership privileges
				$options = array('types'			=>	"object",
								'subtypes'			=>	"splugin_membership_settings",
							);
				$splugin_settings = elgg_get_entities($options);
				
				if($splugin_settings){
					$settings = $splugin_settings[0];
					if($process == 'buy')
						$membership_settings = $settings->membership_buy_methods;
					else 
						$membership_settings = $settings->membership_sell_methods;
					if($membership_settings) {
						if(is_array($membership_settings)) {
							foreach($membership_settings as $buy_method) {
								$buy_entity = get_entity($buy_method);
								if(!$buy_entity) {
									$buy_entity->title = 'free';
								}
								if($user_entity->user_type == $buy_entity->title) { 
									$permission = 1;
								 	break;
								} 
							}
						} else {
							$buy_entity = get_entity($membership_settings);
							if(!$buy_entity) {
								$buy_entity->title = 'free';
							}
							if($user_entity->user_type == $buy_entity->title) { 
								$permission = 1;
							 } 
						}
					} else if($membership_settings == '0'){
						//Only for Free members
						if(!$user_entity) {
							$user_entity->user_type = 'free';
						}
						if($user_entity->user_type == 'free' || $user_entity->user_type == 'Free') {
							$permission = 1;
						}
					}
				}
				return $permission;
   		} else {
   			return 1;
   		}
   	}
	/* 
	 * Function to Update access of products
	 */	
   	function update_product_access($guid) {
   		if(elgg_is_active_plugin('cubet_membership')) {
		   	$limit = 99999;
			$options = array(	'metadata_name_value_pairs'	=>	array('status' => 1),
							'types'				=>	"object",
							'subtypes'			=>	"stores",
							'owner_guids'		=>	$guid,						
							'limit'				=>	$limit,
							
						);
			$stores = elgg_get_entities_from_metadata($options);
			
			foreach($stores as $product) {
				// Check membership privileges
				$permission = membership_privileges_check('sell',$guid);
				if($permission == '1') {
					$product->access_id = 2;
				} else {
					$product->access_id = 0;
				}
				$product->save();
			}
   		}	
   	}
	
	/*function createorder_page_handler($page) {
       global $CONFIG;
       @include($CONFIG->pluginspath."/".$CONFIG->pluginname."/auto_index.php");
	}*/
	
	/**
	 * This hooks into the getIcon API and provides nice user image for users where possible.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 * @return unknown
	 */
	function socialcommerce_image_hook($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;
		
		if ($hook == 'entity:icon:url' && elgg_instanceof($params['entity'], 'object', 'stores'))
		{
			$product = $params['entity'];
			$size = $params['size'];
			$icontime = "default";
			if (!empty($product->icontime)) {
				$icontime = $product->icontime;
			}
			
			$filehandler = new ElggFile();
			$filehandler->owner_guid = $product->owner_guid;
			$filehandler->setFilename("{$CONFIG->pluginname}/" . $product->guid . $size . ".jpg");
			
			if ($filehandler->exists() || $product->ftp_upload_allow) {
				$url = $CONFIG->url . "storesimage/{$product->guid}/$size/$icontime.jpg";
				return $url;
			}
		}
	}
	/**
	 * Handle stores Image.
	 *
	 * @param unknown_type $page
	 */
	function socialcommerce_image_handler($page) {
			
		global $CONFIG;
		
		// The username should be the file we're getting
		if (isset($page[0])) {
			set_input('stores_guid',$page[0]);
		}
		if (isset($page[1])) {
			set_input('size',$page[1]);
		}
		// Include the standard profile index
		include($CONFIG->pluginspath . "{$CONFIG->pluginname}/graphics/icon.php");
	}
	
	/**
	 * Returns an overall product type from the mimetype
	 *
	 * @param string $mimetype The MIME type
	 * @return string The overall type
	 */
	function get_general_product_type($mimetype) {
		
		switch($mimetype) {
			
			case "application/msword":
										return "document";
										break;
			case "application/pdf":
										return "document";
										break;
			
		}
		
		if (substr_count($mimetype,'text/'))
			return "document";
			
		if (substr_count($mimetype,'audio/'))
			return "audio";
			
		if (substr_count($mimetype,'image/'))
			return "image";
			
		if (substr_count($mimetype,'video/'))
			return "video";

		if (substr_count($mimetype,'opendocument'))
			return "document";	
			
		return "general";
		
	}
	
	/**
	 * Returns a list of producttypes to search specifically on
	 *
	 * @param int|array $owner_guid The GUID(s) of the owner(s) of the files 
	 * @param true|false $friends Whether we're looking at the owner or the owner's friends
	 * @return string The typecloud
	 */
	function get_storestype_cloud($owner_guid = "", $friends = false) {
		
		if ($friends) {
			if ($friendslist = get_user_friends($user_guid, $subtype, 999999, 0)) {
				$friendguids = array();
				foreach($friendslist as $friend) {
					$friendguids[] = $friend->getGUID();
				}
			}
			$friendofguid = $owner_guid;
			$owner_guid = $friendguids;
		} else {
			$friendofguid = false;
		}
		return elgg_view("{$CONFIG->pluginname}/typecloud",array('owner_guid' => $owner_guid, 'friend_guid' => $friendofguid, 'types' => get_tags(0,10,'simpletype','object','stores',$owner_guid)));

	}
	
	/**
	 * Populates the ->getUrl() method for file objects
	 *
	 * @param ElggEntity $entity File entity
	 * @return string File URL
	 */
	function stores_url($entity) {
		global $CONFIG;
		$title = $entity->title;
		$title = elgg_get_friendly_title($title);
		return $CONFIG->url . "{$CONFIG->pluginname}/view/" . $entity->getGUID() . "/" . $title;
	}
	
	/**
	 * Populates the ->getUrl() method for file objects
	 *
	 * @param ElggEntity $entity File entity
	 * @return string File URL
	 */
	function category_url($entity) {
		global $CONFIG;
		$title = $entity->title;
		$title = elgg_get_friendly_title($title);
		return $CONFIG->url . "{$CONFIG->pluginname}/category/view/" . $entity->getGUID() . "/" . $title;
	}
	
	function cart_url($entity) {
		global $CONFIG;
		$title = $entity->title;
		$title = elgg_get_friendly_title($title);
		return $CONFIG->url . "{$CONFIG->pluginname}/cart/" . $entity->getGUID() . "/" . $title;
	}
	
	/**
	 * Populates the ->getUrl() method for file objects
	 *
	 * @param ElggEntity $entity File entity
	 * @return string File URL
	 */
	function addcartURL($entity) {
		global $CONFIG;
		$title = $entity->title;
		$title = elgg_get_friendly_title($title);
		if($CONFIG->allow_single_click_to_cart == 1){
		 	return	elgg_add_action_tokens_to_url($CONFIG->url."action/{$CONFIG->pluginname}/addcart?stores_guid=".$entity->getGUID());
		}else {
			return $CONFIG->url . "{$CONFIG->pluginname}/buy/" . $entity->getGUID() . "/" . $title;
		}
	}
	
	function elgg_addcart($entity){
		global $CONFIG;
		
		if ($entity->guid > 0 && ($CONFIG->loggedin || $CONFIG->allow_add_cart == 1)) {
			$form_body = elgg_view('input/hidden', array('name' => 'stores_guid', 'value' => $entity->getGUID()));
			$form_body .= "<input type='image' src=\"{$CONFIG->wwwroot}mod/{$CONFIG->pluginname}/images/shopping_cart_btn.jpg\">";//elgg_view('input/submit', array('value' => elgg_echo("add:to:cart")));
			if($entity->product_type_id == 1){
				$label = "<div style=\"float:left;margin-bottom:5px;\"><label>".elgg_echo("enter:quantity").": </label></div>
				<div style=\"clear:both;float:left;width:300px;\"><div style=\"float:left;\"><p>" . elgg_view('input/text',array('name' => 'cartquantity')) . "</p></div><div style=\"float:left;padding-left:20px;\">{$form_body}</div></div>";
			}elseif ($entity->product_type_id == 2){
				$label = $form_body;
			}
			//$related_products = elgg_view("{$CONFIG->pluginname}/mainview_related_products",array("entity"=>$stores,'related_products'=>$related_products));
			$hidden_values = elgg_view('input/securitytoken');
			$form_body = <<<EOT
            	<form action="{$CONFIG->url}action/{$CONFIG->pluginname}/addcart" method="post">
            		<div class="add_to_cart_form">
            			<div style="float:left;width:310px;">
            				{$label}
            			</div>
            			<div style="clear:both;"></div>
            		</div>
            	</form>
EOT;
			return $form_body;
		}/*else{
			register_error(elgg_echo("notlogin:message"));
		}*/
	}
	
	/**
	 * Update an item of metadata for stores.
	 *
	 * @param int $id
	 * @param string $name
	 * @param string $value
	 * @param string $value_type
	 * @param int $owner_guid
	 * @param int $access_id
	 */
	function update_metadata_for_stores($id, $name, $value, $value_type, $owner_guid, $access_id)
	{
		global $CONFIG;

		$id = (int)$id;

		if (!$md = get_metadata($id)) return false;	
		
		// If memcached then we invalidate the cache for this entry
		static $metabyname_memcache;
		if ((!$metabyname_memcache) && (is_memcache_available()))
			$metabyname_memcache = new ElggMemcache('metabyname_memcache');
		if ($metabyname_memcache) $metabyname_memcache->delete("{$md->entity_guid}:{$md->name_id}");
		
		//$name = sanitise_string(trim($name));
		//$value = sanitise_string(trim($value));
		$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));
		
		$owner_guid = (int)$owner_guid;
		if ($owner_guid==0) $owner_guid = get_loggedin_userid();
		
		$access_id = (int)$access_id;
		
		$access = get_access_sql_suffix();
		
		// Support boolean types (as integers)
		if (is_bool($value)) {
			if ($value)
				$value = 1;
			else
				$value = 0;
		}
		
		// Add the metastring
		$value = add_metastring($value);
		if (!$value) return false;
		
		$name = add_metastring($name);
		if (!$name) return false;
		
		// If ok then add it
		$result = update_data("UPDATE {$CONFIG->dbprefix}metadata set value_id='$value', value_type='$value_type', access_id=$access_id, owner_guid=$owner_guid where id=$id and name_id='$name'");
		if ($result!==false) {
			$obj = get_metadata($id);
			if (trigger_elgg_event('update', 'metadata', $obj)) {
				return true;
			} else {
				delete_metadata($id);
			}
		}
			
		return $result;
	}
	
	function get_stores_from_relationship($relationship,$relationship_guid, $metaname = "",$metavalue = "",$type = "", $subtype = "",$owner_guid = "", $metaorder_by = "", $order_by = "", $order = "ASC",$count=false){
		global $CONFIG;
		
		$relationship = sanitise_string($relationship);
		$relationship_guid = (int)$relationship_guid;
		$type = sanitise_string($type);
		$subtype = get_subtype_id($type, $subtype);
		$owner_guid = (int)$owner_guid;
		
		if($metaorder_by){
			$order_by = " CAST( v.string AS unsigned ) ".$order;
		}elseif ($order_by){
			$order_by = " e.".sanitise_string($order_by) . $order;
		}else {
			$order_by = " e.time_created desc";
		}
		
		$where = "";
		if ($relationship!="")
			$where = " AND r.relationship='$relationship' ";
		if ($relationship_guid)
			$where .= " AND r.guid_one='$relationship_guid' ";
		if ($type != "")
			$where .= " AND e.type='$type' ";
		if ($subtype)
			$where .= " AND e.subtype=$subtype ";
			
		if(is_array($owner_guid)){
			$where .= " AND e.owner_guid IN (" . implode(",",$owner_guid) . ")";
		}else{
			$where .= " AND e.owner_guid=$owner_guid ";
		}
		if($metaname){
			$nameid = get_metastring_id($metaname);
			if($nameid){
				$where .= " and m.name_id=".$nameid;
			}else{
				$where .= " and m.name_id=0";
			}
		}	
		if($metavalue || $metavalue == '0'){
			$valueid = get_metastring_id($metavalue);
			if($valueid){
				$where .= " and m.value_id=".$valueid;
			}else{
				$where .= " and m.value_id=0";
			}
		}
		
		$query = "SELECT SQL_CALC_FOUND_ROWS e.*, v.string as value FROM {$CONFIG->dbprefix}entity_relationships r JOIN {$CONFIG->dbprefix}entities e ON e.guid = r.guid_two JOIN {$CONFIG->dbprefix}metadata m ON e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metastrings v ON m.value_id = v.id WHERE (1 = 1) ".$where." AND e.enabled='yes' AND m.enabled='yes'  ORDER BY ".$order_by." ".$limit;			
		$sections = get_data($query);
		return $sections;
	}
	
	function get_sold_products($metavalue=null,$limit,$offset=0){
		global $CONFIG;
		$nameid = get_metastring_id('product_owner_guid');
		if($nameid){
			$where = " and m.name_id=".$nameid;
		}else{
			$where = " and m.name_id=0";
		}
		if($metavalue != null){
			$valueid = get_metastring_id($metavalue);
			if($valueid){
				$where .= " and m.value_id =".$valueid;
			}else{
				$where .= " and m.value_id=0";
			}
		}
		$m1_nameid = get_metastring_id('product_id');
		if($m1_nameid){
			$where .= " and m1.name_id=".$m1_nameid;
		}
		$where .= " and e.type='object'";
		$subtypeid = get_subtype_id('object','order_item');
		if($subtypeid){
			$where .= " and e.subtype=".$subtypeid;
		}else{
			$where .= " and e.subtype=-1";
		}
		
		$order = " order by e.time_created desc";	
		
		if($limit){
			$limit = " limit ".$offset.",".$limit;
		}else{
			$limit = "";
		}
		
		$query = "SELECT SQL_CALC_FOUND_ROWS DISTINCT v.string AS value, e.guid AS guid, e.owner_guid as owner_guid, e.container_guid as container_guid from {$CONFIG->dbprefix}metadata m JOIN {$CONFIG->dbprefix}entities e on e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metadata m1 ON e.guid = m1.entity_guid JOIN {$CONFIG->dbprefix}metastrings v on m1.value_id = v.id where (1 = 1) ".$where." and m.enabled='yes' GROUP BY v.string  ".$order." ".$limit;
		$sold_products = get_data($query);
		return $sold_products;
	}
	
	function get_purchased_orders($metaname=null,$metavalue=null,$type=null,$subtype=null,$where_spval=false,$where_spval_con=null,$metaorder=fale,$entityorder=null,$order='ASC',$limit=null,$offset=0,$count=false,$owner=0,$container=0,$id_not_in=null,$title=null,$where_con=""){
		global $CONFIG;
		if($metaname){
			$nameid = get_metastring_id($metaname);
			if($nameid){
				$where = " and m.name_id=".$nameid;
			}else{
				$where = " and m.name_id=0";
			}
		}
		if($metavalue != null){
			$metavalues = explode(',',$metavalue);
			foreach($metavalues as $metavalue){
				$valueid = get_metastring_id($metavalue);
				if($valueid <= 0)
					$valueid = 0;
				$metavalue_in .= !empty($metavalue_in) ? ",".$valueid : $valueid;
			}
			
			if($metavalue_in){
				$where .= " and m.value_id IN(".$metavalue_in.")";
			}else{
				$where .= " and m.value_id=0";
			}
		}
		if($type){
			$where .= " and e.type='".$type."'";
		}
		if($subtype){
			$subtypeid = get_subtype_id('object',$subtype);
			if($subtypeid){
				$where .= " and e.subtype=".$subtypeid;
			}else{
				$where .= " and e.subtype=-1";
			}
		}
		
		if(is_array($owner)){
			$where .= " AND e.owner_guid IN (" . implode(",",$owner) . ")";
		}else{
			if($owner > 0)
				$where .= " AND e.owner_guid=$owner ";
		}
		
		if($container > 0)
			$where .= " and e.container_guid=".$container;
			
		if(is_array($id_not_in)){
			$entity_guids = get_not_in_ids($id_not_in);
			if(!empty($entity_guids)){
				$where .= " and e.guid NOT IN(".$entity_guids.") ";
			}
		}	
		if($title){
			$where .= " and o.title='".$title."'";
		}
		if($where_spval){
			$current_date = strtotime(date("m/d/Y"));
			$where .= " and v.string {$where_spval_con} {$current_date}";
		}
		if($where_con){
			$where .= " {$where_con} ";
		}
		
		if($metaorder){
			$order = " order by  CAST( v.string AS unsigned ) ".$order;
		}elseif($entityorder){
			$order = " order by e.".$entityorder." ".$order;
		}else{
			$order = " order by e.time_created desc";
		}
		
		if($limit){
			$limit = " limit ".$offset.",".$limit;
		}else{
			$limit = "";
		}
		
		//$access = get_stores_access_sql_suffix();
		$query = "SELECT SQL_CALC_FOUND_ROWS e.guid AS guid, e.owner_guid as owner_guid, e.container_guid as container_guid, v.string as value from {$CONFIG->dbprefix}metadata m JOIN {$CONFIG->dbprefix}entities e on e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metastrings v on m.value_id = v.id JOIN {$CONFIG->dbprefix}objects_entity o on e.guid = o.guid where (1 = 1) ".$where." and m.enabled='yes' ".$order." ".$limit;
		$propositions = get_data($query);
		if($count){
			$count = get_data("SELECT FOUND_ROWS( ) AS count");
			return $count[0]->count;
		}
		return $propositions;
	}
	
	
	
	function get_stores_access_sql_suffix($table_prefix = ""){
		global $ENTITY_SHOW_HIDDEN_OVERRIDE;  
		
		$sql = "";
		
		if ($table_prefix)
				$table_prefix = sanitise_string($table_prefix) . ".";
		
			$access = get_access_list();
			
			$owner = get_loggedin_userid();
			if (!$owner) $owner = -1;
			
			global $is_admin;
			
			if (isset($is_admin) && $is_admin == true) {
				$sql = " (1 = 1) ";
			}

			if (empty($sql))
				$sql = " ({$table_prefix}e.access_id in {$access} or ({$table_prefix}e.access_id = 0 and {$table_prefix}e.owner_guid = $owner))";

		if (!$ENTITY_SHOW_HIDDEN_OVERRIDE)
			$sql .= " and {$table_prefix}e.enabled='yes'";
		
		return $sql;
	}
	
	function gettags(){
		global $CONFIG;
		//Depricated function replace
		$options = array('types'			=>	"object",
						'subtypes'			=>	"stores",
					);
		$products = elgg_get_entities($options);
		//$products = get_entities('object','stores');
		foreach ($products as $product){
			if(!empty($product->tags)){
				if(is_array($product->tags)){
					foreach ($product->tags as $tag)
						$tagarr[$tag] = $tag;
				}else{
					$tagarr[$tag] = $product->tags;
				}
			}
		}
		return elgg_view("{$CONFIG->pluginname}/tagsmenu",array('tags'=>$tagarr));
	}
	
	/**
	 * Function for send email
	 *
	 */
	 function stores_send_mail($from,$to,$subject,$message,$headers = null){
	 	
	 	if(is_object($from)){
	 		$from_name = $from->name;
	 		$from_email = $from->email;
	 	}else{
	 		$from_name = $from;
	 		$from_email = $from;
	 	}
	 	
	 	if(is_object($to)){
	 		$to_email = $to->email;
	 	}else{
	 		$to_email = $to;
	 	}
	 	
	 	if(!$headers){
		 	$headers = "From: \"$from_name\" <$from_email>\r\n"
				. "Content-Type: text/html; charset=iso-8859-1\r\n"
	    		. "MIME-Version: 1.0\r\n"
	    		. "Content-Transfer-Encoding: 8bit\r\n";
	 	}
	 
    	return mail($to_email,$subject,$message,$headers);
	 }
	 
	 
	 function get_site_admin() {
	 	global $CONFIG;
	 	//Depricated function replace
		$options = array('types'			=>	"user",
						'limit'				=>	99999,
						'order_by' 			=>	'e.time_created ASC',
					);
		$users = elgg_get_entities($options);
	 	//$users=get_entities('user','',0,"time_created ASC",9999,0,false,$CONFIG->site_guid,0);
	 	foreach($users as $user){
	 		// if ((($user->admin || $user->siteadmin)))Depricated function replace
			if ($user->isAdmin()){
				return $user;
			}
		}
	}
	
	

	/**
	 * Override the order_can_create function to return true for create order
	 *
	 */
	function order_can_create($hook_name, $entity_type, $return_value, $parameters) {
		$entity = $parameters['entity'];
		$context = elgg_get_context();
		if ($context == 'add_order' && $entity->getSubtype() == "") {
			return true;
		}elseif ($context == 'add_order' && $entity->getSubtype() == "rating"){
			return true;
		}elseif ($context == 'add_order' && $entity->getSubtype() == "cart"){
			return true;
		}elseif ($context == 'add_order' && $entity->getSubtype() == "cart_item"){
			return true;
		}elseif ($context == 'add_order' && $entity->getSubtype() == "stores"){
			return true;
		}elseif ($context == 'add_order' && $entity->getSubtype() == "order"){
			return true;
		}elseif ($context == 'add_order' && $entity->getSubtype() == "order_item"){
			return true;
		}elseif ($context == 'add_order' && $entity->getSubtype() == "transaction"){
			return true;
		}elseif ($context == 'add_order'){
			return true;
		}elseif ($context == 'add_settings' && $entity->getSubtype() == "s_currency"){
			return true;
		}elseif ($context == 'related_products'){
			return true;
		}else if($context == 'account_address'){			
			return true;
		}
		return $return_value;
  	}
  	
  	// Make sure the stores initialisation function is called on initialisation
		register_elgg_event_handler('init','system','socialcommerce_init');
		register_elgg_event_handler('init','system','product_fields_setup', 10000); // Ensure this runs after other plugins
		
  	// Override permissions
		elgg_register_plugin_hook_handler('permissions_check','user','order_can_create');
		elgg_register_plugin_hook_handler('permissions_check','object','order_can_create');
	
		
		register_elgg_event_handler('pagesetup','system','socialcommerce_pagesetup');
		
	// Register actions
		elgg_register_action("{$CONFIG->pluginname}/add", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/add.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/edit", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/edit.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/delete", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/delete.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/icon", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/icon.php", 'public');
		elgg_register_action("{$CONFIG->pluginname}/add_category", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/add_category.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/edit_category", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/edit_category.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/category/delete", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/delete_category.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/addcart", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/addcart.php", 'public');
		elgg_register_action("{$CONFIG->pluginname}/remove_cart", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/remove_cart.php", 'public');
		elgg_register_action("{$CONFIG->pluginname}/update_cart", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/update_cart.php", 'public');
		elgg_register_action("{$CONFIG->pluginname}/add_address", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/add_address.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/edit_address", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/edit_address.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/delete_address", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/delete_address.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/makepayment", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/makepayment.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/add_order", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/add_order.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/change_order_status", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/change_order_status.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/add_wishlist", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/add_wishlist.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/remove_wishlist", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/remove_wishlist.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/download", $CONFIG->pluginspath. "{$CONFIG->pluginname}/actions/download.php", 'public');
		elgg_register_action("{$CONFIG->pluginname}/retrieve", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/retrieve.php", 'logged_in');
		
		elgg_register_action("{$CONFIG->pluginname}/contry_tax", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/contry_tax.php", 'logged_in');
				
		elgg_register_action("{$CONFIG->pluginname}/addcommon_tax", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/addcommon_tax.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/addcountry_tax", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/addcountry_tax.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/manage_socialcommerce", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/manage_socialcommerce.php", 'public');
		
		elgg_register_action("{$CONFIG->pluginname}/add_coupon", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/edit_coupon.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/edit_coupon", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/edit_coupon.php", 'logged_in');
		elgg_register_action("{$CONFIG->pluginname}/delete_coupon", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/delete_coupon.php", 'logged_in');
		
		elgg_register_action("{$CONFIG->pluginname}/create_order", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/create_order.php", 'logged_in');
		
		elgg_register_action("{$CONFIG->pluginname}/manage/related_products", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/manage_related_products.php", 'public');
		
		elgg_register_action("{$CONFIG->pluginname}/sold_price", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/sold_product_price_details.php", 'logged_in');
		
		elgg_register_action("{$CONFIG->pluginname}/version_save", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/version_save.php", 'logged_in');
		
		elgg_register_action("{$CONFIG->pluginname}/version_delete", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/version_delete.php", 'logged_in');
		
		elgg_register_action("{$CONFIG->pluginname}/checkout/register", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/checkout_account_create.php", 'public');
		/*** multiple product CSv uploading ***/
		elgg_register_action("{$CONFIG->pluginname}/upload_multi_product", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/upload_multi_product.php", 'public');
		elgg_register_action("{$CONFIG->pluginname}/add_multi_product", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/add_multi_product.php", 'public');
		/**Get the subcategorie deatils */
		elgg_register_action("{$CONFIG->pluginname}/get_category", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/get_product_categories.php", 'public');
		elgg_register_action("{$CONFIG->pluginname}/chk_category_delete", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/chk_category_delete.php", 'public');
		/**List the directories in the ftp root folder*/
		elgg_register_action("{$CONFIG->pluginname}/get_dir_list", $CONFIG->pluginspath . "{$CONFIG->pluginname}/actions/get_dir_list.php", 'public');
		
		function deletespamerce(){
			$off = get_input('off',0);
			$valid_users = array();
			$options = array('types' 	=>'object',
							'subtypes'	=>'stores',
							'limit'		=>99999);
			$stores = elgg_get_entities($options);
				
			foreach($stores as $store){
				$valid_users[$store->owner_guid] = $store->owner_guid;
			}
			
			$options = array('types' 	=>'object',
							'subtypes'	=>'cart',
							'limit'		=>99999);
			$carts = elgg_get_entities($options);
			
			foreach($carts as $cart){
				$valid_users[$cart->owner_guid] = $cart->owner_guid;
			}
			
			$options = array('types' 	=>'object',
							'subtypes'	=>'order',
							'limit'		=>99999);
			$orders = elgg_get_entities($options);
			
			foreach($orders as $order){
				$valid_users[$order->owner_guid] = $order->owner_guid;
			}
					
			
			$valid_users[2] = 2; 
			/*$valid_users[46253] = 46253;
			$valid_users[46158] = 46158;
			$valid_users[45844] = 45844;
			$valid_users[45779] = 45779;
			$valid_users[45355] = 45355;
			$valid_users[43961] = 43961;
			$valid_users[43831] = 43831;
			$valid_users[43770] = 43770;
			$valid_users[43673] = 43673;
			$valid_users[43659] = 43659;*/

			$options = array('types' 	=>'user',
					'limit'		=>	99999,
					'count'		=> true);

			$members_count = elgg_get_entities($options);
			echo "total_members=".$members_count."<br>";

			$options = array('types' 	=>'user',
							'offset'	=> $off,	
							'limit'		=> 200);

			$members = elgg_get_entities($options);
			$count = 1;
			foreach($members as $member){
				if(!in_array($member->guid,$valid_users)){
					echo "username=".$member->username." name = ".$member->name." guid = ".$member->guid."<br>";
					$member->delete();
					$count++;
				}
			}
			echo "Total Spamners = ".$count;
		}
?>