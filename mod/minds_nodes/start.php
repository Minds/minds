<?php
/**
 *  A plugin to communicate with the multisite management interface
 */
namespace minds\plugin\minds_nodes;

use minds\bases;
class start extends bases\plugin{
	
	public function init(){
		//$routes = router::registerRoutes($this->registerRoutes());
		$this->registerClasses();	
		\add_subtype('object', 'node', 'MindsNode');
		\add_subtype('object', 'minds_tier', 'MindsTier');
	
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
	
		\elgg_extend_view('js/elgg', 'minds_nodes/js');
		\elgg_extend_view('css/elgg', 'minds_nodes/css');
	
		\elgg_register_event_handler('pagesetup', 'system', array($this, 'pagesetup'));
	
		// Register an admin menu
		//elgg_register_admin_menu_item('minds', 'minds_tiers');	
		//elgg_register_admin_menu_item('minds', 'manage', 'minds_tiers');   
		\elgg_register_admin_menu_item('configure', 'tiers', 'monitization');
	
		// Endpoint
		\elgg_register_page_handler('tierlogin', array($this, 'tierLoginHandler'));
		\elgg_register_page_handler('nodes', array($this, 'pageHandler'));
	
		// Override the return url on tier orders
		\elgg_register_plugin_hook_handler('urls', 'pay', array($this, 'payOverride'));
	   	
	}

	public function pagesetup(){
		if (\elgg_get_context() == "settings" && \elgg_get_logged_in_user_guid()) {
			$params = array(
				'name' => 'my_nodes',
				'text' => \elgg_echo('minds_node:manage'),
				'href' => "nodes/manage",
			);
			\elgg_register_menu_item('page', $params);
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
		
		if(!$pages[0]){
			//does the user have any nodes setup? If so send them to the manage page
			if($this->getNodes()){
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
	                	     \set_input('username', $pages[1]);
	                	 }
				include('pages/minds_nodes/manage.php');
				break;
	                case 'upgrade' :
	                    \set_input('node_guid', $pages[1]);
	                    include('pages/minds_nodes/upgrade_to.php');
	                    break;
			case 'node':
				\set_input('node_guid', $pages[1]);
				include('pages/minds_nodes/node.php');
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

	public static function getNodes($owner_guid, $limit=12, $offset=""){
		$nodes = elgg_get_entities(array('type'=>'object', 'subtype'=>'node', 'limit'=>$limit, 'offset'=>$offset));
		return $nodes;
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
}




define('MINDS_EXPIRES_DAY', 86400);
define('MINDS_EXPIRES_WEEK', 604800);
define('MINDS_EXPIRES_MONTH', 2419200);
define('MINDS_EXPIRES_YEAR', 31536000);
