<?php
/**
 *  A plugin to communicate with the multisite management interface
 */
namespace minds\plugin\minds_nodes;

use Minds\Components;
use Minds\Core;
use Minds\Core\plugins;

class start extends Components\Plugin{
	
	/**
	 * Return the favicon of a remote domain
	 * @param type $url The domain to retrieve.
	 */
	static function getIconFromMetadata($url) {
	    
	    // Use Google S2 for now to offload complexity and load. TODO: Discuss whether scraping is better
	    return "https://www.google.com/s2/favicons?domain=$url";
	    
	}
    
	public function init(){
		
		/**
		 * The payments plugin is mandatory to this plugin working, therefore we must enable it
		 */
		if(!plugins::isActive('payments') && elgg_is_admin_logged_in()){
			plugins::factory('payments')->activate();
		}
		
		//$routes = router::registerRoutes($this->registerRoutes());
		$this->registerClasses();	
		\add_subtype('object', 'node', 'MindsNode');
		\add_subtype('object', 'minds_tier', 'MindsTier');

		\elgg_register_plugin_hook_handler('entities_class_loader', 'all', function($hook, $type, $return, $row){
			if($row->subtype == 'node')
				return new \MindsNode($row);

			if($row->subtype == 'minds_tier')
				return new \MindsTier($row);
		});
	
		// Register action
		\elgg_register_action('minds/minds_tiers/new', dirname(__FILE__) . '/actions/minds_tiers/new.php', 'admin');
		\elgg_register_action('minds/minds_tiers/batch', dirname(__FILE__) . '/actions/minds_tiers/batch.php', 'admin');
		\elgg_register_action('minds/minds_tiers/delete', dirname(__FILE__) . '/actions/minds_tiers/delete.php', 'admin');
		
		\elgg_register_action("checkdomain", dirname(__FILE__) . "/actions/checkdomain.php", 'public');
		\elgg_register_action("payment", dirname(__FILE__) . "/actions/payment.php", 'public');
		\elgg_register_action("registernode", dirname(__FILE__) . "/actions/registernode.php");
		\elgg_register_action("registernewnode", dirname(__FILE__) . "/actions/registernewnode.php");
		\elgg_register_action("select_tier", dirname(__FILE__) . "/actions/select_tier.php", 'public');
		\elgg_register_action("upgrade_to", dirname(__FILE__) . "/actions/upgrade_to.php");
		\elgg_register_action("renamenode", dirname(__FILE__) . "/actions/renamenode.php");
		
		\elgg_register_action("nodes_upgrade", dirname(__FILE__) . "/actions/upgrade.php");
		\elgg_register_action("node/edit", dirname(__FILE__) . "/actions/edit.php");
		\elgg_register_action("node/delete", dirname(__FILE__) . "/actions/delete.php");
		\elgg_register_action("nodes/contact", dirname(__FILE__) . "/actions/contact.php", 'public');
	
		\elgg_extend_view('js/elgg', 'minds_nodes/js');
		\elgg_extend_view('css/elgg', 'minds_nodes/css');
	
//		\elgg_register_event_handler('pagesetup', 'system', array($this, 'pagesetup'));
		
		\elgg_extend_view('core/settings/statistics', 'minds_nodes/statistics');
	
		// Register an admin menu
		//elgg_register_admin_menu_item('minds', 'minds_tiers');	
		//elgg_register_admin_menu_item('minds', 'manage', 'minds_tiers');   
		\elgg_register_admin_menu_item('configure', 'tiers', 'monitization');
	
		// Endpoint
		\elgg_register_page_handler('tierlogin', array($this, 'tierLoginHandler'));
		\elgg_register_page_handler('nodes', array($this, 'pageHandler'));
	
		// Override the return url on tier orders
		\elgg_register_plugin_hook_handler('urls', 'pay', array($this, 'payOverride'));
		
		// Node Icons - handle remote icons, cached icons etc
		elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'minds\plugin\minds_nodes\start::iconUrlHook');	
			   	
	}

    public function pagesetup(){
        return true;
		if (\elgg_get_context() == "settings" && \elgg_get_logged_in_user_guid()) {
			$params = array(
				'name' => 'my_nodes',
				'text' => \elgg_echo('minds_node:manage'),
				'href' => "nodes/manage",
			);
			\elgg_register_menu_item('page', $params);
		}
		
		if (elgg_is_logged_in()) {
		    //if (self::getNodes(elgg_get_logged_in_user_entity(), true)) {
			\elgg_register_menu_item('site', array(
			    'name' => 'nodes',
			    'text' => '<span class="entypo">&#xE817;</span> My sites',
			    'href' => '#nodes-switcher',
			    'title' => elgg_echo('nodes:mynodes'),
			    'priority' => 9999, // Make sure we're last, so the sidebar selector works...
			    'rel' => 'toggle'
			));
		   // }
		}
	}
	
	/**
	 * Retrieve nodes/count of nodes belonging to a user, caching the result.
	 * @param \minds\plugin\minds_nodes\ElggUser $user
	 * @param array|int|false $count
	 */
	public static function getNodes(ElggUser $user = null, $count = false) {
	    if(!elgg_is_logged_in()){
		return NULL;
	    }
	    if (!$user) $user = elgg_get_logged_in_user_entity ();
	    
	    $params = array(
		'type' => 'object',
		'subtype' => 'node',
		'count' => $count,
		'owner_guid' => $user->guid
	    );
	    
	    if (!$count) $params['limit'] = 999;
	    
	    $cacher = \Minds\Core\Data\cache\factory::build();
	    $key = "object::node::{$user->guid}";
	    if ($count) $key.= "::count";
	    
		$value = $cacher->get($key);
	    if (!$value){
		//error_log('MPDEBUG - Value for key ' . $key . ' not in cache '  . print_r($params, true));
		$value = elgg_get_entities($params);
		$cacher->set($key, $value);
	    } else {
		//error_log('MPDEBUG - Value for key ' . $key . ' retrieved from cache ' . print_r($params, true));
	    }
	    
	    return $value;
	}
	
	public static function iconUrlHook($hook, $type, $returnvalue, $params) 
	{
	    $node = $params['entity'];
	    $size = $params['size'];
	    if (elgg_instanceof($node, 'object', 'node')) {

		$icon = null;
		if ($node->launched) {

		    // Have we cached an icon recently?
		    $label_ts = "cached_icon_{$size}_ts";
		    $label_icon = "cached_icon_{$size}_url";
		    if ($node->$label_ts > time() - (60*60*24*7)) {
			$icon = $node->$label_icon;

			//error_log('ICON Loaded from cache ' . $icon);
		    }

		    // No icon, attempt to retrieve it 
		    if (!$icon) {
			$icon = self::getIconFromMetadata($node->getURL());
			if ($icon) {
			    $node->$label_icon = $icon;
			    $node->$label_ts = time();

			    $node->save();

			    //error_log('ICON Saving ' . $icon);
			}
		    }

		    return $icon;
		}

		// No icon, return default
		if (!$icon)
		    return elgg_get_site_url() . '_graphics/icon.png';
		    //elgg_get_site_url() . '_graphics/icons/default/'.$size.'.png';
	    }
	}
	
	/**
	 * @todo, move tiers into its own object
	 */
	public static function tiersGetProduct($product_id){
		$access = \elgg_set_ignore_access();
	    $products = \elgg_get_entities_from_metadata(array(
	        'types' => array('object'),
	        'subtypes' => array('Minds_product'),
	        'metadata_name_value_pairs' => array(
	            'name' => 'product_id',
	            'value' => $product_id,
	        ),
	    ));
	    \elgg_set_ignore_access($access);
	    if (!$products)
	        throw new \Exception("No product $product_id found");
	
	    return $products[0];
	}
	
	public static function tiersGetFeatures(){
		return array('users', 'bandwidth', 'own_domain', 'support');
	}

	public static function tiersGetCurrentValidTier($user){
		global $DB;
    
	    if (!$user) $user = elgg_get_logged_in_user_entity ();
	    if (!$user) return false;
	    
	    $tiers_guid = array();
	    $ia = elgg_set_ignore_access();
	
	    // Get tiers
	    if ($tiers = elgg_get_entities(array('type' => 'object', 'subtype' => 'minds_tier'))){
	        foreach ($tiers as $tier){
	            $tiers_guid[] = $tier->guid;
			}
	    }
	
	    $orders = elgg_get_entities(array('type' => 'object', 'subtype' => 'pay', 'owner_guid'=>$user->guid, 'limit'=>100000));
	    $nodes = elgg_get_entities(array('type'=>'object', 'subtype' => 'node', 'owner_guid'=>$user->guid, 'limit'=>10000));
	   
	    if (count($orders)) {
	  // 	return false; 
	       foreach ($orders as $order)
	       {
	           $tier = get_entity($order->object_guid);
	      
		   if($order->status == 'canceled'){
			return false;
		   }
	     
	           $expires = $tier->expires;
	           if (!$expires) $expires = MINDS_EXPIRES_YEAR; // Default to year
	           
	           // If cost is 0, then never expire 
	           if ($tier->price == 0) {
	               elgg_set_ignore_access($ia);
	               return $order;
	           }
	           
	           if ($order->time_updated >= (time() - $expires))
	           {
	                elgg_set_ignore_access($ia);
	                return $order;
	           }
	       }
	
	   }
	
	   elgg_set_ignore_access($ia);
	   return false;
	}

	public function tierLoginHandler(){
		 $_SESSION['fb_referrer'] = 'y'; // Prevent Bootcamp intercepting login
	    $_SESSION['__tier_selected'] = get_input('tier');
	    $_SESSION['_from_tier'] = 'y';
	    $content = "<div class=\"register-popup\">".elgg_view_form('register', null, array('returntoreferer' => true))."</div>";
	
	    // If we've returned to the window after a successful login, then refresh back to parent
	    if (elgg_is_logged_in()) {
		$content .= "
		<script>
		    window.opener.location.reload();  
	
		    window.close();
		</script>
		";
	    }
	
	    $params = array(
		'title' => elgg_echo('minds_widgets:tab:'.$tab),
		'content' => $content,
		'sidebar' => ''
	    );
	
	    echo elgg_view_page('Login', elgg_view_layout('default', $params),'default_popup');
	    return true;
	}
	
	/**
	 * @todo, move this to new page handler
	 */
	public function pageHandler($pages){
        return forward(REFERRER);
		global $CONFIG;

		\elgg_register_menu_item('title', array(
				'name' => 'launch',
				'href' => 'nodes/launch',
				'text' => \elgg_echo('minds_node:launch'),
				'link_class' => 'elgg-button elgg-button-action',
			));
		if(\elgg_is_logged_in()){
			\elgg_register_menu_item('title', array(
	                        'name' => 'manage',
	                        'href' => 'nodes/manage',
	                        'text' => \elgg_echo('minds_node:manage'),
	                        'link_class' => 'elgg-button elgg-button-action',
	                ));
		}
		
		
		//if this is a multisite then we forward to minds.com.
		if(\Minds\Core\minds::detectMultisite()){
			forward('https://www.minds.com/nodes/launch?referrer='.\elgg_get_plugin_setting('owner_username','minds_nodes'));
			return true;
		}
		
		if(!$pages[0]){
			//does the user have any nodes setup? If so send them to the manage page
			if(self::getNodes(elgg_get_logged_in_user_entity(), true)){
				$pages[0] = 'manage';
			} else {
			//if not then send them to the launch page
				$pages[0] = 'launch';
			}
		}
	
		switch($pages[0]) {
			case 'launch':
				include('pages/minds_nodes/launch.php');
				break;
			case 'manage':
			      if(isset($pages[1])){
	              	\set_input('slug', $pages[1]);
	              }
				include('pages/minds_nodes/manage.php');
				break;
			case 'node':
				\set_input('node_guid', $pages[1]);
				include('pages/minds_nodes/node.php');
				break;
			case 'upgrade':
				\set_input('node_guid', $pages[1]);
				include('pages/minds_nodes/upgrade.php');
				break;
			case 'ping':
				$title = \elgg_echo("register:node:testping");
	
				$content = \elgg_view_title($title);
				
				$content = \elgg_view('forms/pingtest', array('domain' => \get_input('domain')));
				    
				$body = \elgg_view_layout("one_column", array('content' => $content));
				
				echo \elgg_view_page($title, $body);
				return;
				break;
			case 'index':
			default:
				include('pages/minds_nodes/index.php');
		}
		return true;
	}
	
	public static function payOverride($hook, $type, $return, $params) {

	    if ($order = $params['order']) {
	
			$items = unserialize($order->items);
			if ($items) {
			    // Assume that if the first one is a tier then everything is 
			    $ia = \elgg_set_ignore_access();
		
			    $tier = \get_entity($items[0]->object_guid, 'object');
			    if (\elgg_instanceof($tier, 'object', 'minds_tier'))
				    $return['return'] = \elgg_get_site_url() . 'nodes/manage/';
		
			    \elgg_set_ignore_access($ia);
		
			    error_log('PAYPAL: Return url sent to ' . $return['return']);
		
			    return $return;
			}
	
	    }
	}
	
	public function sendEmail($node){
		elgg_set_viewtype('email');
		//\elgg_send_email('mark@minds.com', 'mark@kramnorth.com', 'New Order', '<h1>Thanks for your order..</h1> <p>Your order has been succesfully processed</p>');
		if(core\plugins::isActive('phpmailer')){
			$view = elgg_view('minds_nodes/welcome', array('node'=>$node));
			$to = array(
				$node->getOwnerEntity(false)->getEmail(),
				'mark@minds.com',
				'bill@minds.com'
			);

			\phpmailer_send('info@minds.com', 'Mark & Bill, from Minds', $to, '', 'Welcome to your new site', $view, NULL, true);
		}
		elgg_set_viewtype('default');
	}
}




define('MINDS_EXPIRES_DAY', 86400);
define('MINDS_EXPIRES_WEEK', 604800);
define('MINDS_EXPIRES_MONTH', 2419200);
define('MINDS_EXPIRES_YEAR', 31536000);
